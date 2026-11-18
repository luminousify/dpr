/*
 * db.js - DPR PWA IndexedDB wrapper (Ticket 5 / Phase 3.1)
 *
 * Thin, promise-based wrapper over IndexedDB. Loaded via a plain <script>
 * tag in input_dpr_pwa.php (no module loader), so it exposes a single
 * global namespace `DPRDb`. Conventions match input_dpr.js: 2-space indent,
 * function declarations, no ES module syntax.
 *
 * Three object stores (created once, up front - cheap to create all three
 * now even though Ticket 5 only uses pending_records; reference_data and
 * session are reserved for Tickets 6/7 per SPEC_PWA_QUEUE.md section 3.1):
 *
 *   pending_records  keyPath: id_production   (no autoIncrement)
 *     -> one production record saved offline, awaiting sync.
 *        Record shape stored by the submit-intercept in input_dpr_pwa.php:
 *          {
 *            id_production: "...",      // unique key
 *            status: "pending",         // "pending" | "synced" | "error"
 *            created_at: ISOString,
 *            synced_at: ISOString?,     // set by Ticket 6 after a 200
 *            form: { ... }              // nested object mirroring legacy POST
 *          }
 *
 *        CONTRACT for downstream tickets (read this before touching the shape):
 *        Ticket 6 POSTs `record.form` (NOT the whole record) as the JSON body
 *        to api/Sync/submit. record.form already carries a top-level
 *        `id_production` (the form field <input name="id_production"> is
 *        serialized into it), which is what Sync_payload_helper requires.
 *        status / created_at / synced_at are PWA-local metadata and are NOT
 *        sent to the server. Ticket 3's drain worker parses that same
 *        record.form payload when inserting into t_production_op + children.
 *
 *   reference_data   keyPath: key          (Ticket 7: cached kanit/BOM/tools)
 *   session          keyPath: key          (Ticket 7: cached operator session)
 *
 * iOS note: IndexedDB works on iOS Safari. Only the Background Sync API is
 * missing - and this layer never relies on it (save is in-page, synchronous
 * from the submit handler). See CONTEXT.md Decision 2.
 */
