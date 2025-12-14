# Rapport de Configuration du Thème - Projet Schoola

**Date:** 17 novembre 2025  
**Projet:** schoola-web  
**Framework:** Laravel + Bootstrap 5.3.3

---

## 📋 Vue d'ensemble

Le projet utilise un système de thème basé sur **Bootstrap 5.3.3** avec un mode sombre/clair dynamique géré via l'attribut `data-bs-theme`. La gestion du thème est implémentée à la fois côté serveur (Laravel) et côté client (JavaScript).

---

## 🎨 Architecture du Système de Thème

### 1. Framework CSS Principal

-   **Bootstrap 5.3.3** - Framework CSS principal
-   **Bootstrap Icons 1.11.3** - Bibliothèque d'icônes
-   **Sass** - Préprocesseur CSS (version 1.77.6)
-   **Vite** - Bundler pour compilation des assets

### 2. Structure des Fichiers

```
resources/
├── sass/
│   ├── app.scss              # Fichier principal (1072 lignes)
│   └── guest.scss            # Styles pour pages publiques
├── js/
│   ├── app.js                # Point d'entrée JavaScript
│   └── main.js               # Gestion du thème et graphiques
└── views/
    ├── components/
    │   └── layouts/
    │       └── app.blade.php # Layout principal
    └── livewire/application/setting/page/
        └── setting-theme-page.blade.php # Page paramètres thème
```

---

## 🔧 Variables CSS Personnalisées

### Variables de Thème Clair (`[data-bs-theme="light"]`)

```scss
--sidebar-bg: #343a40              // Gris foncé
--sidebar-header-bg: #2c3136       // Gris plus foncé
--sidebar-hover-bg: #2c3136        // Même que header
--sidebar-footer-bg: #2c3136       // Même que header
--sidebar-active-bg: rgba(13, 110, 253, 0.1)  // Bleu transparent
--card-bg: #ffffff                 // Blanc
--card-border: rgba(0, 0, 0, 0.125) // Gris transparent
--timeline-border: #eee            // Gris clair
--avatar-circle-bg: #e9ecef        // Gris très clair
```

### Variables de Thème Sombre (`[data-bs-theme="dark"]`)

```scss
--sidebar-bg: #1a1d20              // Noir profond
--sidebar-header-bg: #151719       // Noir plus profond
--sidebar-hover-bg: #151719        // Même que header
--sidebar-footer-bg: #151719       // Même que header
--sidebar-active-bg: rgba(13, 110, 253, 0.15)  // Bleu transparent (plus visible)
--card-bg: #2c3034                 // Gris très foncé
--card-border: #373b3e             // Gris foncé
--timeline-border: #373b3e         // Même que card-border
--avatar-circle-bg: #373b3e        // Même que card-border
```

### Variables Bootstrap par Défaut (`:root`)

```scss
--bs-blue: #0d6efd
--bs-indigo: #6610f2
--bs-purple: #6f42c1
--bs-pink: #d63384
--bs-red: #dc3545
--bs-orange: #fd7e14
--bs-yellow: #ffc107
--bs-green: #198754
--bs-teal: #20c997
--bs-cyan: #0dcaf0
```

---

## 🌓 Gestion du Mode Sombre/Clair

### 1. Système de Stockage

-   **LocalStorage:** `localStorage.getItem('theme')`
-   **Valeurs possibles:** `'light'`, `'dark'`, `'auto'`
-   **Détection système:** `window.matchMedia('(prefers-color-scheme: dark)')`

### 2. Application du Thème

**Fichier:** `resources/js/main.js`

```javascript
// Initialisation au chargement de la page
const savedTheme = localStorage.getItem("theme");
const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

let initialTheme = savedTheme;
if (!savedTheme || savedTheme === "auto") {
    initialTheme = prefersDark ? "dark" : "light";
}

document.documentElement.setAttribute("data-bs-theme", initialTheme);
```

### 3. Interface de Changement de Thème

**Page:** `setting-theme-page.blade.php`

**Options disponibles:**

-   ☀️ **Clair** - Mode lumineux
-   🌙 **Sombre** - Mode foncé
-   ⚪ **Auto** - Suit les préférences système

**Implémentation:**

```javascript
function setTheme(theme) {
    document.documentElement.setAttribute("data-bs-theme", theme);
    localStorage.setItem("theme", theme);

    // Mise à jour des graphiques Chart.js
    if (window.Chart) {
        Chart.helpers.each(Chart.instances, function (instance) {
            const isDark = theme === "dark";
            instance.options.plugins.legend.labels.color = isDark
                ? "#fff"
                : "#666";
            instance.options.scales.x.grid.color = isDark ? "#373b3e" : "#ddd";
            instance.options.scales.y.grid.color = isDark ? "#373b3e" : "#ddd";
            instance.update();
        });
    }
}
```

