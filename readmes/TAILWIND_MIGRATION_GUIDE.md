# 🔄 Guide de Migration Bootstrap → Tailwind CSS

## 📋 Table de conversion rapide

### 🎨 Layout & Containers

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `container` | `container mx-auto px-4` | Container centré avec padding |
| `container-fluid` | `w-full px-4` | Container pleine largeur |
| `row` | `flex flex-wrap` | Utiliser Flexbox ou Grid |
| `col` | `flex-1` | Colonne flexible |
| `col-6` | `w-1/2` | 50% largeur |
| `col-md-4` | `md:w-1/3` | 33% sur écrans moyens |
| `d-flex` | `flex` | Display flex |
| `d-none` | `hidden` | Masquer élément |
| `d-block` | `block` | Display block |
| `d-inline-block` | `inline-block` | Display inline-block |

### 🔘 Boutons

| Bootstrap | Tailwind | Component CSS |
|-----------|----------|---------------|
| `btn btn-primary` | `btn btn-primary` | ✅ Classe préservée |
| `btn btn-secondary` | `btn btn-secondary` | ✅ Classe préservée |
| `btn btn-success` | `btn btn-success` | ✅ Classe préservée |
| `btn btn-danger` | `btn btn-danger` | ✅ Classe préservée |
| `btn btn-warning` | `btn btn-warning` | ✅ Classe préservée |
| `btn btn-outline-primary` | `btn btn-outline-primary` | ✅ Classe préservée |
| `btn-sm` | `btn-sm` | ✅ Classe préservée |
| `btn-lg` | `btn-lg` | ✅ Classe préservée |

### 🃏 Cards

| Bootstrap | Tailwind | Component CSS |
|-----------|----------|---------------|
| `card` | `card` | ✅ Classe préservée |
| `card-header` | `card-header` | ✅ Classe préservée |
| `card-body` | `card-body` | ✅ Classe préservée |
| `card-footer` | `card-footer` | ✅ Classe préservée |
| `card-title` | `text-lg font-semibold` | Direct Tailwind |

### 📝 Forms

| Bootstrap | Tailwind | Component CSS |
|-----------|----------|---------------|
| `form-control` | `form-control` | ✅ Classe préservée |
| `form-label` | `form-label` | ✅ Classe préservée |
| `form-select` | `form-select` | ✅ Classe préservée |
| `form-check` | `flex items-center` | Direct Tailwind |
| `form-check-input` | `mr-2` | Direct Tailwind |
| `input-group` | `flex` | Direct Tailwind |

### 🏷️ Badges

| Bootstrap | Tailwind | Component CSS |
|-----------|----------|---------------|
| `badge bg-primary` | `badge badge-primary` | ✅ Classe préservée |
| `badge bg-success` | `badge badge-success` | ✅ Classe préservée |
| `badge bg-danger` | `badge badge-danger` | ✅ Classe préservée |
| `badge bg-warning` | `badge badge-warning` | ✅ Classe préservée |
| `badge rounded-pill` | `badge rounded-full` | Modifié |

### 📊 Tables

| Bootstrap | Tailwind | Component CSS |
|-----------|----------|---------------|
| `table` | `table` | ✅ Classe préservée |
| `table-striped` | `table table-striped` | ✅ Classe préservée |
| `table-hover` | `table table-hover` | ✅ Classe préservée |
| `table-bordered` | `border border-gray-300` | Direct Tailwind |

### ⚠️ Alerts

| Bootstrap | Tailwind | Component CSS |
|-----------|----------|---------------|
| `alert alert-success` | `alert alert-success` | ✅ Classe préservée |
| `alert alert-danger` | `alert alert-danger` | ✅ Classe préservée |
| `alert alert-warning` | `alert alert-warning` | ✅ Classe préservée |
| `alert alert-info` | `alert alert-info` | ✅ Classe préservée |

### 📏 Spacing (Margin & Padding)

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `m-0` | `m-0` | Margin 0 |
| `m-1` | `m-1` (0.25rem) | Margin 0.25rem |
| `m-2` | `m-2` (0.5rem) | Margin 0.5rem |
| `m-3` | `m-3` (0.75rem) | Margin 0.75rem |
| `m-4` | `m-4` (1rem) | Margin 1rem |
| `m-5` | `m-6` (1.5rem) | **Attention: différence** |
| `mt-3` | `mt-3` | Margin top |
| `mb-4` | `mb-4` | Margin bottom |
| `mx-auto` | `mx-auto` | Centrer horizontalement |
| `p-3` | `p-3` | Padding |
| `px-4` | `px-4` | Padding horizontal |
| `py-2` | `py-2` | Padding vertical |

### 🎭 Text & Typography

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `text-start` | `text-left` | Texte aligné à gauche |
| `text-center` | `text-center` | Texte centré |
| `text-end` | `text-right` | Texte aligné à droite |
| `text-primary` | `text-blue-600` | Couleur primaire |
| `text-success` | `text-green-600` | Couleur succès |
| `text-danger` | `text-red-600` | Couleur danger |
| `text-muted` | `text-gray-500` | Texte atténué |
| `fw-bold` | `font-bold` | Texte gras |
| `fw-normal` | `font-normal` | Poids normal |
| `fs-1` | `text-5xl` | Grande taille |
| `fs-6` | `text-base` | Taille de base |
| `small` | `text-sm` | Petit texte |

