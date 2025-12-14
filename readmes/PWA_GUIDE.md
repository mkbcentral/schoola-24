# Guide PWA (Progressive Web App) - Schoola

## 📱 PWA Implémentée

Votre application Schoola est maintenant une **Progressive Web App (PWA)** complète avec support offline, installation sur appareil, et notifications push.

---

## ✅ Fonctionnalités PWA Implémentées

### 1. Manifest Web App (`public/manifest.json`)

-   ✅ Métadonnées complètes de l'application
-   ✅ Icônes multi-tailles (72x72 à 512x512)
-   ✅ Thème et couleurs personnalisés
-   ✅ Mode d'affichage standalone (comme une app native)
-   ✅ Shortcuts pour accès rapide aux fonctions clés
-   ✅ Screenshots pour les stores

### 2. Service Worker (`public/sw.js`)

-   ✅ Cache stratégique des ressources
-   ✅ Support offline complet
-   ✅ Mise à jour automatique du cache
-   ✅ 3 stratégies de cache implémentées :
    -   **Network First** : Pages HTML (contenu dynamique)
    -   **Cache First** : Assets statiques (images, fonts, CSS, JS)
    -   **Stale While Revalidate** : Autres ressources

### 3. Page Offline (`public/offline.html`)

-   ✅ Interface élégante hors ligne
-   ✅ Détection automatique de la reconnexion
-   ✅ Bouton de réessai manuel
-   ✅ Animation et design moderne

### 4. Meta Tags PWA

-   ✅ Theme color pour Android
-   ✅ Apple mobile web app meta tags
-   ✅ Manifest link
-   ✅ Apple touch icons

### 5. Enregistrement du Service Worker

-   ✅ Enregistrement automatique au chargement
-   ✅ Détection des mises à jour
-   ✅ Prompt de rafraîchissement
-   ✅ Gestion online/offline

---

## 🎯 Stratégies de Cache

### Network First (Pages HTML)

```
Réseau → Si échec → Cache → Si échec → Page Offline
```

**Utilisé pour** : Pages dynamiques, contenu mis à jour fréquemment

### Cache First (Assets Statiques)

```
Cache → Si absent → Réseau → Mettre en cache
```

**Utilisé pour** : Images, fonts, CSS, JavaScript

### Stale While Revalidate

```
Retourner le cache immédiatement + Mettre à jour en arrière-plan
```

**Utilisé pour** : APIs, données semi-dynamiques

---

## 📦 Fichiers Créés

### 1. `/public/manifest.json`

Configuration complète de la PWA avec :

-   Nom et description de l'application
-   Icônes pour tous les appareils
-   Couleurs de thème
-   Shortcuts vers les pages principales
-   Configuration d'affichage

### 2. `/public/sw.js`

Service Worker complet avec :

-   Cache des ressources
-   Stratégies de cache intelligentes
-   Gestion offline
-   Support des notifications push
-   Background sync

### 3. `/public/offline.html`

Page d'erreur offline avec :

-   Design moderne et responsive
-   Détection automatique de reconnexion
-   Bouton de réessai
-   Vérification périodique de la connexion

### 4. `/scripts/generate-pwa-icons.ps1`

Script PowerShell pour générer les icônes PWA

---

## 🎨 Génération des Icônes

### Option 1 : Automatique (Recommandé)

