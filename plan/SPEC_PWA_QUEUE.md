# SPEC: DPR Offline-First PWA + Async Queue

**Source:** CONTEXT.md (all 8 architecture decisions)
**Status:** Draft - pending approval

## Overview

Transform the DPR production recording app into an offline-first PWA with an async write queue, so 100 operators can input production data within a 1-hour window without crashing the server.

## Phase 0: Quick Wins (Server Performance) - No architecture change

### 0.1 Production config fixes
- Set `save_queries = FALSE` in `application/config/database.php` (already conditional, verify it's off in production)
- Set `ENVIRONMENT` to `production` on the server (set `CI_ENV` env var or hardcode in `index.php`)
- Verify `db_debug` is FALSE in production

### 0.2 Batch inserts in write path
- Refactor `m_dpr::add()` and `m_operator::save_dpr()` to use `insert_batch()` instead of single-row `insert()` loops
- Affects: `t_production_op`, `t_production_op_dl`, `t_production_op_release`, `t_production_op_cutting_tools_usage`

### 0.3 Database index
- Add composite index `(id_production, nama)` on `t_production_op_dl` to speed the pivot aggregation query in `tampil_production_rev()`

**Risk:** Low. No behavior change, only performance improvement.
**Rollback:** Revert config values, drop index.

---

## Phase 1: Async Queue Infrastructure (Server-side)

### 1.1 Queue table
- Create `t_sync_queue` table:
  ```sql
  CREATE TABLE t_sync_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_production VARCHAR(50) NOT NULL,
    payload JSON NOT NULL,
    status ENUM('pending', 'processing', 'done', 'error', 'duplicate_review') DEFAULT 'pending',
    error_message TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    processed_at DATETIME NULL,
    UNIQUE KEY uk_id_production (id_production)
  );
  ```

### 1.2 Fast-accept API endpoint
- New controller: `api/Sync` with method `submit()`
- Accepts POST with JSON payload (all form data: user, detail, detailLT, ress, cutting_tools)
- Validates session (operator must be logged in)
- Inserts payload into `t_sync_queue` as-is (no business validation, no joins)
- Returns `{"status":"queued","id_production":"..."}` in <50ms
- No duplicate check here (deferred to drain worker)

### 1.3 Queue drain worker
- New controller: `c_sync/queue_drain()`
- Selects `WHERE status = 'pending' ORDER BY created_at LIMIT 50`
- For each record:
  1. Parse JSON payload
  2. Run `find_duplicate_entry()` check -> if duplicate, set `status = 'duplicate_review'`
  3. Insert into real tables (`t_production_op`, `t_production_op_dl`, `t_production_op_release`, cutting tools) using batch inserts
  4. Set `status = 'done'`
  5. On error: set `status = 'error'`, log `error_message`
- Idempotent: `UNIQUE KEY uk_id_production` prevents reprocessing

### 1.4 Windows Service / Task Scheduler
- Register Windows Task Scheduler to hit `http://localhost/dpr/c_sync/queue_drain` every 1 minute
- OR register a Windows Service with a PHP loop polling every 10 seconds
- Follows existing `c_scheduler.php` pattern

**Risk:** Medium. New table + endpoint, but existing form continues to work independently.
**Rollback:** Stop the Task/Service, old form unaffected.

---

## Phase 2: PWA Foundation (App Shell Caching)

### 2.1 Web App Manifest
- Create `manifest.json` in web root
- App name, icons, theme color, display: standalone
- Link from `online/input_dpr_pwa.php` (new view, parallel to existing)

### 2.2 Service Worker
- Create `sw.js` in web root
- **Install:** cache app shell (HTML, CSS, JS, fonts)
- **Activate:** clean old caches
- **Fetch:** cache-first for static assets, network-first for API calls (with offline fallback)
- Register from the PWA view's HTML

### 2.3 New PWA views (parallel deployment)
- `online/input_dpr_pwa.php` - the offline-capable input form (clone of `input_dpr.php` with PWA hooks)
- `online/receipt_pwa.php` - local confirmation page (renders from IndexedDB data)
- Route: `login_op/input_dpr_pwa`

**Risk:** Zero impact on existing app. New URL, no changes to existing routes.
**Rollback:** Delete new files, remove Service Worker registration.

---

## Phase 3: Offline Input (IndexedDB Layer)

### 3.1 IndexedDB wrapper
- Create `assets/scripts/db.js` - thin wrapper over IndexedDB
- Store: `pending_records` (keyPath: `id_production`, autoIncrement: false)
- Store: `reference_data` (keyPath: `key`) - cached kanit list, BOM, cutting tools
- Store: `session` (keyPath: `key`) - cached operator session

### 3.2 Client-side id_production generation
- Port the server logic from `c_operator::add()` to JavaScript:
  - `ambil_tahun = tanggal.substr(2,2)`, `bulan`, `tanggal`
  - `waktu = date('His') + counter`
  - `id_production = tahun + tanggal + bulan + waktu + id_bom`
- Ensure uniqueness via local counter + timestamp

### 3.3 Form submission to IndexedDB
- Intercept form submit in `input_dpr_pwa.php`
- Generate `id_production` client-side
- Save complete record (user + detail + detailLT + ress + cutting_tools) to IndexedDB
- Show instant local success + receipt page
- Display "pending" badge on saved records

**Risk:** Low. Pure frontend, no server changes.
**Rollback:** Revert JS changes.

---

## Phase 4: Sync Engine (Connect PWA to Queue)

### 4.1 Sync logic
- Create `assets/scripts/sync.js`
- On record save: attempt immediate sync (POST to `api/Sync/submit`)
- On app open: sync all pending records from IndexedDB
- On success (HTTP 200 from queue): mark record as "synced" in IndexedDB
- On failure: retry with exponential backoff (2s, 4s, 8s, 16s, max 60s)
- Show sync status badge: "Pending (3)" or "All synced"

### 4.2 iOS compatibility
- No Background Sync API on iOS Safari
- Fallback: sync triggered on `pageshow` event (app foregrounded) and on form submit
- If app is closed, sync resumes next time app opens

### 4.3 Duplicate review handling
- Queue drain worker sets `status = 'duplicate_review'` for duplicates
- New admin view: `c_sync/duplicate_review` shows flagged records
- Supervisor can approve (force insert) or reject (delete queue entry) the next day

**Risk:** Medium. Connects frontend to new backend endpoint.
**Rollback:** Disable sync.js, records stay in IndexedDB (manually drainable).

---

## Phase 5: Authentication & Reference Data Caching

### 5.1 Persistent local session
- On successful online login: cache `id_operator`, `nik`, `nama_operator`, `jabatan`, `divisi`, `nama_kanit` to IndexedDB `session` store
- On app open: if server unreachable, load session from IndexedDB (offline login)
- When server reachable: silently validate/refresh session

### 5.2 Reference data cache
- On login: fetch kanit list, BOM list, cutting tools -> store in IndexedDB `reference_data`
- Form loads reference data from IndexedDB first (instant), then refreshes from server if reachable
- Autocomplete (cutting tools) uses local cache, falls back to AJAX if online

**Risk:** Low-medium. Security consideration: cached credentials on personal phones.
**Rollback:** Clear IndexedDB session store, revert to server-only auth.

---

## Phase 6: Deployment & Rollout

### 6.1 Parallel deployment
- Existing form stays at `login_op/input_dpr` (untouched)
- PWA at `login_op/input_dpr_pwa`
- Both drain to same `t_production_op` tables

### 6.2 Gradual migration
- Shift 1: 5 tech-savvy operators use PWA, monitor queue + error rates
- Shift 2-3: 20 operators
- Shift 4-5: 50 operators
- Shift 6+: all 100 operators

### 6.3 Cutover
- Once stable for 1 week with all operators: redirect `login_op/input_dpr` to PWA
- Keep old form accessible at backup URL for emergency fallback

---

## Dependency Graph

```
Phase 0 (Quick Wins) ---------> independent, ship first
Phase 1 (Queue Infrastructure) -> independent, can parallel with Phase 0
Phase 2 (PWA Foundation) ------> depends on nothing
Phase 3 (Offline Input) -------> depends on Phase 2
Phase 4 (Sync Engine) ---------> depends on Phase 1 + Phase 3
Phase 5 (Auth Caching) --------> depends on Phase 2
Phase 6 (Deployment) ----------> depends on Phase 4 + Phase 5
```

## Tracer Bullet Strategy

The critical path is: **Phase 1 (queue) + Phase 2 (PWA shell) + Phase 3 (offline input) + Phase 4 (sync)**. A tracer bullet would implement a minimal end-to-end slice through all four phases:

> One operator fills a minimal form offline -> saves to IndexedDB -> syncs to queue table -> drain worker inserts into `t_production_op` -> record appears in existing DPR listing.

This proves the architecture works before fleshing out all fields, edge cases, and the full rollout.
