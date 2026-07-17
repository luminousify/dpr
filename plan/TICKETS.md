# TICKETS: DPR PWA + Async Queue

Each ticket is a tracer-bullet slice. Blocking edges declared. Work blockers-first.

---

## TICKET 1: Production config + quick wins
**Phase:** 0
**Blocks:** None
**Blocked by:** Nothing

**Tasks:**
- [ ] Verify/set `ENVIRONMENT = 'production'` on server
- [ ] Verify `save_queries` is FALSE in production
- [ ] Add composite index `(id_production, nama)` on `t_production_op_dl`
- [ ] Refactor `m_dpr::add()` single-row insert loops -> `insert_batch()`
- [ ] Refactor `m_operator::save_dpr()` single-row insert loops -> `insert_batch()`

**Acceptance:** Existing form still works. `EXPLAIN` on pivot query shows index usage. Batch inserts verified in query log.

---

## TICKET 2: Queue table + fast-accept API endpoint
**Phase:** 1
**Blocks:** TICKET 4 (sync engine needs endpoint to call)
**Blocked by:** Nothing (can parallel with TICKET 1)

**Tasks:**
- [ ] Create `t_sync_queue` table (DDL in spec)
- [ ] Create controller `application/controllers/api/Sync.php` with `submit()` method
- [ ] `submit()` accepts JSON POST, validates session, inserts into `t_sync_queue`, returns `{"status":"queued"}`
- [ ] Add route in `routes.php` if needed
- [ ] Test with curl/Postman: POST a payload, verify <50ms response, verify row in queue table

**Acceptance:** `POST /dpr/api/Sync/submit` with valid session + JSON payload returns 200 in <50ms and creates a `pending` row.

---

## TICKET 3: Queue drain worker
**Phase:** 1
**Blocks:** TICKET 4 (sync engine needs records to actually land in real tables)
**Blocked by:** TICKET 2 (needs queue table to exist)

**Tasks:**
- [ ] Create controller `application/controllers/c_sync.php` with `queue_drain()` method
- [ ] Select `WHERE status='pending' LIMIT 50`
- [ ] Parse JSON, run `find_duplicate_entry()`, insert into real tables using batch logic
- [ ] Set `status='done'` or `'duplicate_review'` or `'error'`
- [ ] Test: manually insert a payload into queue, hit `c_sync/queue_drain`, verify record appears in `t_production_op`
- [ ] Create Windows Task Scheduler task: hit `http://localhost/dpr/c_sync/queue_drain` every 1 minute

**Acceptance:** Pending queue records drain into `t_production_op` + child tables. Duplicates flagged as `duplicate_review`. Worker is idempotent.

---

## TICKET 4: PWA shell + Service Worker + manifest
**Phase:** 2
**Blocks:** TICKET 5, TICKET 7
**Blocked by:** Nothing

