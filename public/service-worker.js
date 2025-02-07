const CACHE_NAME = "my-site-cache-v1";
const urlsToCache = [
    "/",
    "/css/bulma.min.css",
    "/css/font-awesome.min.css",
    "/js/tailwind.js",
    "/js/alpine.js",
    // Add other assets you want to cache
];

self.addEventListener("install", (event) => {
    // Perform install steps
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log("Opened cache");
            return cache.addAll(urlsToCache);
        })
    );
});

self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            // Cache hit - return response
            if (response) {
                return response;
            }
            return fetch(event.request);
        })
    );
});
