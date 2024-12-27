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
    // Abaikan permintaan dengan metode selain GET
    if (event.request.method !== 'GET') {
        event.respondWith(fetch(event.request).catch(() => {
            // Tampilkan fallback halaman offline jika server tidak dapat diakses
            return caches.match('/offline');
        }));
        return;
    }

    // Lanjutkan untuk menangani metode GET seperti biasa
    event.respondWith(
        caches.open(CACHE_NAME).then(cache => {
            return cache.match(event.request).then(cachedResponse => {
                // Cek beberapa URL dan metode GET
                if (
                    (event.request.url.endsWith('/') || 
                     event.request.url.endsWith('/home') || 
                     event.request.url.endsWith('/dashboard') || 
                     event.request.url.endsWith('/css/sb-admin-2.min.css') || 
                     event.request.url.endsWith('/css/styles.css') || 
                     event.request.url.endsWith('/css/stylesdokter.css') || 
                     event.request.url.endsWith('/css/stylesfoto.css') || 
                     event.request.url.endsWith('/js/scripts.js') || 
                     event.request.url.endsWith('/js/scriptsfoto.js') || 
                     event.request.url.endsWith('/js/sb-admin.js') || 
                     event.request.url.endsWith('/img/1.jpg') || 
                     event.request.url.endsWith('/img/2.jpg') || 
                     event.request.url.endsWith('/img/3.jpg') || 
                     event.request.url.endsWith('/img/profile.png') || 
                     event.request.url.endsWith('/img/testimonials-1.jpg') || 
                     event.request.url.endsWith('/img/testimonials-2.jpg') || 
                     event.request.url.endsWith('/img/testimonials-3.jpg') || 
                     event.request.url.endsWith('/img/portfolio/bleaching.jpg') || 
                     event.request.url.endsWith('/img/portfolio/bracesilustra.jpg') || 
                     event.request.url.endsWith('/img/portfolio/denture.png') || 
                     event.request.url.endsWith('/img/portfolio/exract.jpg') || 
                     event.request.url.endsWith('/img/portfolio/scaling.png') || 
                     event.request.url.endsWith('/img/portfolio/tambal.jpg')) &&
                    event.request.method === 'GET'
                ) {
                    return cachedResponse || fetch(event.request).then(networkResponse => {
                        const responseToCache = networkResponse.clone();
                        cache.put(event.request, responseToCache);
                        return networkResponse;
                    }).catch(() => {
                        return cachedResponse || caches.match('/offline');
                    });
                }

                // Jika cache ada, periksa waktu kedaluwarsa
                if (cachedResponse) {
                    const headers = cachedResponse.headers;
                    const dateHeader = headers.get('sw-timestamp');
                    if (dateHeader) {
                        const cacheTime = new Date(dateHeader);
                        const now = new Date();
                        const diff = now - cacheTime;

                        // Hapus cache jika lebih dari 1 jam
                        if (diff > 3600000) {
                            cache.delete(event.request);
                            return caches.match('/offline');
                        }
                    }
                }

                // Jika cache tidak ditemukan atau cache kedaluwarsa, coba ambil dari jaringan
                return fetch(event.request).then(networkResponse => {
                    const responseToCache = networkResponse.clone();
                    const newHeaders = new Headers(networkResponse.headers);
                    newHeaders.append('sw-timestamp', new Date().toISOString());

                    return cache.put(event.request, new Response(responseToCache.body, { headers: newHeaders }))
                        .then(() => networkResponse);
                }).catch(() => {
                    return cachedResponse || caches.match('/offline');
                });
            });
        })
    );
});


// self.addEventListener('fetch', event => {
//     event.respondWith(
//         caches.open(CACHE_NAME).then(cache => {
//             return cache.match(event.request).then(cachedResponse => {
//                 // Cek apakah halaman yang diminta adalah "/"
//                 if (event.request.url.endsWith('/') && event.request.method === 'GET') {
//                     // Jika halaman "/" diakses, kembalikan cache walaupun sudah lebih dari 1 jam
//                     return cachedResponse || fetch(event.request).then(networkResponse => {
//                         const responseToCache = networkResponse.clone();
//                         return cache.put(event.request, responseToCache).then(() => networkResponse);
//                     }).catch(() => {
//                         // Jika jaringan gagal, coba ambil dari cache atau fallback ke offline
//                         return cachedResponse || caches.match('/offline');
//                     });
//                 }