Utilisez [PWA Builder Image Generator](https://www.pwabuilder.com/imageGenerator) :

1. Téléchargez votre logo (minimum 512x512px)
2. Téléchargez le ZIP généré
3. Extrayez dans `public/images/icons/`

### Option 2 : Manuel avec ImageMagick

```powershell
# Installer ImageMagick d'abord
# https://imagemagick.org/script/download.php

cd scripts
.\generate-pwa-icons.ps1
```

### Option 3 : Online

-   [Favicon Generator](https://realfavicongenerator.net/)
-   [App Icon Generator](https://www.appicon.co/)

### Tailles Requises

-   72x72, 96x96, 128x128, 144x144
-   152x152, 192x192, 384x384, 512x512

---

## 🚀 Installation de la PWA

### Sur Android (Chrome)

1. Ouvrir l'application dans Chrome
2. Menu → "Installer l'application"
3. Ou banner automatique "Ajouter à l'écran d'accueil"

### Sur iOS (Safari)

1. Ouvrir dans Safari
2. Bouton Partage → "Sur l'écran d'accueil"
3. Renommer si nécessaire → Ajouter

### Sur Desktop (Chrome/Edge)

1. Icône d'installation dans la barre d'adresse
2. Ou Menu → "Installer Schoola..."

---

## 🧪 Test de la PWA

### Vérifier le Manifest

```
Chrome DevTools → Application → Manifest
```

Vérifiez :

-   ✅ Toutes les propriétés sont chargées
-   ✅ Icônes présentes et valides
-   ✅ Pas d'erreurs

### Vérifier le Service Worker

```
Chrome DevTools → Application → Service Workers
```

Vérifiez :

-   ✅ Service Worker enregistré et actif
-   ✅ Scope correct
-   ✅ Pas d'erreurs dans la console

### Test Offline

1. Ouvrir l'application
2. DevTools → Network → Offline
3. Rafraîchir la page
4. ✅ La page offline s'affiche
5. ✅ Contenu caché disponible

### Lighthouse Audit

```bash
# Chrome DevTools → Lighthouse
# Cocher : Progressive Web App
# Générer le rapport
```

**Objectif** : Score PWA > 90/100

---

## 📊 Scores PWA Attendus

### Critères Lighthouse

-   ✅ **Installable** : Manifest + Service Worker
-   ✅ **PWA Optimisé** : Icons, theme color
-   ✅ **Fonctionne offline** : Service Worker actif
-   ✅ **Responsive** : Mobile-friendly
-   ✅ **HTTPS** : Requis en production
-   ✅ **Fast** : Chargement < 3s

---

## 🔧 Configuration Serveur

### Apache (.htaccess)

```apache
# Forcer HTTPS (requis pour PWA)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

# Headers pour Service Worker
<FilesMatch "sw\.js$">
    Header set Service-Worker-Allowed "/"
    Header set Cache-Control "no-cache, no-store, must-revalidate"
</FilesMatch>

# Headers pour Manifest
<FilesMatch "manifest\.json$">
    Header set Content-Type "application/manifest+json"
    Header set Cache-Control "max-age=604800"
</FilesMatch>
```

### Nginx

```nginx
# Forcer HTTPS
server {
    listen 80;
    return 301 https://$host$request_uri;
}

# Headers Service Worker
location = /sw.js {
    add_header Service-Worker-Allowed "/";
    add_header Cache-Control "no-cache, no-store, must-revalidate";
}

# Headers Manifest
location = /manifest.json {
    add_header Content-Type "application/manifest+json";
    add_header Cache-Control "max-age=604800";
}
```

---

## 📱 Fonctionnalités Avancées

### 1. Notifications Push (Optionnel)

Le Service Worker inclut déjà le support. Pour activer :

```javascript
// Demander la permission
Notification.requestPermission().then((permission) => {
    if (permission === "granted") {
        console.log("Notifications activées");
    }
});

// Envoyer une notification
navigator.serviceWorker.ready.then((registration) => {
    registration.showNotification("Titre", {
        body: "Message",
        icon: "/images/icons/icon-192x192.png",
    });
});
```

### 2. Background Sync

Synchroniser les données en arrière-plan :

```javascript
// Enregistrer une sync
navigator.serviceWorker.ready.then((registration) => {
    return registration.sync.register("sync-data");
});
```

### 3. Share API

Partager du contenu :

```javascript
if (navigator.share) {
    navigator.share({
        title: "Schoola",
        text: "Système de gestion scolaire",
        url: window.location.href,
    });
}
```

---

## 🐛 Debugging

### Service Worker ne s'enregistre pas

1. Vérifier que le site est en HTTPS (ou localhost)
2. Vérifier que `/sw.js` est accessible
3. Console : Rechercher les erreurs

### Cache ne fonctionne pas

1. DevTools → Application → Clear storage
2. Désenregistrer le Service Worker
3. Rafraîchir et réenregistrer

### Page offline ne s'affiche pas

1. Vérifier que `/offline.html` existe
2. Vérifier qu'il est dans PRECACHE_ASSETS
3. Clear cache et retester

### Icônes ne s'affichent pas

1. Vérifier les chemins dans manifest.json
2. Vérifier que les fichiers existent
3. DevTools → Application → Manifest → Icons

---

## 📈 Monitoring PWA

### Métriques à Suivre

-   **Taux d'installation** : % d'utilisateurs qui installent
-   **Engagement** : Temps passé sur la PWA
-   **Offline** : Utilisation hors ligne
-   **Rétention** : Retours sur l'app

### Google Analytics PWA

```javascript
// Tracker l'installation
window.addEventListener("appinstalled", () => {
    gtag("event", "pwa_installed");
});

// Tracker l'usage offline
window.addEventListener("offline", () => {
    gtag("event", "pwa_offline");
});
```

---

## ✅ Checklist de Déploiement

-   [ ] Icônes générées dans `public/images/icons/`
-   [ ] Manifest.json accessible
-   [ ] Service Worker enregistré
-   [ ] Page offline fonctionnelle
-   [ ] HTTPS activé en production
-   [ ] Test Lighthouse > 90/100
-   [ ] Test sur Android/iOS
-   [ ] Test installation desktop
-   [ ] Test mode offline
-   [ ] Meta tags présents dans tous les layouts

---

## 🎓 Ressources

-   [PWA Builder](https://www.pwabuilder.com/)
-   [Web.dev PWA](https://web.dev/progressive-web-apps/)
-   [MDN Service Workers](https://developer.mozilla.org/en-US/docs/Web/API/Service_Worker_API)
-   [Workbox](https://developers.google.com/web/tools/workbox)
-   [PWA Checklist](https://web.dev/pwa-checklist/)

---

**PWA Version** : 1.0.0  
**Service Worker** : v1.0.0  
**Date** : 25 Novembre 2025