**Tasks:**
- [ ] Create `manifest.json` in web root (name, icons, theme #5bc0de, display: standalone)
- [ ] Create `sw.js` in web root (cache app shell: bootstrap CSS, jQuery, input_dpr.js, db.js)
- [ ] Create new view `application/views/online/input_dpr_pwa.php` (clone of `input_dpr.php` + manifest link + SW registration)
- [ ] Add route `login_op/input_dpr_pwa` in `login_op.php` controller
- [ ] Verify: open page, confirm SW registered in DevTools, confirm offline mode loads cached shell

**Acceptance:** App loads from cache when offline (Service Worker shown Active in DevTools). Installable on Android Chrome.

---

## TICKET 5: IndexedDB layer + offline form save
**Phase:** 3
**Blocks:** TICKET 6 (sync engine needs records in IndexedDB)
**Blocked by:** TICKET 4 (needs PWA shell)

**Tasks:**
- [ ] Create `assets/scripts/db.js` - IndexedDB wrapper (stores: `pending_records`, `reference_data`, `session`)
- [ ] Port `id_production` generation to JS (from `c_operator::add()` logic)
- [ ] In `input_dpr_pwa.php`: intercept form submit, generate id_production, save to IndexedDB
- [ ] Show instant local success + receipt data from IndexedDB
- [ ] Add "pending" badge UI showing unsynced count
- [ ] Test: fill form with WiFi off, verify record saved to IndexedDB, verify receipt shows

**Acceptance:** Form can be filled and saved 100% offline. Record appears in IndexedDB. Local receipt/confirmation shown. No server call required to save.

---

## TICKET 6: Sync engine (connect PWA to queue)
**Phase:** 4
**Blocks:** TICKET 8 (deployment needs working sync)
**Blocked by:** TICKET 3 (drain worker must be running) + TICKET 5 (records must be in IndexedDB)

**Tasks:**
- [ ] Create `assets/scripts/sync.js`
- [ ] On record save: POST to `api/Sync/submit`, on success mark "synced" in IndexedDB
- [ ] On app open (`pageshow` event): sync all pending records
- [ ] Exponential backoff retry on failure (2s, 4s, 8s, 16s, max 60s)
- [ ] Update badge: "Pending (N)" or "All synced"
- [ ] Test end-to-end: fill form offline -> turn WiFi on -> verify record syncs to queue -> verify drain worker inserts to `t_production_op` -> verify record appears in existing DPR listing

**Acceptance:** Offline record syncs to server within seconds of reconnect. Record appears in existing DPR listing (`c_dpr/dpr`). No data loss if app closed mid-sync.

---

## TICKET 7: Persistent local session + reference data cache
**Phase:** 5
**Blocks:** TICKET 8 (operators must work fully offline)
**Blocked by:** TICKET 4 (needs IndexedDB + PWA shell)

**Tasks:**
- [ ] On successful online login: cache session to IndexedDB `session` store
- [ ] On app open: if server unreachable, load session from IndexedDB (offline login)
- [ ] When server reachable: silently re-validate session
- [ ] On login: fetch kanit list, BOM, cutting tools -> store in IndexedDB `reference_data`
- [ ] Form loads reference data from IndexedDB first, refreshes from server if online
- [ ] Cutting tools autocomplete uses local cache first, AJAX fallback if online
- [ ] Test: login online once -> kill WiFi -> reopen app -> verify offline login works -> verify form fields populated from cache

**Acceptance:** After first online login, app opens offline with cached session and fully populated form dropdowns.

---

## TICKET 8: Duplicate review admin page + rollout
**Phase:** 4 + 6
**Blocks:** Nothing (final ticket)
**Blocked by:** TICKET 6 + TICKET 7

**Tasks:**
- [ ] Create `c_sync/duplicate_review` view - lists records with `status='duplicate_review'`
- [ ] Supervisor can approve (force insert) or reject (delete queue entry)
- [ ] Begin parallel deployment: PWA at `login_op/input_dpr_pwa`, old form untouched
- [ ] Shift 1: 5 operators pilot
- [ ] Gradually scale to 100 operators
- [ ] Monitor queue depth, error rate, sync latency
- [ ] After 1 week stable: redirect old URL to PWA

**Acceptance:** Duplicate flagged records reviewable by supervisor. PWA runs in parallel with old form. Gradual rollout to 100 operators with no data loss.

---

## Dependency Graph (visual)

```
TICKET 1 (quick wins) -----> [ships independently, day 1]

TICKET 2 (queue table + API) ---+
                                |
TICKET 4 (PWA shell + SW) ------+---> TICKET 3 (drain worker)
                                         |
TICKET 4 -----------------------> TICKET 5 (IndexedDB save)
                                         |
                                    TICKET 7 (auth + ref cache)
                                         |
TICKET 3 + TICKET 5 -----------> TICKET 6 (sync engine)
                                         |
                                    TICKET 7 ---+
                                         |      |
                                    TICKET 8 (rollout)
```

## Tracer Bullet (prove architecture first)

Before fleshing out every ticket, implement this minimal slice:

1. **TICKET 2** (queue table + API) - minimal version
2. **TICKET 4** (PWA shell) - minimal version
3. **TICKET 5** (IndexedDB save) - minimal form, just qty_ok + qty_ng
4. **TICKET 3** (drain worker) - minimal version
5. **TICKET 6** (sync) - minimal version

**Goal:** One operator fills a minimal form offline -> saves locally -> syncs -> drains into `t_production_op` -> appears in existing DPR listing. Proves the full architecture end-to-end before investing in all form fields, edge cases, and rollout.
