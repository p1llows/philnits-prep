const CACHE_NAME = 'paxi-v2';
const urlsToCache = [
    '/favicon.svg',
    '/favicon-32.png',
    '/icon-192.png',
    '/icon-512.png',
    '/icon-maskable-512.png',
    '/paxi-mark.svg',
    '/paxi-lockup.svg',
    '/manifest.json',
    '/images/email-logo.png',
    '/mascot/paxi.svg',
    '/mascot/paxi-think.svg',
    '/mascot/paxi-cheer.svg',
    '/mascot/paxi-sleepy.svg',
    '/mascot/paxi-timer.svg',
    '/mascot/paxi-inspect.svg',
    '/mascot/paxi-passed.svg',
    '/mascot/paxi-oops.svg',
    '/mascot/paxi-avatar.svg'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(urlsToCache);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;
    
    // Network-first strategy for dynamic pages and Vite build assets
    event.respondWith(
        fetch(event.request)
            .then((networkResponse) => {
                return networkResponse;
            })
            .catch(() => {
                return caches.match(event.request);
            })
    );
});
