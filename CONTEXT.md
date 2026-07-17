# CONTEXT.md - DPR Production Recording PWA Project

## Problem Statement

Manufacturing injection-molding production recording app (DPR) has three symptoms:
1. **Server dies** = app completely unreachable
2. **Loading too slow** during the critical 1-hour input window
3. **End-of-shift input bottleneck** - all operators input in the same 30-minute window

## Root Cause Analysis

The root cause is structural and unavoidable:
- **Company policy:** operators' personal phones are locked away during the shift and released only **1 hour before shift ends**. All ~100 operators must input their production data within this 1-hour window.
- This creates a guaranteed write burst of ~100 concurrent complex writes against a single remote MySQL server.
- The server cannot handle the burst, leading to slow responses, timeouts, and eventual crash.
- Operators cannot change behavior (input earlier) because they physically don't have their phones.

## Architecture Decisions (ADR-style)

### Decision 1: PWA Offline-First is the centerpiece
**Status:** Agreed

The app will become a Progressive Web App so that:
- The app shell (HTML/CSS/JS) is cached by a Service Worker for instant loading.
- Production records are saved to IndexedDB locally (instant, <100ms) and synced to the server in the background.
- The operator never waits for the server during input.

### Decision 2: Target devices are personal phones (Android + iPhone mix)
**Status:** Agreed

- Android: full PWA support including Background Sync.
- iPhone (iOS Safari): no Background Sync. Sync strategy must work without it.
- **Sync strategy:** save locally immediately -> attempt sync immediately when online (not deferred to background sync). Retry with exponential backoff if offline. Show "pending" badge until confirmed. Sync is also triggered on every app open.
- **Data-at-risk window:** minimized to the duration of WiFi outage (seconds to minutes), not the entire shift.

### Decision 3: Login uses persistent local session
**Status:** Agreed

- After first successful online login, session token + user identity cached in IndexedDB/localStorage.
- On subsequent app opens, if server is unreachable, cached session is used (offline login).
- Safe because these are personal phones, not shared kiosks.
- Session is silently validated/refreshed whenever server is reachable.
- Reference data (kanit list, cutting tools, BOM list, operator data) is cached on login and refreshed when server is reachable. Form is fully functional offline.

### Decision 4: Input is one-way with local confirmation
**Status:** Agreed (decided from code analysis)

- The current form (`c_operator/add` -> `hasil_input`) does all calculations client-side (`input_dpr.js`). The server only stores data.
- The only server-generated value is `id_production` (timestamp + bom_id format), which can be generated client-side using the same logic.
- PWA flow: operator fills form -> saves to IndexedDB -> sees instant local confirmation ("Saved - syncing") -> receipt page renders from local data -> syncs in background.
- Operator does not need server feedback before leaving.

### Decision 5: Duplicates allowed offline, resolved during sync
**Status:** Agreed

- Duplicates are rare (each operator handles different machine/product) but possible.
- Operator can submit offline freely without duplicate blocking.
- Duplicate check (`find_duplicate_entry`: tanggal + mesin + shift + id_bom) runs during sync as post-validation.
- If sync detects a duplicate, record is flagged (status = "duplicate_review") for supervisor to handle next day, not the operator.

### Decision 6: Async write queue using MySQL table + Windows worker
**Status:** Agreed

- **Queue table:** new `t_sync_queue` table in `dpr_cku` database stores JSON payloads from PWA sync.
- **Fast accept endpoint:** lightweight controller method inserts JSON into queue table (no validation, no joins, <50ms response). Returns 200 immediately.
- **Worker:** Windows Service or Task Scheduler runs a CodeIgniter controller method (`c_sync/queue_drain`) on a loop (every 10 seconds if Service, every 1 minute if Task Scheduler). Drains queue into real tables (`t_production_op`, `t_production_op_dl`, `t_production_op_release`, cutting tools) at a controlled rate.
- **Drain rate:** ~2 records/second = 7,200/hour (well above 100 operators).
- **Idempotency:** worker must be idempotent (retried sync doesn't double-insert). Uses `id_production` as unique key.
- **Pattern:** follows existing `c_scheduler.php` pattern (CI controller triggered externally).
- **Server access:** confirmed - full admin access to create tables and register Windows Task/Service.

### Decision 7: All three recommendations needed (#1, #3, #5)
**Status:** Agreed

Given 100 operators in a 1-hour burst:
- **#1 (Quick wins):** config fixes + DB indexes + batch inserts. Same-day impact on loading speed.
- **#3 (PWA):** the centerpiece. Makes each operator's input fast and independent of server health.
- **#5 (Async queue):** essential, not optional. The burst is guaranteed. Queue absorbs it, server drains gradually.

### Decision 8: Parallel/soft launch deployment
**Status:** Agreed

- Existing form stays live at current URL as fallback.
- PWA deployed at new URL (e.g., `login_op/input_dpr_pwa`).
- Both old form and PWA drain into same `t_production_op` tables (no data fragmentation).
- Gradual migration: small group first -> more operators -> full rollout.
- Once stable, old URL redirects to PWA.

## Current Tech Stack

- **Backend:** CodeIgniter 3 (PHP), single remote MySQL server (`36.92.174.141:3333`, db `dpr_cku`)
- **Frontend:** jQuery, Bootstrap, DataTables, Highcharts, select2
- **Server OS:** Windows (Windows Services + Task Scheduler for scheduled tasks)
- **Input flow:** operator login (`login_op`) -> input form (`online/input_dpr.php`) -> client-side calc (`input_dpr.js`) -> POST to `c_operator/add` -> server-side insert -> redirect to `hasil_input` confirmation page
- **Tables involved in write:** `t_production_op`, `t_production_op_dl`, `t_production_op_release`, `t_production_op_cutting_tools_usage`
- **Existing scheduler pattern:** `c_scheduler.php` triggered externally by Windows Task/Service

## Known Quick Wins (#1)

- `save_queries = TRUE` in production config -> set to `FALSE` (memory/performance)
- `ENVIRONMENT = 'development'` -> set to `production` on server
- `add()` in model uses single-row inserts in loops -> convert to `insert_batch()`
- `find_duplicate_entry()` runs per-row N+1 -> could be batched
- Add composite index `(id_production, nama)` on `t_production_op_dl` for pivot query
