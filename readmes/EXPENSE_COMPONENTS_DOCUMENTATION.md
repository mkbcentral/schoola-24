# Composants Réutilisables - Gestion des Dépenses

Ce document décrit les composants Blade réutilisables créés pour la page de gestion des dépenses.

## 📦 Composants Créés

### 1. `<x-expense.type-switcher>`

**Localisation:** `resources/views/components/expense/type-switcher.blade.php`

**Description:** Boutons de basculement entre "Dépenses sur Frais" et "Autres Dépenses" + bouton "Nouvelle Dépense".

**Props:**

-   `expenseType` (string, default: 'fee') - Type de dépense actuel ('fee' ou 'other')

**Utilisation:**

```blade
<x-expense.type-switcher :expenseType="$expenseType" />
```

---

### 2. `<x-expense.statistics-cards>`

**Localisation:** `resources/views/components/expense/statistics-cards.blade.php`

**Description:** Quatre cartes de statistiques (Total USD, Total CDF, Total converti, Nombre).

**Props:**

-   `statistics` (array) - Tableau contenant:
    -   `totalUSD` (float) - Total en dollars
    -   `totalCDF` (float) - Total en francs congolais
    -   `totalUSDConverted` (float) - Total converti en dollars
    -   `count` (int) - Nombre de dépenses

**Utilisation:**

```blade
<x-expense.statistics-cards :statistics="$statistics" />
```

**Features:**

-   Spinners automatiques pendant le chargement
-   Support du mode sombre
-   Icônes Bootstrap Icons
-   Responsive (4 colonnes → empilées sur mobile)

---

### 3. `<x-expense.quick-filters>`

**Localisation:** `resources/views/components/expense/quick-filters.blade.php`

**Description:** Filtres rapides pour date, période, devise et catégorie.

**Props:**

-   `date` (string|null) - Date spécifique sélectionnée
-   `filterPeriod` (string, default: '') - Période prédéfinie
-   `filterCurrency` (string, default: '') - Devise sélectionnée
-   `filterCategoryExpense` (string, default: '0') - ID catégorie sélectionnée
-   `categoryExpenses` (Collection) - Liste des catégories disponibles

**Utilisation:**

```blade
<x-expense.quick-filters
    :date="$date"
    :filterPeriod="$filterPeriod"
    :filterCurrency="$filterCurrency"
    :filterCategoryExpense="$filterCategoryExpense"
    :categoryExpenses="$categoryExpenses" />
```

**Features:**

-   Filtres avec `wire:model.live` pour réactivité immédiate
-   Bouton "Plus de filtres" qui ouvre l'offcanvas
-   Layout responsive avec Bootstrap grid

---

### 4. `<x-expense.advanced-filters>`

**Localisation:** `resources/views/components/expense/advanced-filters.blade.php`

**Description:** Offcanvas Bootstrap avec filtres avancés (plages de dates, mois, type de frais/source).

**Props:**

-   `expenseType` (string, default: 'fee') - Type de dépense ('fee' ou 'other')
-   `dateRange` (string, default: '') - Plage de dates prédéfinie
-   `dateDebut` (string|null) - Date de début personnalisée
-   `dateFin` (string|null) - Date de fin personnalisée
-   `filterMonth` (string, default: '') - Mois spécifique
-   `filterCategoryFee` (string, default: '0') - ID type de frais
-   `filterOtherSource` (string, default: '0') - ID source de dépense
-   `categoryFees` (Collection) - Liste des types de frais
-   `otherSources` (Collection) - Liste des sources de dépenses

**Utilisation:**

```blade
<x-expense.advanced-filters
    :expenseType="$expenseType"
    :dateRange="$dateRange"
    :dateDebut="$dateDebut"
    :dateFin="$dateFin"
    :filterMonth="$filterMonth"
    :filterCategoryFee="$filterCategoryFee"
    :filterOtherSource="$filterOtherSource"
    :categoryFees="$categoryFees"
    :otherSources="$otherSources" />
```

**Features:**