---

## 🎯 Composants Stylisés en Mode Sombre

### 1. Éléments Généraux

-   **Body:** `background: #212529`, `color: #fff`
-   **Cards:** `background: #2c3034`, `border: #373b3e`
-   **Navbar:** `background: #2c3034`, `border-bottom: #373b3e`
-   **Breadcrumbs:** Texte blanc, liens bleus

### 2. Formulaires

-   **Inputs/Selects:** `background: #1a1d20`, `border: #373b3e`, `color: #fff`
-   **Focus:** `border-color: #0d6efd`
-   **Valid/Invalid:** Couleurs ajustées pour contraste

### 3. Tableaux

```scss
[data-bs-theme="dark"] .table {
    --bs-table-color: #fff;
    --bs-table-bg: transparent;
    --bs-table-border-color: #373b3e;
    --bs-table-striped-bg: rgba(255, 255, 255, 0.05);
    --bs-table-hover-bg: rgba(255, 255, 255, 0.075);
}
```

### 4. Boutons

-   **btn-info:** `background: #0dcaf0`, `color: #000`
-   **Hover:** Éclaircissement automatique
-   **Active:** Ombres et transitions

### 5. Dropdowns

-   **Background:** `#1a1d20`
-   **Borders:** `#373b3e`
-   **Hover:** `#23272b`

### 6. Modals

-   **Header:** `background: #1a1d20`
-   **Borders:** `#373b3e`
-   **Body:** Hérité du thème

### 7. Graphiques (Chart.js)

-   **Canvas:** Filtre `brightness(0.8)`
-   **Grilles:** `#373b3e` (dark) / `#ddd` (light)
-   **Textes:** `#fff` (dark) / `#666` (light)
-   **Mise à jour dynamique** lors du changement de thème

---

## 📱 Composants Spéciaux

### Sidebar

```scss
#sidebar {
    background: var(--sidebar-bg);
    color: #fff;
    position: fixed;
    height: 100vh;
    z-index: 1000;
}

// Liens actifs
#sidebar ul li.active > a {
    background: var(--sidebar-active-bg);
    border-left-color: #0d6efd;
    color: #0d6efd;
}

// Hover
#sidebar ul li a:hover {
    background: var(--sidebar-hover-bg);
    border-left-color: rgba(13, 110, 253, 0.5);
}
```

### Timeline

-   **Marker:** Cercles colorés avec bordures adaptées
-   **Content:** Bordures bottom selon le thème
-   **Texte:** Opacité réduite pour hiérarchie visuelle

### Offcanvas (Notifications)

-   **Background:** Suit `--card-bg`
-   **Header:** Couleur primaire + bordure
-   **Items:** Hover avec `--sidebar-hover-bg`

---

## 🌐 Page d'Accueil Publique

**Fichier:** `resources/views/home.blade.php`

### Variables CSS Personnalisées

```css
:root {
    --primary-color: #1e90ff; /* Bleu ciel */
    --secondary-color: #4169e1; /* Bleu royal */
    --light-color: #f8f9fa; /* Gris très clair */
    --dark-color: #343a40; /* Gris foncé */
}
```

### Utilisation

-   **Hero Section:** Gradient `linear-gradient(135deg, var(--primary-color), var(--secondary-color))`
-   **Navbar:** Transparent au départ, devient solide au scroll
-   **Boutons:** Couleurs primaires avec transitions

---

## 🔄 Synchronisation Multi-Onglets

Le système actuel **NE synchronise PAS** automatiquement le thème entre onglets. Chaque onglet lit le thème au chargement depuis localStorage.

### Amélioration Suggérée

```javascript
// Écouter les changements dans localStorage
window.addEventListener("storage", (e) => {
    if (e.key === "theme") {
        document.documentElement.setAttribute("data-bs-theme", e.newValue);
    }
});
```

---

## 📊 Dépendances du Thème

### NPM Packages

```json
"bootstrap": "^5.3.3",              // Framework CSS principal
"bootstrap-icons": "^1.11.3",       // Icônes
"sass": "1.77.6",                   // Préprocesseur
"toastr": "^2.1.4",                 // Notifications (stylisées pour dark)
"sweetalert2": "^11.6.13",          // Alertes (gère le thème)
"select2": "^4.1.0-rc.0",           // Dropdowns améliorés
"select2-bootstrap-5-theme": "^1.3.0" // Thème Bootstrap 5
```

---

## ⚠️ Points d'Attention

### 1. Conflits de Couleurs

