# Guide des Modes Sombre et Clair - Quick Payment

## 📋 Vue d'ensemble

Le système de paiement rapide (Quick Payment) dispose d'un support complet pour les modes sombre et clair. Le thème s'adapte automatiquement aux préférences du système d'exploitation ou peut être contrôlé manuellement par l'utilisateur.

## 🎨 Fonctionnalités

### Adaptation Automatique

-   **Détection système** : Détecte automatiquement la préférence de thème du système
-   **Sauvegarde** : Mémorise la préférence de l'utilisateur dans `localStorage`
-   **Synchronisation** : Met à jour automatiquement si le système change de thème

### Composants Adaptés

Tous les composants Quick Payment s'adaptent aux modes sombre/clair :

1. **Barre de recherche**

    - Arrière-plan adaptatif
    - Bordures et textes contrastés
    - Placeholder lisible

2. **Dropdown autocomplete**

    - Fond adapté au thème
    - Ombres appropriées
    - Hover state visible

3. **Carte d'information élève**

    - Gradient ajusté au thème
    - Badges lisibles
    - Icônes contrastées

4. **Formulaire de paiement**

    - Champs de saisie adaptés
    - Labels visibles
    - Mode édition distinctif

5. **Liste des paiements**
    - Items bien contrastés
    - Hover state adapté
    - Scrollbar personnalisée

## 📁 Fichiers Créés

### CSS

-   **`resources/css/quick-payment-theme.css`**
    -   Variables CSS pour les deux thèmes
    -   Styles adaptatifs avec `@media (prefers-color-scheme: dark)`
    -   Classes utilitaires spécifiques

### JavaScript

-   **`resources/js/theme-switcher.js`**
    -   Classe `ThemeSwitcher` pour gérer les thèmes
    -   Détection des préférences système
    -   Sauvegarde dans localStorage
    -   API simple pour Livewire

### Composant Blade

-   **`resources/views/components/theme-toggle.blade.php`**
    -   Bouton de basculement de thème
    -   Alpine.js pour la réactivité
    -   Animations CSS

## 🔧 Utilisation

### 1. Ajouter le Bouton de Basculement

#### Dans le Navbar

```blade
{{-- Dans resources/views/components/layouts/partials/navbar.blade.php --}}
<div class="navbar-nav ms-auto">
    <x-theme-toggle />
</div>
```

#### Bouton Flottant

```javascript
// Dans app.js ou directement dans une vue
document.addEventListener("DOMContentLoaded", () => {
    window.createThemeToggleButton();
});
```

### 2. API JavaScript

```javascript
// Basculer entre les thèmes
window.toggleTheme();

// Définir un thème spécifique
window.setTheme("dark"); // ou 'light'

// Obtenir le thème actuel
const currentTheme = window.getTheme();

// Réinitialiser au thème système
window.themeSwitcher.resetToSystem();
```

### 3. Utilisation avec Livewire

```blade
<!-- Dans un composant Livewire -->
<button
    wire:click="$dispatch('theme-changed', { theme: 'dark' })"
    x-on:click="window.setTheme('dark')">
    Mode Sombre
</button>
```

### 4. Écouter les Changements de Thème

```javascript
// JavaScript
window.addEventListener("themeChanged", (e) => {
    console.log("Nouveau thème:", e.detail.theme);
    // Votre logique ici
});
```

```blade
<!-- Alpine.js -->
<div x-data="{ theme: window.getTheme() }"
     @theme-changed.window="theme = $event.detail.theme">
    Thème actuel: <span x-text="theme"></span>
</div>
```

## 🎯 Variables CSS Personnalisables

### Mode Clair (Défaut)

```css
:root {
    --qp-bg-primary: #ffffff;
    --qp-bg-secondary: #f8f9fa;
    --qp-text-primary: #1a1f36;
    --qp-border-color: #e1e4e8;
    /* ... */
}
```

### Mode Sombre

```css
[data-bs-theme="dark"] {
    --qp-bg-primary: #1a1d29;
    --qp-bg-secondary: #242837;
    --qp-text-primary: #e8eaed;
    --qp-border-color: #363b4a;
    /* ... */
}
```

### Personnalisation

Pour personnaliser les couleurs, modifiez les variables dans `quick-payment-theme.css` :

```css
/* Mode clair personnalisé */
:root {
    --qp-bg-primary: #f0f4f8; /* Votre couleur */
}

/* Mode sombre personnalisé */
[data-bs-theme="dark"] {
    --qp-bg-primary: #0f1419; /* Votre couleur */
}
```

## 🌟 Classes CSS Disponibles

### Cartes

-   `.quick-payment-card` : Carte principale adaptative
-   `.qp-student-info-card` : Carte info élève avec gradient

### Formulaires

-   `.qp-form-control` : Champ de saisie adaptatif
-   `.qp-form-select` : Select adaptatif
-   `.qp-form-label` : Label adaptatif
-   `.qp-search-input` : Input de recherche

### Dropdown

-   `.qp-dropdown` : Container dropdown
-   `.qp-dropdown-item` : Item de dropdown
-   `.qp-dropdown-empty` : Message vide
-   `.qp-dropdown-menu` : Menu dropdown

### Liste

-   `.qp-payment-item` : Item de paiement
-   `.qp-payment-list-card` : Carte de liste
-   `.qp-payment-divider` : Séparateur

### Utilitaires

-   `.qp-scrollable` : Zone défilable avec scrollbar personnalisée
-   `.qp-transition` : Transition standard (0.3s)
-   `.qp-transition-fast` : Transition rapide (0.2s)
-   `.qp-fade-in` : Animation d'apparition

## ♿ Accessibilité

