/*
 * reference-data.js - DPR PWA reference-data cache (Ticket 7 / Phase 5.2)
 *
 * Caches the kanit list, BOM list, and cutting-tools list to the IndexedDB
 * `reference_data` store on app open (when online), then populates the form
 * dropdowns from the cache so the PWA form is fully usable offline
 * (CONTEXT.md Decision 3, SPEC 5.2).
 *
 * Cache-first autocomplete strategy (SPEC 5.2): the BOM and cutting-tools
 * jQuery UI autocomplete widgets (bound by input_dpr.js) are rewired here
 * to filter the IndexedDB cache first, falling back to the existing AJAX
 * endpoints only when the cache has no match AND the browser is online.
 * input_dpr.js itself is NOT modified (legacy form at login_op/input_dpr
 * stays intact - CONTEXT.md Decision 8).
 *
 * Loaded via a plain <script> tag in input_dpr_pwa.php AFTER db.js (it
 * depends on DPRDb) and BEFORE sync.js. Exposes the `DPRRef` global.
 * Conventions match db.js / sync.js / auth.js: IIFE, 2-space indent,
 * function declarations, no ES module syntax, Promise-based.
 *
 * reference_data store shape (keyPath: 'key'):
 *   { key: 'kanit',         value: ['name1', ...],  cached_at: ISOString }
 *   { key: 'bom',           value: [{id_bom, ...}], cached_at: ISOString }
 *   { key: 'cutting_tools', value: [{id, code, ...}], cached_at: ISOString }
 * The value arrays are produced server-side by Pwa_reference_normalizer,
 * so their shape matches what the existing autocomplete widgets expect.
 */
