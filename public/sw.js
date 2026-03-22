// Service Worker - Saint Seiya Deckbuilding PWA
const CACHE_NAME = 'ss-deckbuilding-v1';

// Fichiers a mettre en cache pour le mode hors-ligne (optionnel)
const STATIC_ASSETS = [
    '/favicon.ico',
    '/images/constellation.svg'
];

// Installation du service worker
self.addEventListener('install', (event) => {
    console.log('[SW] Installation...');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Mise en cache des ressources statiques');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activation et nettoyage des anciens caches
self.addEventListener('activate', (event) => {
    console.log('[SW] Activation...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        }).then(() => self.clients.claim())
    );
});

// Strategie Network First (reseau d'abord, cache en fallback)
self.addEventListener('fetch', (event) => {
    // Ignorer les requetes non-GET
    if (event.request.method !== 'GET') return;

    // Ignorer les requetes API et les websockets
    const url = new URL(event.request.url);
    if (url.pathname.startsWith('/api/') ||
        url.pathname.startsWith('/broadcasting/') ||
        url.pathname.startsWith('/livewire/')) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Mettre en cache les reponses reussies
                if (response.status === 200) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // En cas d'erreur reseau, utiliser le cache
                return caches.match(event.request);
            })
    );
});