var DPRDb = (function () {

  var DB_NAME = 'dpr_pwa';
  var DB_VERSION = 1;

  var STORE_PENDING = 'pending_records';
  var STORE_REFERENCE = 'reference_data';
  var STORE_SESSION = 'session';

  // Cached open() promise so repeated callers share one connection.
  var _dbPromise = null;

  // Open (and on first run, create) the database. Resolves to the IDBDatabase.
  // Rejects if IndexedDB is unavailable (e.g. very old browser) or the open
  // request errors (e.g. blocked by a higher-version open in another tab).
  function open() {
    if (_dbPromise) {
      return _dbPromise;
    }
    _dbPromise = new Promise(function (resolve, reject) {
      if (!('indexedDB' in window)) {
        reject(new Error('IndexedDB not supported in this browser'));
        return;
      }
      var request = indexedDB.open(DB_NAME, DB_VERSION);

      request.onupgradeneeded = function (event) {
        var db = event.target.result;
        if (!db.objectStoreNames.contains(STORE_PENDING)) {
          // keyPath id_production, no autoIncrement: the client generates the
          // id (see uniqueIdProduction in input_dpr_pwa.php). This is the
          // same key the queue table uses (uk_id_production), so a record
          // flows PWA -> IndexedDB -> queue without rekeying.
          db.createObjectStore(STORE_PENDING, { keyPath: 'id_production' });
        }
        if (!db.objectStoreNames.contains(STORE_REFERENCE)) {
          db.createObjectStore(STORE_REFERENCE, { keyPath: 'key' });
        }
        if (!db.objectStoreNames.contains(STORE_SESSION)) {
          db.createObjectStore(STORE_SESSION, { keyPath: 'key' });
        }
      };

      request.onsuccess = function (event) {
        resolve(event.target.result);
      };
      request.onerror = function (event) {
        reject(event.target.error);
      };
      // If another tab holds an older version and blocks the upgrade, the
      // client cannot proceed; surface the error so callers can degrade.
      request.onblocked = function () {
        reject(new Error('IndexedDB upgrade blocked by another tab'));
      };
    });
    return _dbPromise;
  }

  // Internal: obtain an object store handle wrapped in a Promise.
  function _store(storeName, mode) {
    return open().then(function (db) {
      // Use a fresh transaction each call. Transactions auto-commit when the
      // task queue drains, which suits our one-shot put/get pattern.
      return db.transaction(storeName, mode).objectStore(storeName);
    });
  }

  // Upsert a pending record. Resolves to the id_production on success.
  // Using put() (not add()) so a re-save with the same id overwrites cleanly.
  function putPending(record) {
    return _store(STORE_PENDING, 'readwrite').then(function (store) {
      return new Promise(function (resolve, reject) {
        var req = store.put(record);
        req.onsuccess = function () {
          resolve(req.result);
        };
        req.onerror = function () {
          reject(req.error);
        };
      });
    });
  }

  // Read one pending record by id_production. Resolves to the record or
  // undefined if not present.
  function getPending(id) {
    return _store(STORE_PENDING, 'readonly').then(function (store) {
      return new Promise(function (resolve, reject) {
        var req = store.get(id);
        req.onsuccess = function () {
          resolve(req.result);
        };
        req.onerror = function () {
          reject(req.error);
        };
      });
    });
  }

  // Return all pending records. Resolves to an array (never null/undefined).
  // Ticket 6 reads this to find records needing sync.
  function getAllPending() {
    return _store(STORE_PENDING, 'readonly').then(function (store) {
      return new Promise(function (resolve, reject) {
        var req = store.getAll();
        req.onsuccess = function () {
          resolve(req.result || []);
        };
        req.onerror = function () {
          reject(req.error);
        };
      });
    });
  }

  // Count of records not yet synced (status !== 'synced'). Drives the
  // "Pending: N" badge. getAll()+filter is fine here: the working set is at
  // most ~100 records per device per shift, far below where an index on
  // status would matter. Kept simple deliberately.
  function countPending() {
    return getAllPending().then(function (rows) {
      var n = 0;
      for (var i = 0; i < rows.length; i++) {
        if (!rows[i] || rows[i].status !== 'synced') {
          n++;
        }
      }
      return n;
    });
  }

  // Patch a pending record's status (and optional extra fields, e.g.
  // synced_at / error_message). Used by Ticket 6 after a sync attempt.
  // Resolves to the updated record, or null if the id is unknown.
  function updatePendingStatus(id, status, extra) {
    return getPending(id).then(function (rec) {
      if (!rec) {
        return null;
      }
      rec.status = status;
      if (extra) {
        var keys = Object.keys(extra);
        for (var i = 0; i < keys.length; i++) {
          rec[keys[i]] = extra[keys[i]];
        }
      }
      return putPending(rec);
    });
  }

  // Delete a pending record by id_production. Ticket 6 may use this to drop
  // a confirmed-synced record (alternative to marking status='synced').
  function deletePending(id) {
    return _store(STORE_PENDING, 'readwrite').then(function (store) {
      return new Promise(function (resolve, reject) {
        var req = store.delete(id);
        req.onsuccess = function () {
          resolve(true);
        };
        req.onerror = function () {
          reject(req.error);
        };
      });
    });
  }

  // ---- Ticket 7: generic helpers for reference_data + session stores ----
  // These two stores use a synthetic `key` keyPath (e.g. {key:'kanit', ...}),
  // so the pending_records-specific helpers above don't apply. The four
  // helpers below are store-agnostic and use the same _store() internal.

  // Upsert one keyed record into any store. Resolves to the key on success.
  function putKey(storeName, record) {
    return _store(storeName, 'readwrite').then(function (store) {
      return new Promise(function (resolve, reject) {
        var req = store.put(record);
        req.onsuccess = function () {
          resolve(req.result);
        };
        req.onerror = function () {
          reject(req.error);
        };
      });
    });
  }

  // Read one keyed record from any store by its key. Resolves to the record
  // or undefined if not present.
  function getKey(storeName, key) {
    return _store(storeName, 'readonly').then(function (store) {
      return new Promise(function (resolve, reject) {
        var req = store.get(key);
        req.onsuccess = function () {
          resolve(req.result);
        };
        req.onerror = function () {
          reject(req.error);
        };
      });
    });
  }

  // Delete one keyed record from any store by its key. Resolves to true.
  function deleteKey(storeName, key) {
    return _store(storeName, 'readwrite').then(function (store) {
      return new Promise(function (resolve, reject) {
        var req = store.delete(key);
        req.onsuccess = function () {
          resolve(true);
        };
        req.onerror = function () {
          reject(req.error);
        };
      });
    });
  }

  return {
    open: open,
    putPending: putPending,
    getPending: getPending,
    getAllPending: getAllPending,
    countPending: countPending,
    updatePendingStatus: updatePendingStatus,
    deletePending: deletePending,
    // Ticket 7 generic keyed-store helpers.
    putKey: putKey,
    getKey: getKey,
    deleteKey: deleteKey,
    STORE_PENDING: STORE_PENDING,
    STORE_REFERENCE: STORE_REFERENCE,
    STORE_SESSION: STORE_SESSION
  };
})();