Le système de thème respecte les standards d'accessibilité :

### Contraste

-   **WCAG 2.1 Level AA** : Ratio de contraste minimum de 4.5:1
-   Variables de couleur testées pour les deux thèmes
-   Support du mode `prefers-contrast: high`

### Focus

```css
.qp-search-input:focus-visible {
    outline: 3px solid var(--qp-primary);
    outline-offset: 2px;
}
```

### Mouvement Réduit

```css
@media (prefers-reduced-motion: reduce) {
    .qp-transition {
        transition: none !important;
        animation: none !important;
    }
}
```

### ARIA

```blade
<button
    aria-label="Basculer entre les modes sombre et clair"
    :title="theme === 'dark' ? 'Passer en mode clair' : 'Passer en mode sombre'">
```

## 📱 Responsive

Le système s'adapte aux différentes tailles d'écran :

```css
@media (max-width: 768px) {
    .qp-payment-list-card {
        position: relative !important;
        top: 0 !important;
    }

    .qp-scrollable {
        max-height: 400px !important;
    }
}
```

## 🔍 Débogage

### Vérifier le Thème Actuel

```javascript
console.log("Thème actuel:", window.getTheme());
console.log("Thème stocké:", localStorage.getItem("schoola-theme"));
console.log(
    "Thème système:",
    window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"
);
```

### Forcer un Thème

```javascript
// Pour tester rapidement
document.documentElement.setAttribute("data-bs-theme", "dark");
document.body.classList.add("dark-mode");
```

### Réinitialiser

```javascript
// Supprimer les préférences sauvegardées
localStorage.removeItem("schoola-theme");
window.themeSwitcher.resetToSystem();
```

## 🚀 Build et Production

### Compilation

```powershell
# Développement
npm run dev

# Production
npm run build
```

### Vérification

Après le build, vérifiez que :

1. ✅ `quick-payment-theme.css` est inclus dans les assets
2. ✅ `theme-switcher.js` est chargé dans `app.js`
3. ✅ Les variables CSS sont présentes dans le bundle

## 📊 Performance

### Optimisations Appliquées

-   **CSS Variables** : Changement de thème instantané sans rechargement
-   **LocalStorage** : Sauvegarde persistante légère
-   **Transitions ciblées** : Animations uniquement sur les éléments nécessaires
-   **Lazy Evaluation** : Détection système uniquement au besoin

### Poids

-   CSS : ~15 KB (non minifié)
-   JS : ~3 KB (non minifié)
-   Impact performance : Négligeable

## 🎓 Exemples d'Intégration

### Exemple 1 : Dropdown dans Navbar

```blade
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button"
       data-bs-toggle="dropdown">
        <i class="bi bi-palette"></i> Thème
    </a>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="#"
               onclick="window.setTheme('light'); return false;">
                <i class="bi bi-sun"></i> Clair
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#"
               onclick="window.setTheme('dark'); return false;">
                <i class="bi bi-moon"></i> Sombre
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item" href="#"
               onclick="window.themeSwitcher.resetToSystem(); return false;">
                <i class="bi bi-arrow-clockwise"></i> Auto (Système)
            </a>
        </li>
    </ul>
</li>
```

### Exemple 2 : Préférence dans Paramètres Utilisateur

```blade
<div class="form-group">
    <label>Préférence de thème</label>
    <select class="form-select" onchange="window.setTheme(this.value)">
        <option value="light">Clair</option>
        <option value="dark">Sombre</option>
    </select>
</div>
```

### Exemple 3 : Toggle dans Sidebar

```blade
<div class="sidebar-footer">
    <x-theme-toggle />
</div>
```

## 🐛 Résolution de Problèmes

### Le thème ne change pas

1. Vérifiez que `theme-switcher.js` est chargé
2. Ouvrez la console et testez `window.getTheme()`
3. Vérifiez l'attribut `data-bs-theme` sur `<html>`

### Les couleurs ne s'appliquent pas

1. Vérifiez que `quick-payment-theme.css` est importé
2. Inspectez les éléments pour voir si les classes CSS sont appliquées
3. Vérifiez la priorité CSS (utilisez `!important` si nécessaire)

### Le thème ne persiste pas

1. Vérifiez que localStorage n'est pas désactivé
2. Testez dans une fenêtre de navigation privée
3. Vérifiez les permissions du navigateur

## 📚 Ressources

-   [MDN - prefers-color-scheme](https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-color-scheme)
-   [Bootstrap 5 Dark Mode](https://getbootstrap.com/docs/5.3/customize/color-modes/)
-   [WCAG 2.1 Contrast Guidelines](https://www.w3.org/WAI/WCAG21/Understanding/contrast-minimum)

## 🔄 Mises à Jour Futures

### Fonctionnalités Prévues

-   [ ] Mode "Auto" avec détection du coucher/lever du soleil
-   [ ] Thèmes personnalisés additionnels (bleu, vert, etc.)
-   [ ] Animation de transition entre thèmes
-   [ ] Prévisualisation avant application
-   [ ] Synchronisation multi-onglets

## 📝 Changelog

### Version 1.0.0 (2024-11-25)

-   ✅ Support initial des modes sombre et clair
-   ✅ Adaptation de tous les composants Quick Payment
-   ✅ Classe `ThemeSwitcher` pour la gestion
-   ✅ Composant Blade `theme-toggle`
-   ✅ Sauvegarde dans localStorage
-   ✅ Détection des préférences système
-   ✅ Support de l'accessibilité WCAG 2.1 AA
-   ✅ Responsive design
-   ✅ Documentation complète

---

**Développé pour Schoola** - Système de gestion scolaire
