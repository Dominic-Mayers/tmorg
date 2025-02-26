var cacheName = 'business-card-pwa';
var filesToCache = [
  '/governors/js/main.js',
  '/governors/images_card/Business_card_back.pdf',
  '/governors/images_card/Carte_affaire_arriere.pdf',
  '/governors/images_card/Business_Card_TM_Pale_logo_no_text.jpg',
  '/governors/images_card/Carte_Affaire_MT_Pale_logo_no_text.jpg'
];

/* Start the service worker and cache all of the app's content */
self.addEventListener('install', function(e) {
  e.waitUntil(
    caches.open(cacheName).then(function(cache) {
      return cache.addAll(filesToCache);
    })
  );
});

/* Serve cached content when offline */
self.addEventListener('fetch', function(e) {
  e.respondWith(
    caches.match(e.request).then(function(response) {
      return response || fetch(e.request);
    })
  );
});
