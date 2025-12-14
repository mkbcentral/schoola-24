# 📚 Guide du système Z-Index

## 🎯 Problème résolu

La navbar s'affichait au-dessus des modals, overlays et autres éléments importants, causant des problèmes d'interface.

## ✅ Solution implémentée

Un système de z-index hiérarchique cohérent utilisant des variables CSS.

## 📊 Hiérarchie des niveaux (du plus bas au plus haut)

```scss
--z-base: 1              // Niveau de base (éléments normaux)
--z-elevated: 10         // Éléments légèrement élevés
--z-fixed: 100           // Éléments en position fixed standard
--z-sticky: 200          // Navbar sticky - SOUS les modals
--z-dropdown: 1000       // Menus déroulants
--z-modal-backdrop: 1040 // Fond sombre des modals
--z-offcanvas-backdrop: 1045
--z-offcanvas: 1050      // Panneaux latéraux (offcanvas)
--z-modal: 1055          // Modals - AU-DESSUS de tout sauf overlay
--z-popover: 1060        // Popovers (info-bulles avancées)
--z-tooltip: 1070        // Tooltips simples
--z-overlay: 9999        // Overlays de chargement (placeholder Livewire)
--z-top: 10000           // Niveau maximum absolu (notifications toast, etc.)
```

## 🎨 Utilisation dans les templates

### ✅ CORRECT - Utiliser les variables CSS

```blade
<!-- Overlay de chargement -->
<div style="z-index: var(--z-overlay);">

<!-- Modal -->
<div style="z-index: var(--z-modal);">

<!-- Dropdown -->
<div style="z-index: var(--z-dropdown);">
```

### ❌ INCORRECT - NE PAS hardcoder les valeurs

```blade
<!-- À ÉVITER -->
<div style="z-index: 9999;">
<div style="z-index: 1050;">
```

## 🔧 Fichiers modifiés

### 1. `resources/sass/base/_z-index.scss`

-   Définition centralisée de tous les niveaux z-index
-   Hiérarchie corrigée : navbar (200) < modal (1055) < overlay (9999)

### 2. Templates Blade mis à jour

-   `livewire/placeholder.blade.php` - Utilise `--z-overlay`
-   `components/v2/loading-overlay.blade.php` - Utilise `--z-modal` et `--z-modal-backdrop`
-   `livewire/application/student/student-info-page.blade.php` - Utilise `--z-dropdown`
-   `livewire/application/registration/list/list-registration-by-date-page.blade.php` - Utilise `--z-dropdown`

## 📝 Classes utilitaires disponibles

```html
<div class="z-base">...</div>
<div class="z-sticky">...</div>
<div class="z-modal">...</div>
<div class="z-overlay">...</div>
<!-- etc. -->
```

## 🎯 Règles à suivre

1. **Toujours utiliser les variables CSS** au lieu de valeurs hardcodées
2. **Navbar reste en dessous** des modals/overlays (z-sticky: 200)
3. **Modals au-dessus** de la navbar (z-modal: 1055)
4. **Overlays de chargement au top** (z-overlay: 9999)
5. **Ne jamais dépasser z-top** (10000) sauf cas exceptionnel

## 🐛 Débogage

Si un élément passe au-dessus d'un autre de manière incorrecte :

1. Vérifier le z-index dans l'inspecteur
2. S'assurer qu'il utilise les variables CSS
3. Vérifier la hiérarchie dans `_z-index.scss`
4. Vérifier que le contexte de stacking est correct (position: relative/absolute/fixed)

## 🔄 Recompilation nécessaire

Après modification de `_z-index.scss`, recompiler le CSS :

```bash
npm run dev
# ou
npm run build
```

---

**Dernière mise à jour :** Décembre 2025