//                 // Jika cache ada, periksa waktu kedaluwarsa
//                 if (cachedResponse) {
//                     const headers = cachedResponse.headers;
//                     const dateHeader = headers.get('sw-timestamp');
//                     if (dateHeader) {
//                         const cacheTime = new Date(dateHeader);
//                         const now = new Date();
//                         const diff = now - cacheTime;

//                         // Hapus cache jika lebih dari 1 jam
//                         if (diff > 60000) { // 1 jam = 3600000ms
//                             cache.delete(event.request);
//                             return caches.match('/offline'); // Arahkan ke halaman offline
//                         }
//                     }
//                 }

//                 // Jika cache tidak ditemukan atau cache kedaluwarsa, coba ambil dari jaringan
//                 return fetch(event.request).then(networkResponse => {
//                     const responseToCache = networkResponse.clone();
//                     const newHeaders = new Headers(networkResponse.headers);
//                     newHeaders.append('sw-timestamp', new Date().toISOString());

//                     return cache.put(event.request, new Response(responseToCache.body, { headers: newHeaders }))
//                         .then(() => networkResponse);
//                 }).catch(() => {
//                     // Jika jaringan gagal, kembalikan cache atau fallback ke offline
//                     return cachedResponse || caches.match('/offline');
//                 });
//             });
//         })
//     );
// });


// self.addEventListener('fetch', event => {
//     event.respondWith(
//         caches.open(CACHE_NAME).then(cache => {
//             return cache.match(event.request).then(cachedResponse => {
//                 // Periksa apakah cache valid
//                 if (cachedResponse) {
//                     const headers = cachedResponse.headers;
//                     const dateHeader = headers.get('sw-timestamp');
//                     if (dateHeader) {
//                         const cacheTime = new Date(dateHeader);
//                         const now = new Date();
//                         const diff = now - cacheTime;

//                         // Hapus cache jika lebih dari 1 jam
//                         if (diff > 60000) { // 1 jam = 3600000ms
//                             cache.delete(event.request);
//                             return caches.match('/offline');
//                         }
//                     }
//                 }

//                 // Jika tidak ada cache atau cache valid, coba fetch dari jaringan
//                 return fetch(event.request).then(networkResponse => {
//                     // Simpan di cache dengan header timestamp
//                     const responseToCache = networkResponse.clone();
//                     const newHeaders = new Headers(networkResponse.headers);
//                     newHeaders.append('sw-timestamp', new Date().toISOString());

//                     return cache.put(event.request, new Response(responseToCache.body, { headers: newHeaders }))
//                         .then(() => networkResponse);
//                 }).catch(() => {
//                     // Kembalikan cache jika fetch gagal
//                     return cachedResponse || caches.match('/offline');
//                 });
//             });
//         })
//     );
// });


// const CACHE_NAME = 'pwa-cache-v1';
// const urlsToCache = [
//     '/',
//     '/admin',
//     '/offline',
//     '/css/sb-admin-2.min.css',
//     '/css/styles.css',
//     '/css/stylesdokter.css',
//     '/css/stylesfoto.css',
//     '/js/scripts.js',
//     '/js/scriptsfoto.js',
//     '/js/sb-admin.js',
//     '/img/1.jpg',
//     '/img/2.jpg',
//     '/img/3.jpg',
//     '/img/profile.png',
//     '/img/testimonials-1.jpg',
//     '/img/testimonials-2.jpg',
//     '/img/testimonials-3.jpg',
//     '/img/portfolio/bleaching.jpg',
//     '/img/portfolio/bracesilustra.jpg',
//     '/img/portfolio/denture.png',
//     '/img/portfolio/exract.jpg',
//     '/img/portfolio/scaling.png',
//     '/img/portfolio/tambal.jpg',
// ];

// self.addEventListener('install', event => {
//     event.waitUntil(
//         caches.open(CACHE_NAME)
//             .then(cache => {
//                 return cache.addAll(urlsToCache);
//             })
//     );
// });

// self.addEventListener('fetch', event => {
//     event.respondWith(
//         fetch(event.request).then(response => {
//             // Jika berhasil mengambil dari jaringan, simpan di cache dan kembalikan respons
//             return caches.open(CACHE_NAME).then(cache => {
//                 cache.put(event.request, response.clone());
//                 return response;
//             });
//         }).catch(() => {
//             // Jika jaringan gagal, coba ambil dari cache
//             return caches.match(event.request).then(response => {
//                 return response || caches.match('/offline');
//             });
//         })
//     );
// });