-   Offcanvas Bootstrap natif avec `id="offcanvasFilters"`
-   Plages de dates prédéfinies (aujourd'hui, hier, cette semaine, etc.)
-   Dates personnalisées avec désactivation automatique si plage prédéfinie
-   Filtres conditionnels selon le type de dépense
-   Bouton "Réinitialiser tous les filtres"
-   Support `wire:ignore.self` pour éviter les problèmes Livewire

---

### 5. `<x-expense.table>`

**Localisation:** `resources/views/components/expense/table.blade.php`

**Description:** Tableau des dépenses avec pagination, actions et état vide.

**Props:**

-   `expenses` (LengthAwarePaginator) - Collection paginée des dépenses
-   `expenseType` (string, default: 'fee') - Type de dépense ('fee' ou 'other')

**Utilisation:**

```blade
<x-expense.table :expenses="$expenses" :expenseType="$expenseType" />
```

**Features:**

-   Colonnes conditionnelles selon le type de dépense
-   Formatage automatique des montants (USD 2 décimales, CDF 0 décimales)
-   Badges colorés pour catégories et types
-   Boutons d'édition et suppression avec spinners
-   Pagination automatique avec liens Laravel
-   État vide avec icône et message
-   Spinner de chargement pendant la pagination
-   Support du mode sombre

**Colonnes:**

-   Date (format d/m/Y)
-   Description
-   Mois (badge)
-   Catégorie (badge)
-   Type Frais / Source (conditionnel)
-   USD ($) - aligné à droite
-   CDF (FC) - aligné à droite
-   Actions (éditer, supprimer)

---

## 🎨 Avantages de la Refactorisation

### Avant (fichier monolithique)

-   **428 lignes** de code Blade dans `expense-management-page.blade.php`
-   HTML et logique mélangés
-   Difficile à maintenir et tester
-   Code dupliqué

### Après (composants modulaires)

-   **~50 lignes** dans le fichier principal
-   **5 composants réutilisables** bien séparés
-   Chaque composant a une responsabilité unique
-   Facilement testable et maintenable
-   Réutilisable dans d'autres pages

### Réduction de complexité

```
Avant: 428 lignes → Après: ~50 lignes
Gain: 88% de réduction de code dans le fichier principal
```

---

## 🔧 Réutilisation dans d'autres contextes

Ces composants peuvent être réutilisés pour d'autres modules financiers :

### Exemple : Page des Recettes

```blade
<!-- Adaptez simplement les props -->
<x-expense.type-switcher :expenseType="$revenueType" />
<x-expense.statistics-cards :statistics="$revenueStats" />
<x-expense.table :expenses="$revenues" :expenseType="$revenueType" />
```

### Exemple : Rapport financier

```blade
<!-- Utilisez seulement les statistiques -->
<x-expense.statistics-cards :statistics="$monthlyStats" />
```

---

## 🎯 Bonnes Pratiques Appliquées

1. **Single Responsibility Principle**

    - Chaque composant a une seule responsabilité
    - Facile à tester et maintenir

2. **Props Typées**

    - Documentation claire des props attendus
    - Valeurs par défaut définies

3. **Reactive avec Livewire**

    - `wire:model.live` pour réactivité immédiate
    - `wire:loading` pour les états de chargement
    - `wire:ignore.self` pour les offcanvas

4. **Accessibilité**

    - Attributs ARIA corrects
    - Labels descriptifs
    - Support du mode sombre

5. **Performance**
    - Spinners de chargement pour le feedback utilisateur
    - Pagination pour grandes listes
    - Lazy loading des filtres avancés (offcanvas)

---

## 📝 Maintenance

Pour modifier un composant :

1. Localiser le fichier dans `resources/views/components/expense/`
2. Modifier le template Blade
3. Vérifier l'utilisation dans `expense-management-page.blade.php`
4. Tester les changements

Pour créer un nouveau composant similaire :

1. Copier un composant existant
2. Renommer et adapter les props
3. Utiliser dans votre vue avec `<x-expense.votre-composant />`

---

## 🚀 Prochaines Améliorations Possibles

-   [ ] Créer des tests unitaires pour chaque composant
-   [ ] Ajouter des variantes de composants (compact, détaillé)
-   [ ] Créer une classe PHP pour chaque composant avec logique métier
-   [ ] Ajouter des slots pour personnalisation avancée
-   [ ] Créer un composant `<x-expense.export-buttons>` pour l'export PDF/Excel

---

**Date de création:** 26 novembre 2025  
**Auteur:** Refactoring automatisé  
**Version:** 1.0
