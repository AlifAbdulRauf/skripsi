const CACHE_NAME = 'pwa-cache-v1';
const urlsToCache = [
    '/',
    '/admin',
    '/offline',
    '/css/sb-admin-2.min.css',
    '/css/styles.css',
    '/css/stylesdokter.css',
    '/css/stylesfoto.css',
    '/js/scripts.js',
    '/js/scriptsfoto.js',
    '/js/sb-admin.js',
    '/img/1.jpg',
    '/img/2.jpg',
    '/img/3.jpg',
    '/img/profile.png',
    '/img/testimonials-1.jpg',
    '/img/testimonials-2.jpg',
    '/img/testimonials-3.jpg',
    '/img/portfolio/bleaching.jpg',
    '/img/portfolio/bracesilustra.jpg',
    '/img/portfolio/denture.png',
    '/img/portfolio/exract.jpg',
    '/img/portfolio/scaling.png',
    '/img/portfolio/tambal.jpg',
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', event => {
    event.respondWith(
        fetch(event.request).then(response => {
            // Jika berhasil mengambil dari jaringan, simpan di cache dan kembalikan respons
            return caches.open(CACHE_NAME).then(cache => {
                cache.put(event.request, response.clone());
                return response;
            });
        }).catch(() => {
            // Jika jaringan gagal, coba ambil dari cache
            return caches.match(event.request).then(response => {
                return response || caches.match('/offline');
            });
        })
    );
});