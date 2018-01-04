const CACHE_NAME = 'blog-1.0.5';
const CACHED_FILES = [
    'css/app.css',
    'css/test.css',
    'js/app.js',
    'js/test.js',
    'storage/'
];

self.addEventListener('install', (evt) => {
    // console.log('install', evt);
    evt.waitUntil(
        Promise.resolve()
            .then(() => {
                return caches.open(CACHE_NAME);
            })
            .then((cache) => {
                return cache.addAll(CACHED_FILES);
            })
            .then(() => {
                self.skipWaiting();
            })
    );
});