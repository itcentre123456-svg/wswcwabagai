const CACHE_NAME = "wswc-cache-v1";
const urlsToCache = [
  "/wswc/",
  "/wswc/login.php",
  "/wswc/Club/icons/icon192.png",
  "/wswc/Club/icons/icon512.png",
  // add more files here
];
96*
self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(urlsToCache);
    })
  );
  console.log("Service Worker installed");
});

self.addEventListener("activate", event => {
  console.log("Service Worker activated");
});

self.addEventListener("fetch", event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request);
    })
  );
});
