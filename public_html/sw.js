const CACHE_NAME = 'cronotimer-v1';
const ASSETS_TO_CACHE = [
    '/',
    '/?lang=it',
    '/?lang=en',
    '/?lang=fr',
    '/assets/css/style.css',
    '/assets/css/theme-neon.css',
    '/assets/css/theme-futuristic.css',
    '/assets/js/main.js',
    '/manifest.json'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            );
        })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        })
    );
});
