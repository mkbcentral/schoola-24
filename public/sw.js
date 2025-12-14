/* Service Worker pour Schoola PWA */

const CACHE_VERSION = 'v1.0.0';
const CACHE_NAME = `schoola-cache-${CACHE_VERSION}`;
const OFFLINE_URL = '/offline.html';

// Ressources à mettre en cache lors de l'installation
const PRECACHE_ASSETS = [
    '/',
    '/offline.html',
    '/images/Vector-white.svg',
    '/manifest.json'
];

// Ressources statiques à mettre en cache
const STATIC_CACHE_PATTERNS = [
    /\.(?:png|jpg|jpeg|svg|gif|webp)$/,
    /\.(?:woff|woff2|ttf|eot)$/,
    /\.(?:css|js)$/
];

// Routes à ne jamais mettre en cache
const NO_CACHE_PATTERNS = [
    /\/api\//,
    /\/livewire\//,
    /\/sanctum\//
];

/**
 * Installation du Service Worker
 */
self.addEventListener('install', (event) => {
    console.log('[SW] Installation en cours...');

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Mise en cache des ressources préconfigurées');
                return cache.addAll(PRECACHE_ASSETS);
            })
            .then(() => {
                console.log('[SW] Installation terminée');
                return self.skipWaiting();
            })
            .catch((error) => {
                console.error('[SW] Erreur lors de l\'installation:', error);
            })
    );
});

/**
 * Activation du Service Worker
 */
self.addEventListener('activate', (event) => {
    console.log('[SW] Activation en cours...');

    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                // Supprimer les anciens caches
                return Promise.all(
                    cacheNames
                        .filter((cacheName) => cacheName.startsWith('schoola-cache-') && cacheName !== CACHE_NAME)
                        .map((cacheName) => {
                            console.log('[SW] Suppression de l\'ancien cache:', cacheName);
                            return caches.delete(cacheName);
                        })
                );
            })
            .then(() => {
                console.log('[SW] Activation terminée');
                return self.clients.claim();
            })
    );
});

/**
 * Interception des requêtes (Fetch)
 */
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Ignorer les requêtes non-GET
    if (request.method !== 'GET') {
        return;
    }

    // Ignorer les patterns exclus du cache
    if (NO_CACHE_PATTERNS.some(pattern => pattern.test(url.pathname))) {
        return;
    }

    // Stratégie: Network First pour les pages HTML
    if (request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(networkFirstStrategy(request));
        return;
    }

    // Stratégie: Cache First pour les ressources statiques
    if (STATIC_CACHE_PATTERNS.some(pattern => pattern.test(url.pathname))) {
        event.respondWith(cacheFirstStrategy(request));
        return;
    }

    // Stratégie par défaut: Stale While Revalidate
    event.respondWith(staleWhileRevalidateStrategy(request));
});

/**
 * Stratégie: Network First (réseau en priorité)
 * Utilisée pour les pages HTML
 */
async function networkFirstStrategy(request) {
    try {
        const networkResponse = await fetch(request);

        if (networkResponse && networkResponse.status === 200) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }

        return networkResponse;
    } catch (error) {
        console.log('[SW] Réseau indisponible, utilisation du cache:', request.url);

        const cachedResponse = await caches.match(request);

        if (cachedResponse) {
            return cachedResponse;
        }

        // Retourner la page offline si disponible
        return caches.match(OFFLINE_URL);
    }
}

/**
 * Stratégie: Cache First (cache en priorité)
 * Utilisée pour les ressources statiques (images, fonts, CSS, JS)
 */
async function cacheFirstStrategy(request) {
    const cachedResponse = await caches.match(request);

    if (cachedResponse) {
        console.log('[SW] Ressource servie depuis le cache:', request.url);
        return cachedResponse;
    }

    try {
        const networkResponse = await fetch(request);

        if (networkResponse && networkResponse.status === 200) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }

        return networkResponse;
    } catch (error) {
        console.error('[SW] Erreur lors de la récupération:', request.url, error);

        // Retourner une image placeholder pour les images
        if (request.url.match(/\.(jpg|jpeg|png|gif|svg)$/)) {
            return caches.match('/images/placeholder.png');
        }
    }
}

/**
 * Stratégie: Stale While Revalidate
 * Retourne le cache immédiatement, puis met à jour en arrière-plan
 */
async function staleWhileRevalidateStrategy(request) {
    const cachedResponse = await caches.match(request);

    const fetchPromise = fetch(request)
        .then((networkResponse) => {
            if (networkResponse && networkResponse.status === 200) {
                // Clone AVANT de l'utiliser
                const responseToCache = networkResponse.clone();
                const cache = caches.open(CACHE_NAME);
                cache.then((c) => c.put(request, responseToCache));
            }
            return networkResponse;
        })
        .catch(() => {
            // En cas d'erreur réseau, on retourne le cache si disponible
            return cachedResponse;
        });

    return cachedResponse || fetchPromise;
}

/**
 * Gestion des messages
 */
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'CACHE_URLS') {
        const urlsToCache = event.data.urls;
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(urlsToCache));
    }
});

/**
 * Gestion des notifications push (optionnel)
 */
self.addEventListener('push', (event) => {
    const options = {
        body: event.data?.text() || 'Nouvelle notification',
        icon: '/images/icons/icon-192x192.png',
        badge: '/images/icons/icon-96x96.png',
        vibrate: [200, 100, 200],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'Voir',
                icon: '/images/icons/checkmark.png'
            },
            {
                action: 'close',
                title: 'Fermer',
                icon: '/images/icons/xmark.png'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification('Schoola', options)
    );
});

/**
 * Gestion des clics sur les notifications
 */
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

/**
 * Background Sync (optionnel)
 */
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-data') {
        event.waitUntil(syncData());
    }
});

async function syncData() {
    console.log('[SW] Synchronisation des données en arrière-plan');
    // Implémenter la logique de synchronisation ici
}

console.log('[SW] Service Worker Schoola chargé - Version:', CACHE_VERSION);