-   Certains composants utilisent des couleurs **hardcodées** au lieu de variables CSS
-   **Exemples:**
    -   `student-info-page.blade.php` - Couleurs inline: `#059669`, `#f8f9fa`, etc.
    -   `home.blade.php` - Variables custom au lieu de Bootstrap

### 2. Mode Impression

**Fichier:** `print.blade.php`

-   Utilise Bootstrap 4.6.2 (pas la version 5.3.3)
-   Pas de support du mode sombre pour l'impression
-   Styles inline uniquement

### 3. Pagination

**Config:** `config/livewire.php`

```php
'pagination_theme' => 'tailwind',  // ⚠️ Utilise Tailwind, pas Bootstrap
```

**Recommandation:** Changer en `'bootstrap'` pour cohérence

### 4. Anciens Fichiers

-   `resources/views/components/settings.txt` - Fichier de configuration obsolète
-   Contient du code dupliqué avec `setting-theme-page.blade.php`

---

## 🎯 Recommandations d'Amélioration

### 1. Centraliser les Variables de Couleurs

**Créer:** `resources/sass/_variables.scss`

```scss
// Couleurs primaires
$primary-color: #1e90ff;
$secondary-color: #4169e1;

// Thème clair
$light-sidebar-bg: #343a40;
$light-card-bg: #ffffff;

// Thème sombre
$dark-sidebar-bg: #1a1d20;
$dark-card-bg: #2c3034;

// Importer dans Bootstrap
@import "variables";
@import "bootstrap/scss/bootstrap";
```

### 2. Uniformiser les Composants

-   Remplacer les couleurs hardcodées par des variables CSS
-   Utiliser `var(--bs-primary)` au lieu de `#0d6efd`
-   Créer des mixins pour les transitions communes

### 3. Améliorer la Page de Paramètres

**Ajouter:**

-   ✅ Prévisualisation du thème en direct
-   ✅ Sélecteur de couleur primaire personnalisée
-   ✅ Taille de police ajustable
-   ✅ Espacement personnalisable

### 4. Optimiser les Performances

```javascript
// Debouncer les changements de thème
const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

const setThemeDebounced = debounce(setTheme, 100);
```

### 5. Accessibilité

-   ✅ Ajouter `prefers-contrast: high` pour contraste élevé
-   ✅ Support de `prefers-reduced-motion`
-   ✅ Focus visible sur tous les éléments interactifs
-   ✅ ARIA labels sur les boutons de changement de thème

### 6. Documentation

-   Créer un guide de style (style guide) avec tous les composants
-   Documenter les classes utilitaires personnalisées
-   Exemples d'utilisation pour chaque composant

---

## 📝 Fichiers Clés à Consulter

| Fichier                                                                          | Lignes | Description                        |
| -------------------------------------------------------------------------------- | ------ | ---------------------------------- |
| `resources/sass/app.scss`                                                        | 1072   | Styles principaux, variables thème |
| `resources/js/main.js`                                                           | ~150   | Gestion JS du thème et graphiques  |
| `resources/views/components/layouts/app.blade.php`                               | ~40    | Layout principal application       |
| `resources/views/livewire/application/setting/page/setting-theme-page.blade.php` | ~100   | Interface paramètres thème         |
| `resources/views/home.blade.php`                                                 | ~600   | Page d'accueil publique            |
| `vite.config.js`                                                                 | ~25    | Configuration build assets         |
| `package.json`                                                                   | ~35    | Dépendances NPM                    |

---

## 🔍 Statistiques

-   **Total de variables CSS personnalisées:** ~20
-   **Composants avec mode sombre:** ~30
-   **Fichiers SCSS:** 2 (app.scss, guest.scss)
-   **Système de thème:** Bootstrap 5.3 `data-bs-theme`
-   **Support navigateurs:** Tous les navigateurs modernes
-   **Support mobile:** Responsive design complet

---

## ✅ Conclusion

Le projet dispose d'un **système de thème robuste et bien structuré** basé sur Bootstrap 5.3.3. Le mode sombre est implémenté de manière cohérente avec des variables CSS bien organisées.

**Forces:**

-   ✅ Mode sombre/clair/auto fonctionnel
-   ✅ Sauvegarde des préférences utilisateur
-   ✅ Mise à jour dynamique des graphiques
-   ✅ Support de la détection système

**Axes d'amélioration:**

-   ⚠️ Centraliser les variables de couleurs
-   ⚠️ Uniformiser l'utilisation des variables CSS
-   ⚠️ Ajouter synchronisation multi-onglets
-   ⚠️ Améliorer l'accessibilité
-   ⚠️ Créer un style guide

---

**Auteur:** GitHub Copilot  
**Modèle:** Claude Sonnet 4.5  
**Date de génération:** 17 novembre 2025
