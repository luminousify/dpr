/*
 * sync.js - DPR PWA sync engine (Ticket 6 / Phase 4.1)
 *
 * Reads pending records from IndexedDB (Ticket 5's DPRDb) and POSTs each
 * record's `form` (NOT the whole record) to the Ticket 2 fast-accept
 * endpoint at api/Sync/submit. The Ticket 3 drain worker
 * (c_sync::queue_drain) then moves queued payloads into t_production_op
 * idempotently, closing the loop: offline save -> queue -> drain -> real
 * tables -> existing DPR listing.
 *
 * Loaded via a plain <script> tag in input_dpr_pwa.php AFTER db.js (it
 * depends on DPRDb) and BEFORE the SW registration. Exposes a single
 * global namespace `DPRSync`. Conventions match db.js / input_dpr.js:
 * 2-space indent, function declarations, no ES module syntax, Promise-based.
 *
 * iOS note (CONTEXT.md Decision 2): iOS Safari has no Background Sync API.
 * Sync is triggered from the page, not by a Service Worker:
 *   - on `pageshow` (app foregrounded) -> syncAll()
 *   - on `online` event (where supported) -> syncAll()
 *   - on form save (submit intercept) -> syncOne(newRecord) (best-effort)
 * If the app is closed, sync resumes the next time it opens.
 *
 * Idempotency (CONTEXT.md Decision 6): the server's queue table has UNIQUE
 * KEY uk_id_production, api/Sync::submit treats a duplicate as 200 queued,
 * and the drain worker treats already-drained ids as clean no-ops. So a
 * duplicate POST (retry after a network blip mid-flight) is safe.
 *
 * Error model (from api/Sync::submit response contract):
 *   200 -> resolve ok: true   (record marked 'synced')
 *   400 -> resolve ok:false   (record marked 'error'; unfixable client-side)
 *   401 -> resolve ok:false   (left 'pending'; surface re-login prompt;
 *                              NOT auto-retried - next pageshow/online will)
 *   422 -> resolve ok:false   (record marked 'error'; unfixable client-side)
 *   5xx / network / timeout   -> reject transient (caller may retry)
 */