var DPRRef = (function () {

  var KEY_KANIT = 'kanit';
  var KEY_BOM = 'bom';
  var KEY_CUTTING = 'cutting_tools';

  var REF_ENDPOINT = 'login_op/pwa_reference_data';
  var BOM_AJAX_URL = 'c_operator/get_autocomplete';
  var CUTTING_AJAX_URL_PART = 'c_dpr/get_autocomplete_cutting_tools';

  // Cap client-side filter results so the dropdown stays snappy even if the
  // cached BOM list is large (hundreds of entries).
  var MAX_FILTER_RESULTS = 20;

  // Guard so the autocomplete rewiring runs at most once per page load.
  var _rewired = false;

  // ---- internals -----------------------------------------------------

  function _baseUrl() {
    return (typeof BASE_URL !== 'undefined') ? BASE_URL : '/';
  }

  function _siteUrl() {
    return (typeof SITE_URL !== 'undefined') ? SITE_URL : _baseUrl();
  }

  function _nowOnline() {
    return (typeof navigator !== 'undefined') ? navigator.onLine : true;
  }

  function _stamp(value) {
    return {
      key: '',            // set by caller
      value: value,
      cached_at: new Date().toISOString()
    };
  }

  function _put(key, value) {
    if (typeof DPRDb === 'undefined') {
      return Promise.reject(new Error('DPRRef: DPRDb unavailable'));
    }
    var record = _stamp(value);
    record.key = key;
    return DPRDb.putKey(DPRDb.STORE_REFERENCE, record);
  }

  function _get(key) {
    if (typeof DPRDb === 'undefined') {
      return Promise.resolve(null);
    }
    return DPRDb.getKey(DPRDb.STORE_REFERENCE, key).then(function (rec) {
      return (rec && rec.value) ? rec.value : null;
    });
  }

  // ---- public read API ----------------------------------------------

  function getKanit() {
    return _get(KEY_KANIT).then(function (v) { return v || []; });
  }

  function getBom() {
    return _get(KEY_BOM).then(function (v) { return v || []; });
  }

  function getCuttingTools() {
    return _get(KEY_CUTTING).then(function (v) { return v || []; });
  }

  // ---- cache write ---------------------------------------------------

  // Fetch {kanit, bom, cutting_tools} from the server and store all three
  // in IndexedDB. Resolves to true on success, false on failure (network
  // error, auth expired, non-JSON). Safe to call repeatedly.
  function cacheAll() {
    return new Promise(function (resolve) {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', _baseUrl() + REF_ENDPOINT, true);
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.timeout = 20000;
      xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
          return;
        }
        if (xhr.status !== 200) {
          resolve(false);
          return;
        }
        var parsed;
        try {
          parsed = JSON.parse(xhr.responseText || '{}');
        } catch (e) {
          resolve(false);
          return;
        }
        var tasks = [];
        if (parsed && parsed.kanit) {
          tasks.push(_put(KEY_KANIT, parsed.kanit));
        }
        if (parsed && parsed.bom) {
          tasks.push(_put(KEY_BOM, parsed.bom));
        }
        if (parsed && parsed.cutting_tools) {
          tasks.push(_put(KEY_CUTTING, parsed.cutting_tools));
        }
        Promise.all(tasks).then(function () {
          resolve(true);
        }, function () {
          resolve(false);
        });
      };
      xhr.onerror = function () { resolve(false); };
      xhr.ontimeout = function () { resolve(false); };
      xhr.send();
    });
  }

  // ---- form population ----------------------------------------------

  // Rebuild the #kanit <select> from the IndexedDB cache so the dropdown
  // is correct even when the SW served a stale cached HTML shell. Only
  // runs if the cache has data; otherwise the server-rendered options
  // (baked into the HTML) are left alone. Preserves the current selection.
  function _populateKanit() {
    return getKanit().then(function (names) {
      if (!names || names.length === 0) {
        return;
      }
      var $kanit = $('#kanit');
      if (!$kanit.length) {
        return;
      }
      var current = $kanit.val() || '';
      $kanit.empty();
      $kanit.append($('<option></option>').attr('value', '').text('-Choose-'));
      var seen = {};
      // Ensure the operator's own kanit (from the server-rendered first
      // option) is present even if the cache list is sorted differently.
      if (current && !seen[current]) {
        $kanit.append($('<option></option>').attr('value', current).text(current));
        seen[current] = true;
      }
      for (var i = 0; i < names.length; i++) {
        var n = names[i];
        if (n && !seen[n]) {
          $kanit.append($('<option></option>').attr('value', n).text(n));
          seen[n] = true;
        }
      }
      if (current) {
        $kanit.val(current);
      }
      // Refresh select2 if it's initialized on this element.
      if ($kanit.hasClass('select2-hidden-accessible') || $kanit.data('select2')) {
        $kanit.trigger('change.select2');
      }
    });
  }

  // Rewire the BOM autocomplete (bound by input_dpr.js) to filter the
  // IndexedDB cache first, falling back to the original AJAX endpoint only
  // when the cache has no matches AND the browser is online. The select
  // callback (which sets #id_bomS, #ct_mc, etc.) is left untouched - the
  // cached items carry the same property names the online response does.
  function _rewireBomAutocomplete() {
    var $bom = $('.autocompleteBom');
    if (!$bom.length || !$bom.data('ui-autocomplete')) {
      return;
    }
    $bom.autocomplete('option', 'source', function (request, response) {
      var term = (request.term || '').toLowerCase();
      getBom().then(function (items) {
        var filtered = [];
        if (items && items.length) {
          for (var i = 0; i < items.length && filtered.length < MAX_FILTER_RESULTS; i++) {
            var it = items[i];
            var id = (it.id_bom || '').toLowerCase();
            var nama = (it.nama_bom || '').toLowerCase();
            if (!term || id.indexOf(term) !== -1 || nama.indexOf(term) !== -1) {
              filtered.push(it);
            }
          }
        }
        if (filtered.length > 0) {
          response(filtered);
          return;
        }
        // Cache miss: fall back to AJAX if online, else empty.
        if (_nowOnline()) {
          $.ajax({
            url: _baseUrl() + BOM_AJAX_URL,
            data: { term: request.term },
            dataType: 'json',
            success: function (data) { response(data || []); },
            error: function () { response([]); }
          });
        } else {
          response([]);
        }
      }).catch(function () { response([]); });
    });
  }

  // Rewire the cutting-tools autocomplete (bound by input_dpr.js) with the
  // same cache-first strategy as BOM.
  function _rewireCuttingToolsAutocomplete() {
    var $ct = $('.autocompletecuttingtools');
    if (!$ct.length || !$ct.data('ui-autocomplete')) {
      return;
    }
    $ct.autocomplete('option', 'source', function (request, response) {
      var term = (request.term || '').toLowerCase();
      getCuttingTools().then(function (items) {
        var filtered = [];
        if (items && items.length) {
          for (var i = 0; i < items.length && filtered.length < MAX_FILTER_RESULTS; i++) {
            var it = items[i];
            var code = (it.code || '').toLowerCase();
            if (!term || code.indexOf(term) !== -1) {
              filtered.push(it);
            }
          }
        }
        if (filtered.length > 0) {
          response(filtered);
          return;
        }
        if (_nowOnline()) {
          $.ajax({
            url: _siteUrl() + CUTTING_AJAX_URL_PART,
            dataType: 'json',
            data: { term: request.term },
            success: function (data) {
              response($.map(data || [], function (item) {
                return { label: item.label, value: item.value, id: item.id };
              }));
            },
            error: function () { response([]); }
          });
        } else {
          response([]);
        }
      }).catch(function () { response([]); });
    });
  }

  // Populate form widgets from the IndexedDB cache. Called on load (from
  // cache, instant) and again after cacheAll() succeeds (refreshed).
  function populateForm() {
    if (typeof DPRDb === 'undefined') {
      return Promise.resolve();
    }
    return _populateKanit().then(function () {
      if (!_rewired) {
        _rewireBomAutocomplete();
        _rewireCuttingToolsAutocomplete();
        _rewired = true;
      }
    });
  }

  // ---- init ----------------------------------------------------------

  // Populate the form from cache immediately (instant, works offline),
  // then refresh from the server if online. Call once on DOMReady.
  function init() {
    if (typeof DPRDb === 'undefined') {
      console.error('[PWA] DPRDb unavailable - reference cache disabled');
      return;
    }
    // 1. Instant: populate from whatever is already in the cache.
    populateForm();
    // 2. If online, refresh the cache and re-populate so the operator sees
    //    the latest kanit/BOM/tools (e.g. a newly added BOM). Fire-and-forget.
    if (_nowOnline()) {
      cacheAll().then(function (ok) {
        if (ok) {
          populateForm();
        }
      });
    }
  }

  return {
    cacheAll: cacheAll,
    getKanit: getKanit,
    getBom: getBom,
    getCuttingTools: getCuttingTools,
    populateForm: populateForm,
    init: init
  };
})();
