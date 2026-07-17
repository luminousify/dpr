/*
 * sw.js - DPR PWA Service Worker (Ticket 4: app shell caching)
 *
 * Scope: /dpr/ (this file lives at the web root of the app).
 *
 * Strategy:
 *   - Install:  precache the static app shell (CSS/JS/icons/manifest).
 *   - Activate: delete old cache versions, claim open clients.
 *   - Fetch:    network-first for navigations + API reads (with cache fallback),
 *               cache-first for same-origin static assets (runtime-populated).
 *
 * Tickets 5 (IndexedDB), 6 (sync engine) and 7 (auth cache) will extend this.
 * iOS Safari has no Background Sync API (CONTEXT.md Decision 2), so sync is
 * triggered from the page (pageshow / form submit), not by this worker.
 */

const CACHE_VERSION = 'dpr-pwa-v3';

// Static assets guaranteed to be served 200 from /dpr/. Paths are relative to
// sw.js (i.e. relative to /dpr/). The dynamic PWA page and precache-unfriendly
// URLs are intentionally excluded; they are runtime-cached on first success.
const APP_SHELL = [
  './assets/css/bootstrap.min.css',
  './assets/css/bootstrap-icons.css',
  './assets/css/fonts/bootstrap-icons.woff',
  './assets/css/fonts/bootstrap-icons.woff2',
  './assets/css/jquery-ui.css',
  './assets/css/select2.min.css',
  './assets/js/jquery-3.3.1.js',
  './assets/js/bootstrap.js',
  './assets/js/jquery-ui.js',
  './assets/scripts/select2.min.js',
  './assets/scripts/input_dpr.js',
  './assets/scripts/db.js',
  './assets/images/icons/icon-192.png',
  './assets/images/icons/icon-512.png',
  './manifest.json',
  './offline.html'
];

// Bump CACHE_VERSION to invalidate every cached entry on deploy.
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_VERSION)
      .then(async (cache) => {
        // allSettled so a single 404/redirect never aborts the whole install;
        // the runtime fetch handler backfills anything that missed precache.
        await Promise.allSettled(APP_SHELL.map((url) => cache.add(url)));
      })
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(
        keys.filter((k) => k !== CACHE_VERSION).map((k) => caches.delete(k))
      ))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const req = event.request;

  // Only GET is cacheable; POST (e.g. form submit, api/Sync/submit) passes through.
  if (req.method !== 'GET') {
    return;
  }

  const url = new URL(req.url);

  // Cross-origin (e.g. a future CDN) is left to the browser; only same-origin here.
  if (url.origin !== self.location.origin) {
    return;
  }

  // API reads: network-first, fall back to cache so the UI degrades gracefully offline.
  // Guard against caches.match resolving to undefined (would throw inside respondWith).
  if (url.pathname.indexOf('/api/') !== -1) {
    event.respondWith(
      fetch(req).catch(async () => (await caches.match(req)) || Response.error())
    );
    return;
  }

  // Navigations (HTML pages): network-first so logged-in operators see fresh HTML,
  // but cache the successful response and serve it offline. Ticket 7 will make the
  // cached shell usable without a live session.
  if (req.mode === 'navigate') {
    event.respondWith((async () => {
      // 1. Try network; on success, refresh the cached navigation for next time.
      try {
        const resp = await fetch(req);
        if (resp && resp.ok) {
          const copy = resp.clone();
          const cache = await caches.open(CACHE_VERSION);
          await cache.put(req, copy);
        }
        return resp;
      } catch (err) {
        // 2. Offline (or server down). Serve the exact cached navigation if we have one.
        const cached = await caches.match(req);
        if (cached) return cached;
        // 3. First-ever offline visit before any SW-intercepted online load cached
        //    the page. Fall back to the precached static offline page rather than
        //    crashing the browser with "site can't be reached".
        const fallback = await caches.match('./offline.html');
        if (fallback) return fallback;
        // 4. Last resort: synthetic response so respondWith never gets undefined.
        return new Response(
          '<h1>Offline</h1><p>DPR PWA is offline. Reconnect to load the app.</p>',
          { status: 503, statusText: 'Offline', headers: { 'Content-Type': 'text/html; charset=utf-8' } }
        );
      }
    })());
    return;
  }

  // Static assets: cache-first, then network with runtime caching of the result.
  event.respondWith(
    caches.match(req).then((cached) => {
      if (cached) {
        return cached;
      }
      return fetch(req).then((resp) => {
        if (resp && resp.ok) {
          const copy = resp.clone();
          caches.open(CACHE_VERSION).then((c) => c.put(req, copy));
        }
        return resp;
      });
    })
  );
});