var DPRSync = (function () {

  // Endpoint, relative to BASE_URL (defined inline by input_dpr_pwa.php).
  var ENDPOINT = 'api/Sync/submit';

  // Backoff schedule (SPEC 4.1): 2s, 4s, 8s, 16s, 32s, 60s, 60s, 60s...
  var BACKOFF_BASE_MS = 2000;
  var BACKOFF_MAX_MS = 60000;
  // Hard cap so a permanently-bad record cannot loop forever. At this
  // schedule ~8 attempts is ~4 minutes total. After the cap the record
  // stays `pending` and surfaces in the badge for operator attention.
  var MAX_ATTEMPTS = 8;

  // XHR timeout. Long enough for a slow mobile uplink, short enough that a
  // hung connection rejects as transient (and becomes retry-eligible).
  var REQUEST_TIMEOUT_MS = 30000;

  // Per-id in-flight cache: avoids duplicate concurrent POSTs for the same
  // record (e.g. save-time syncOne racing with a pageshow syncAll). The
  // server is idempotent so a duplicate POST would be safe, but wasteful.
  var _inFlight = {};

  // Per-id retry guard: ensures only ONE backoff chain runs per record at a
  // time. Without this, save-time syncOne and a concurrent pageshow syncAll
  // could each call scheduleRetry(record, 1) for the same record and spawn
  // two parallel setTimeout loops - effectively doubling MAX_ATTEMPTS.
  // The guard is set when scheduleRetry starts a chain and cleared when the
  // chain settles (success, permanent failure, or cap reached).
  var _retryScheduled = {};

  // Single-flight guard for syncAll itself, so pageshow + online firing in
  // quick succession don't kick off two parallel loops.
  var _syncAllPromise = null;

  // Cached jQuery nodes for the badge (defined in input_dpr_pwa.php by T5).
  // Cached lazily so refreshBadge works even if the badge is absent (e.g.
  // the script is ever included on a different page).
  var _$badge = null;
  var _$count = null;
  var _badgeLookedUp = false;

  // ---- internals -----------------------------------------------------

  function _badgeNodes() {
    if (!_badgeLookedUp) {
      _$badge = $('#pwa-pending-badge');
      _$count = $('#pwa-pending-count');
      _badgeLookedUp = true;
    }
    return { badge: _$badge, count: _$count };
  }

  function _isTransientStatus(status) {
    // status === 0 covers network failure / CORS / offline emulation.
    return status === 0 || (status >= 500 && status <= 599);
  }

  // Surface a 401 to the operator: relabel the existing badge red so they
  // know sync is blocked on re-login. Idempotent + cheap; clearAuthExpiredNotice()
  // restores the normal badge paint on the next successful sync. Uses the
  // same DOM node T5 added (no new element), so layout doesn't shift.
  function noticeAuthExpired() {
    var nodes = _badgeNodes();
    if (!nodes.badge || !nodes.badge.length) {
      return;
    }
    nodes.badge.removeClass('badge-warning').addClass('badge-danger');
    // Keep the count text node (#pwa-pending-count) intact so refreshBadge
    // still has something to update on subsequent state changes.
    nodes.badge.attr('title', 'Sesi habis - silakan login ulang untuk sync');
    nodes.badge.show();
  }

  // Restore the normal badge appearance after the session is back / a sync
  // succeeds. Called from refreshBadge() so any successful state change
  // clears the warning.
  function clearAuthExpiredNotice() {
    var nodes = _badgeNodes();
    if (!nodes.badge || !nodes.badge.length) {
      return;
    }
    nodes.badge.removeClass('badge-danger').addClass('badge-warning');
    nodes.badge.removeAttr('title');
  }

  // POST record.form to the endpoint. Resolves on 200 with the server's
  // id_production. Rejects on any non-200 path with an Error carrying:
  //   .httpStatus (number)   .serverError (string|null)
  //   .permanent (bool)      400/422 - record is unfixable
  //   .authExpired (bool)    401 - session gone, prompt re-login
  //   .transient (bool)      5xx / 0 / timeout - caller may retry
  function _postForm(record) {
    var baseUrl = (typeof BASE_URL !== 'undefined') ? BASE_URL : '/';
    var url = baseUrl + ENDPOINT;
    // record.form already carries a top-level id_production (the form field
    // <input name="id_production"> is serialized into it); sending the whole
    // record would store PWA-local junk (status/created_at/synced_at) in
    // t_sync_queue.payload that the drain then has to ignore.
    var body = JSON.stringify(record.form || {});

    return new Promise(function (resolve, reject) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', url, true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      // Same-origin: cookies (ci_session) attach automatically. Set anyway
      // for explicitness; harmless on same-origin, required if BASE_URL ever
      // moves cross-origin.
      xhr.withCredentials = true;
      xhr.timeout = REQUEST_TIMEOUT_MS;

      xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
          return;
        }
        var status = xhr.status;
        var parsed;
        try {
          parsed = JSON.parse(xhr.responseText || '{}');
        } catch (e) {
          parsed = {};
        }

        if (status === 200) {
          resolve({ id_production: parsed.id_production || record.id_production });
          return;
        }

        var msg = parsed.error || ('HTTP ' + status);
        var err = new Error('Sync failed: ' + msg);
        err.httpStatus = status;
        err.serverError = parsed.error || null;
        err.permanent = (status === 400 || status === 422);
        err.authExpired = (status === 401);
        err.transient = _isTransientStatus(status);
        reject(err);
      };

      xhr.onerror = function () {
        var err = new Error('Network error during sync');
        err.httpStatus = 0;
        err.serverError = null;
        err.permanent = false;
        err.authExpired = false;
        err.transient = true;
        reject(err);
      };

      xhr.ontimeout = function () {
        var err = new Error('Sync timed out');
        err.httpStatus = 0;
        err.serverError = null;
        err.permanent = false;
        err.authExpired = false;
        err.transient = true;
        reject(err);
      };

      xhr.send(body);
    });
  }

  // Persist the outcome of a sync attempt to IndexedDB and refresh the
  // badge. Returns a Promise that resolves once the write settles.
  function _persistOutcome(id, status, extra) {
    return DPRDb.updatePendingStatus(id, status, extra || null)
      .then(function () { refreshBadge(); }, function () { refreshBadge(); });
  }

  // ---- public API ----------------------------------------------------

  // Attempt to sync one record.
  //
  // Resolves with a result object:
  //   { ok: true }                       on 200 (record marked 'synced')
  //   { ok: false, reason: 'permanent' } on 400/422 (record marked 'error')
  //   { ok: false, reason: 'auth' }      on 401 (left 'pending', no auto-retry)
  //   { ok: false, reason: 'missing' }   if the record vanished from IDB
  // Rejects (transient Error) on 5xx / network / timeout - record is left
  // 'pending' and the caller may scheduleRetry.
  function syncOne(record) {
    if (!record || !record.id_production) {
      return Promise.reject(new Error('syncOne: record missing id_production'));
    }
    if (typeof DPRDb === 'undefined') {
      return Promise.reject(new Error('syncOne: DPRDb unavailable'));
    }

    var id = record.id_production;
    if (_inFlight[id]) {
      // Share the in-flight attempt. Caller gets the same outcome signal.
      return _inFlight[id];
    }

    var work = DPRDb.getPending(id).then(function (latest) {
      if (!latest) {
        return { ok: false, reason: 'missing' };
      }
      // Skip if another path already synced it (e.g. pageshow syncAll beat
      // the save-time syncOne to the punch).
      if (latest.status === 'synced') {
        return { ok: true };
      }
      return _postForm(latest).then(function () {
        return _persistOutcome(id, 'synced', {
          synced_at: new Date().toISOString(),
          error_message: null
        }).then(function () {
          // A successful POST means the session is fine again - clear any
          // lingering auth-expired badge styling from a prior 401.
          clearAuthExpiredNotice();
          return { ok: true };
        });
      });
    }).then(function (result) {
      delete _inFlight[id];
      return result;
    }, function (err) {
      delete _inFlight[id];
      if (err && err.permanent) {
        return _persistOutcome(id, 'error', {
          error_message: err.serverError || err.message || 'Permanent sync failure'
        }).then(function () {
          return { ok: false, reason: 'permanent', error: err };
        });
      }
      if (err && err.authExpired) {
        // Leave status 'pending'. Surface to the operator via the badge
        // (turn it red + relabel) so they know to re-login. The next
        // pageshow/online/save attempt will retry - and once the session
        // is refreshed, syncAll will pick the record up.
        console.warn('[PWA] sync auth-expired for', id, '- leaving pending');
        noticeAuthExpired();
        return { ok: false, reason: 'auth', error: err };
      }
      // Transient (5xx/network/timeout). Refresh badge (status unchanged)
      // and re-reject so the caller can scheduleRetry.
      refreshBadge();
      throw err;
    });

    _inFlight[id] = work;
    return work;
  }

  // Sync all non-synced records, oldest first. Returns a Promise that
  // resolves to { synced, failed, pending, auth }.
  //   synced   = landed in t_sync_queue (200)
  //   failed   = permanently rejected by server (400/422)
  //   pending  = transient failure; scheduleRetry has been kicked off
  //   auth     = 401 (session expired); no auto-retry, surface re-login
  function syncAll() {
    if (_syncAllPromise) {
      return _syncAllPromise;
    }
    if (typeof DPRDb === 'undefined') {
      return Promise.reject(new Error('syncAll: DPRDb unavailable'));
    }

    var p = DPRDb.getAllPending().then(function (records) {
      // Non-synced only, oldest first (FIFO fairness across operators).
      var pending = (records || []).filter(function (r) {
        return r && r.status !== 'synced';
      }).sort(function (a, b) {
        var ta = a.created_at ? Date.parse(a.created_at) : 0;
        var tb = b.created_at ? Date.parse(b.created_at) : 0;
        return (ta || 0) - (tb || 0);
      });

      var counts = { synced: 0, failed: 0, pending: 0, auth: 0 };
      var authShortCircuit = false;

      // Sequential to keep the per-request burst small (an operator may
      // sync dozens of records after a long offline shift) and to let the
      // per-record in-flight guard do its job. Each step awaits the prior.
      return pending.reduce(function (chain, rec) {
        if (authShortCircuit) {
          // Every subsequent request will also 401; stop early.
          counts.auth++;
          return chain;
        }
        return chain.then(function () {
          return syncOne(rec).then(function (res) {
            if (res && res.ok) {
              counts.synced++;
            } else if (res && res.reason === 'permanent') {
              counts.failed++;
            } else if (res && res.reason === 'auth') {
              counts.auth++;
              authShortCircuit = true;
            }
            // reason === 'missing': record vanished mid-loop, neither count.
          }, function (err) {
            if (err && err.transient) {
              counts.pending++;
              // Kick off a backoff retry; don't block syncAll on it.
              scheduleRetry(rec, 1).catch(function (e) {
                console.warn('[PWA] retry chain failed for', rec.id_production, e);
              });
            } else {
              counts.failed++;
            }
          });
        });
      }, Promise.resolve()).then(function () {
        refreshBadge();
        return counts;
      });
    });

    _syncAllPromise = p.then(function (r) {
      _syncAllPromise = null;
      return r;
    }, function (err) {
      _syncAllPromise = null;
      refreshBadge();
      throw err;
    });
    return _syncAllPromise;
  }

  // Schedule a single record's retry with exponential backoff. `attempt`
  // is 1-based. After MAX_ATTEMPTS the record is left `pending` and
  // surfaces in the badge for operator attention. Always resolves (never
  // rejects) - callers fire it fire-and-forget.
  //
  // Guarded by _retryScheduled so two callers (e.g. save-time + syncAll)
  // transient-failing the same record can't spawn two parallel chains.
  // The first caller's chain owns retries until it settles; later callers
  // get a no-op resolve. The next pageshow/online/save after the chain
  // settles is free to start a fresh one.
  function scheduleRetry(record, attempt) {
    attempt = attempt || 1;
    if (!record || !record.id_production) {
      return Promise.resolve();
    }
    var id = record.id_production;

    // First attempt for this id claims the slot. A subsequent caller (e.g.
    // syncAll) transient-failing the same record gets a polite no-op.
    if (attempt === 1) {
      if (_retryScheduled[id]) {
        return Promise.resolve();
      }
      _retryScheduled[id] = true;
    }

    if (attempt > MAX_ATTEMPTS) {
      console.warn('[PWA] sync exhausted retries for', id);
      delete _retryScheduled[id];
      refreshBadge();
      return Promise.resolve();
    }

    // 2s, 4s, 8s, 16s, 32s, capped at 60s.
    var delay = Math.min(BACKOFF_BASE_MS * Math.pow(2, attempt - 1), BACKOFF_MAX_MS);

    return new Promise(function (resolve) {
      setTimeout(resolve, delay);
    }).then(function () {
      // Re-read; the record may have been synced (another path) or deleted.
      return DPRDb.getPending(id);
    }).then(function (latest) {
      if (!latest || latest.status === 'synced') {
        delete _retryScheduled[id];
        return;
      }
      return syncOne(latest).then(function () {
        // ok or auth or permanent: syncOne has already persisted the right
        // status. Chain done.
        delete _retryScheduled[id];
      }, function (err) {
        if (err && err.transient) {
          // Recurse - keeps the _retryScheduled[id] slot claimed so a
          // concurrent caller can't start a parallel chain.
          return scheduleRetry(latest, attempt + 1);
        }
        // permanent / auth: syncOne has handled persistence. Chain done.
        delete _retryScheduled[id];
      });
    }).catch(function (err) {
      delete _retryScheduled[id];
      console.warn('[PWA] retry error for', id, err);
    });
  }

  // Refresh the pending badge from DPRDb.countPending(). Safe to call on
  // any page; if the badge is absent or DPRDb is unloaded, it no-ops.
  function refreshBadge() {
    if (typeof DPRDb === 'undefined') {
      return;
    }
    var nodes = _badgeNodes();
    if (!nodes.badge || !nodes.badge.length) {
      // Badge not present (e.g. script ever included on a different page).
      // The function is still a useful public API for the submit intercept
      // to call after a save even when there's nothing to paint.
      return;
    }
    DPRDb.countPending().then(function (n) {
      if (n > 0) {
        nodes.count.text(n);
        nodes.badge.show();
      } else {
        // All synced: also clear any lingering auth-expired styling.
        clearAuthExpiredNotice();
        nodes.badge.hide();
      }
    }).catch(function (err) {
      console.warn('[PWA] countPending failed', err);
    });
  }

  // Wire up the pageshow + online triggers. Call once on DOMReady (the
  // inline submit-intercept in input_dpr_pwa.php does this after the form
  // handlers are bound). The form-save trigger is invoked explicitly from
  // the submit intercept via DPRSync.syncOne(record) (fire-and-forget).
  function init() {
    if (typeof DPRDb === 'undefined') {
      console.error('[PWA] DPRDb unavailable - sync disabled');
      return;
    }

    // Initial badge paint.
    refreshBadge();

    // iOS Safari fires pageshow when the app is foregrounded or re-opened
    // (also on regular loads, where syncAll is a no-op if nothing is
    // pending). This is the primary iOS sync trigger; do NOT remove even
    // though Background Sync would be nicer on Android Chrome - it isn't
    // available on iOS (CONTEXT.md Decision 2).
    window.addEventListener('pageshow', function () {
      syncAll().catch(function (err) {
        console.warn('[PWA] pageshow syncAll failed', err && (err.message || err), err && err.stack);
      });
    });

    // Bonus: the `online` event fires the moment the network returns after
    // an offline spell. Safe to ignore if unsupported (no event = no extra
    // trigger; pageshow still covers foregrounding).
    if ('ononline' in window) {
      window.addEventListener('online', function () {
        syncAll().catch(function (err) {
          console.warn('[PWA] online syncAll failed', err && (err.message || err), err && err.stack);
        });
      });
    }
  }

  return {
    syncOne: syncOne,
    syncAll: syncAll,
    scheduleRetry: scheduleRetry,
    refreshBadge: refreshBadge,
    init: init
  };
})();