### 🎨 Background Colors

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `bg-primary` | `bg-blue-600` | Fond primaire |
| `bg-secondary` | `bg-gray-600` | Fond secondaire |
| `bg-success` | `bg-green-600` | Fond succès |
| `bg-danger` | `bg-red-600` | Fond danger |
| `bg-warning` | `bg-yellow-500` | Fond attention |
| `bg-light` | `bg-gray-100` | Fond clair |
| `bg-dark` | `bg-gray-800` | Fond sombre |
| `bg-white` | `bg-white` | Fond blanc |

### 🔲 Borders

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `border` | `border` | Bordure par défaut |
| `border-0` | `border-0` | Pas de bordure |
| `border-top` | `border-t` | Bordure haut |
| `border-bottom` | `border-b` | Bordure bas |
| `border-primary` | `border-blue-600` | Bordure colorée |
| `rounded` | `rounded-lg` | Coins arrondis |
| `rounded-circle` | `rounded-full` | Cercle parfait |
| `rounded-pill` | `rounded-full` | Pilule |

### 📐 Sizing

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `w-25` | `w-1/4` | Largeur 25% |
| `w-50` | `w-1/2` | Largeur 50% |
| `w-75` | `w-3/4` | Largeur 75% |
| `w-100` | `w-full` | Largeur 100% |
| `h-100` | `h-full` | Hauteur 100% |
| `vw-100` | `w-screen` | Largeur viewport |
| `vh-100` | `h-screen` | Hauteur viewport |

### 🔄 Flexbox

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `d-flex` | `flex` | Display flex |
| `flex-row` | `flex-row` | Direction ligne |
| `flex-column` | `flex-col` | Direction colonne |
| `justify-content-start` | `justify-start` | Alignement début |
| `justify-content-center` | `justify-center` | Centrer |
| `justify-content-end` | `justify-end` | Alignement fin |
| `justify-content-between` | `justify-between` | Espace entre |
| `align-items-start` | `items-start` | Alignement haut |
| `align-items-center` | `items-center` | Centrer verticalement |
| `align-items-end` | `items-end` | Alignement bas |
| `flex-wrap` | `flex-wrap` | Retour à la ligne |

### 🎯 Position

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `position-relative` | `relative` | Position relative |
| `position-absolute` | `absolute` | Position absolue |
| `position-fixed` | `fixed` | Position fixe |
| `position-sticky` | `sticky` | Position sticky |
| `top-0` | `top-0` | Haut 0 |
| `bottom-0` | `bottom-0` | Bas 0 |
| `start-0` | `left-0` | Gauche 0 |
| `end-0` | `right-0` | Droite 0 |

### 👁️ Visibility

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `visible` | `visible` | Visible |
| `invisible` | `invisible` | Invisible (prend de l'espace) |
| `d-none` | `hidden` | Masqué |
| `d-sm-block` | `sm:block` | Visible sur petit écran |
| `d-md-none` | `md:hidden` | Masqué sur écran moyen |

### 🌑 Dark Mode

| Bootstrap | Tailwind | Notes |
|-----------|----------|-------|
| `[data-bs-theme="dark"]` | `dark:` prefix | Ex: `dark:bg-gray-800` |

### 📱 Responsive Breakpoints

| Bootstrap | Tailwind | Taille |
|-----------|----------|--------|
| `col-sm-*` | `sm:*` | ≥640px |
| `col-md-*` | `md:*` | ≥768px |
| `col-lg-*` | `lg:*` | ≥1024px |
| `col-xl-*` | `xl:*` | ≥1280px |
| `col-xxl-*` | `2xl:*` | ≥1536px |

---

## 🚀 Stratégie de migration

### ✅ Phase 1 : Utiliser les classes préservées (ACTUEL)
Les composants principaux (btn, card, form-control, etc.) conservent leurs noms Bootstrap dans le layer `@components` de Tailwind.

**Avantage :** Migration transparente, pas de modification de code immédiate.

### 🔄 Phase 2 : Migration progressive par composant
Migrer un composant à la fois vers Tailwind pur :

```html
<!-- AVANT (Bootstrap) -->
<div class="card">
  <div class="card-header">Titre</div>
  <div class="card-body">Contenu</div>
</div>

<!-- APRÈS (Tailwind pur) -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
  <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 font-semibold">
    Titre
  </div>
  <div class="px-6 py-4">Contenu</div>
</div>
```

### 🎯 Phase 3 : Optimisation finale
Supprimer les classes Bootstrap du layer `@components` une fois tous les composants migrés.

---

## 💡 Bonnes pratiques

1. **Toujours utiliser le préfixe `dark:`** pour le mode sombre
2. **Privilégier les classes utilitaires** plutôt que les classes custom
3. **Utiliser `@apply`** uniquement pour les composants réutilisés 10+ fois
4. **Documenter** les patterns spécifiques au projet
5. **Tester** sur tous les navigateurs après chaque migration

---

## 🔗 Ressources

- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Tailwind UI Components](https://tailwindui.com)
- [Flowbite Components](https://flowbite.com) - Composants Tailwind pré-faits
- [HeadlessUI](https://headlessui.com) - Composants accessibles pour Alpine.js
