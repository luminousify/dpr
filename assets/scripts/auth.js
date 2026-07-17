/*
 * auth.js - DPR PWA persistent local session (Ticket 7 / Phase 5.1)
 *
 * Caches the operator's identity (NOT credentials) to the IndexedDB
 * `session` store so the PWA form can render with the operator's
 * name/divisi/kanit after the CI3 session cookie has expired or while the
 * server is unreachable (CONTEXT.md Decision 3, SPEC 5.1).
 *
 * What this does NOT do: bypass server-side auth for writes. The sync
 * endpoint (api/Sync::submit) still requires a valid CI3 session cookie.
 * If the cookie is gone, the operator must re-authenticate online at least
 * once to refresh it. This module makes the FORM usable offline, not the
 * SYNC executable offline (Ticket 6's 401 handling surfaces the re-login
 * prompt via the badge).
 *
 * Loaded via a plain <script> tag in input_dpr_pwa.php AFTER db.js (it
 * depends on DPRDb) and BEFORE sync.js. Exposes the `DPRAuth` global.
 * Conventions match db.js / sync.js: IIFE, 2-space indent, function
 * declarations, no ES module syntax, Promise-based.
 *
 * Session store shape (keyPath: 'key'):
 *   { key: 'identity', value: {id_operator, nik, nama_operator, jabatan,
 *                              divisi, nama_kanit, status},
 *     cached_at: ISOString }
 * The identity value is whitelisted server-side by
 * Pwa_reference_normalizer::normalize_session_identity (drops password_op).
 */
var DPRAuth = (function () {

  var SESSION_KEY = 'identity';
  var PING_ENDPOINT = 'login_op/pwa_session_ping';
  // Throttle re-validation to once per minute so rapid pageshow/online
  // bursts (iOS foregrounding can fire several) don't hammer the server.
  var REVALIDATE_MIN_INTERVAL_MS = 60000;

  var _lastRevalidateAt = 0;

  // ---- internals -----------------------------------------------------

  function _baseUrl() {
    return (typeof BASE_URL !== 'undefined') ? BASE_URL : '/';
  }

  function _nowOnline() {
    return (typeof navigator !== 'undefined') ? navigator.onLine : true;
  }

  // ---- public API ----------------------------------------------------

  // Cache operator identity to IndexedDB. `identity` is the normalized
  // shape from Pwa_reference_normalizer (no password_op). Idempotent -
  // safe to call on every online page load. Resolves to the stored record.
  function cacheSession(identity) {
    if (typeof DPRDb === 'undefined') {
      return Promise.reject(new Error('cacheSession: DPRDb unavailable'));
    }
    if (!identity || typeof identity !== 'object') {
      return Promise.reject(new Error('cacheSession: identity must be an object'));
    }
    var record = {
      key: SESSION_KEY,
      value: identity,
      cached_at: new Date().toISOString()
    };
    return DPRDb.putKey(DPRDb.STORE_SESSION, record).then(function () {
      return record;
    });
  }

  // Read the cached identity. Resolves to the identity object, or null if
  // nothing is cached (first visit, or cache was cleared).
  function getSession() {
    if (typeof DPRDb === 'undefined') {
      return Promise.resolve(null);
    }
    return DPRDb.getKey(DPRDb.STORE_SESSION, SESSION_KEY).then(function (rec) {
      if (!rec || !rec.value) {
        return null;
      }
      return rec.value;
    });
  }

  // Delete the cached identity. Called on explicit logout if we ever wire
  // one from the PWA. Resolves to true.
  function clearSession() {
    if (typeof DPRDb === 'undefined') {
      return Promise.resolve(true);
    }
    return DPRDb.deleteKey(DPRDb.STORE_SESSION, SESSION_KEY);
  }

  // True if a cached identity exists (offline login is available).
  // Convenience for synchronous checks; uses getSession internally.
  function isOfflineAuthAvailable() {
    return getSession().then(function (id) {
      return !!id && !!id.nama_operator;
    });
  }

  // Silently re-validate the CI3 session against the server. On success,
  // refreshes the cached identity (name/divisi may have changed). On
  // failure (valid:false), leaves the cache intact so the offline form
  // stays usable; the sync layer (Ticket 6) surfaces the re-login prompt.
  // No-op if offline or throttled. Always resolves (never rejects).
  function _revalidate() {
    if (!_nowOnline()) {
      return Promise.resolve();
    }
    var now = Date.now();
    if (now - _lastRevalidateAt < REVALIDATE_MIN_INTERVAL_MS) {
      return Promise.resolve();
    }
    _lastRevalidateAt = now;

    return new Promise(function (resolve) {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', _baseUrl() + PING_ENDPOINT, true);
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.timeout = 10000;
      xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
          return;
        }
        if (xhr.status !== 200) {
          // Network blip or server error: leave the cache as-is.
          resolve();
          return;
        }
        var parsed;
        try {
          parsed = JSON.parse(xhr.responseText || '{}');
        } catch (e) {
          resolve();
          return;
        }
        if (parsed && parsed.valid && parsed.identity) {
          cacheSession(parsed.identity).then(function () {
            resolve();
          }, function () {
            resolve();
          });
        } else {
          // Session expired. Do NOT clear the cache - the operator may be
          // mid-shift and the offline form still needs the identity. The
          // sync badge will turn red on the next 401 (Ticket 6).
          resolve();
        }
      };
      xhr.onerror = function () { resolve(); };
      xhr.ontimeout = function () { resolve(); };
      xhr.send();
    });
  }

  // Wire pageshow + online triggers for silent re-validation. Call once on
  // DOMReady (the inline init in input_dpr_pwa.php does this).
  function init() {
    if (typeof DPRDb === 'undefined') {
      console.error('[PWA] DPRDb unavailable - auth cache disabled');
      return;
    }
    // Best-effort immediate re-validation on load (if online).
    _revalidate();
    // iOS foregrounding fires pageshow; bonus `online` for Android.
    window.addEventListener('pageshow', function () {
      _revalidate();
    });
    if ('ononline' in window) {
      window.addEventListener('online', function () {
        _revalidate();
      });
    }
  }

  return {
    cacheSession: cacheSession,
    getSession: getSession,
    clearSession: clearSession,
    isOfflineAuthAvailable: isOfflineAuthAvailable,
    init: init
  };
})();
