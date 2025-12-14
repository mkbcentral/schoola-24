# Module de Paramétrage des Dépenses

## 📋 Description

Ce module permet de gérer les **Catégories de Dépenses** et les **Sources d'Autres Dépenses** avec une interface élégante, intuitive et minimaliste. Il suit l'architecture service-action-repository du projet.

## 🎯 Fonctionnalités

### Catégories de Dépenses

-   ✅ Créer une nouvelle catégorie
-   ✅ Modifier une catégorie existante
-   ✅ Supprimer une catégorie (avec vérification des dépenses associées)
-   ✅ Afficher le nombre de dépenses par catégorie

### Sources d'Autres Dépenses

-   ✅ Créer une nouvelle source
-   ✅ Modifier une source existante
-   ✅ Supprimer une source (avec vérification des dépenses associées)
-   ✅ Afficher le nombre de dépenses par source

## 🏗️ Architecture

### Services

```
app/Services/Expense/
├── CategoryExpenseServiceInterface.php
├── CategoryExpenseService.php
├── OtherSourceExpenseServiceInterface.php
└── OtherSourceExpenseService.php
```

**Responsabilités :**

-   Gestion CRUD des catégories et sources
-   Filtrage par école
-   Vérification des dépenses associées

### Actions

```
app/Actions/Expense/
├── CreateCategoryExpenseAction.php
├── UpdateCategoryExpenseAction.php
├── DeleteCategoryExpenseAction.php
├── CreateOtherSourceExpenseAction.php
├── UpdateOtherSourceExpenseAction.php
└── DeleteOtherSourceExpenseAction.php
```

**Responsabilités :**

-   Exécution des opérations CRUD
-   Gestion des erreurs
-   Validation métier (empêcher la suppression si des dépenses existent)

### Composants Livewire

```
app/Livewire/Application/Finance/Expense/Settings/
├── ExpenseSettingsPage.php (Page principale)
├── CategoryExpenseFormModal.php (Modal catégories)
└── OtherSourceExpenseFormModal.php (Modal sources)
```

### Formulaires Livewire

```
app/Livewire/Forms/
├── CategoryExpenseForm.php
└── OtherSourceExpenseForm.php
```

**Validation avec #[Validate] :**

```php
#[Validate('required|string|min:3|max:255', message: 'Le nom est obligatoire (3-255 caractères)')]
public string $name = '';
```

### Composants Blade Réutilisables

```
resources/views/components/expense-settings/
├── type-switcher.blade.php (Onglets + Boutons d'ajout)
├── category-card.blade.php (Carte catégorie)
└── source-card.blade.php (Carte source)
```

### Vues

```
resources/views/livewire/application/finance/expense/settings/
├── expense-settings-page.blade.php
├── category-expense-form-modal.blade.php
└── other-source-expense-form-modal.blade.php
```

## 🚀 Utilisation

### Accéder à la page

```
URL: /expense/settings
Route: expense.settings
```

### Interface

#### 1. **Type Switcher**

-   Boutons pour basculer entre "Catégories" et "Sources"
-   Bouton "Nouvelle Catégorie" ou "Nouvelle Source" selon l'onglet actif

#### 2. **Grille de Cartes**

-   Affichage en grille responsive (3 colonnes sur desktop)
-   Effet hover avec élévation
-   Menu dropdown pour éditer/supprimer
-   Statistiques pour chaque élément

#### 3. **Modals**

-   Modal Bootstrap pour créer/éditer
-   Validation en temps réel
-   Fermeture automatique après succès
-   Toast notifications

## 🎨 Design

### Caractéristiques UI

-   ✨ **Minimaliste** : Interface épurée sans éléments superflus
-   🎯 **Intuitive** : Actions claires et accessibles
-   💚 **Élégante** : Transitions fluides et design moderne
-   📱 **Responsive** : S'adapte à tous les écrans

### Effets Visuels

```css
.hover-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
```

### Couleurs

-   **Catégories** : Bleu primaire (#0d6efd)
-   **Sources** : Vert success (#198754)
-   **Suppression** : Rouge danger (#dc3545)

## 🔔 Événements Livewire

### Dispatch Events

```php
// Ouverture des modals
$this->dispatch('open-category-modal');
$this->dispatch('open-edit-category-modal', categoryId: $id);
$this->dispatch('open-source-modal');
$this->dispatch('open-edit-source-modal', sourceId: $id);

// Fermeture des modals
$this->dispatch('close-category-modal');
$this->dispatch('close-source-modal');

// Notifications
$this->dispatch('added'); // Toast success
$this->dispatch('error', message: $message); // Toast error
$this->dispatch('category-saved'); // Refresh après sauvegarde
$this->dispatch('source-saved'); // Refresh après sauvegarde

// Suppressions
$this->dispatch('confirm-delete-category', [...]);
$this->dispatch('confirm-delete-source', [...]);
$this->dispatch('category-deleted', message: $message);
$this->dispatch('source-deleted', message: $message);
$this->dispatch('delete-failed', message: $message);
```

### Listeners

```php
#[On('category-saved')]
#[On('source-saved')]
public function refresh(): void
{
    // Rafraîchit la liste
}
```

## 🛡️ Sécurité

### Protection contre la suppression

```php
// Vérifie si la catégorie a des dépenses
if ($this->categoryExpenseService->hasExpenses($categoryExpense)) {
    return [
        'success' => false,
        'message' => 'Impossible de supprimer une catégorie avec des dépenses associées',
    ];
}
```

### Confirmation SweetAlert2

-   Dialog de confirmation avant suppression
-   Affichage du nom de l'élément
-   Boutons d'action clairement identifiés
-   Animations et feedback visuel

## 📊 Statistiques

### Carte Catégorie

-   **Dép. sur Frais** : Nombre d'ExpenseFee
-   **Autres Dép.** : Nombre d'OtherExpense

### Carte Source

-   **Nombre de dépenses** : Total des OtherExpense

## 🔗 Relations avec les Modèles

### CategoryExpense

```php
// Relations
public function expenseFee(): HasMany
public function otherExpenses(): HasMany
public function school(): BelongsTo
```

### OtherSourceExpense

```php
// Relations
public function otherExpenses(): HasMany
public function school(): BelongsTo
```

## 🧪 Tests

Pour tester le module :

```bash
# Accéder à la page
http://localhost/expense/settings

# Créer une catégorie
1. Cliquer sur "Nouvelle Catégorie"
2. Saisir le nom (min 3 caractères)
3. Cliquer sur "Créer"

# Modifier une catégorie
1. Cliquer sur le menu (⋮) de la carte
2. Sélectionner "Modifier"
3. Modifier le nom
4. Cliquer sur "Modifier"

# Supprimer une catégorie
1. Cliquer sur le menu (⋮) de la carte
2. Sélectionner "Supprimer"
3. Confirmer dans le dialog SweetAlert2

# Même processus pour les Sources
```

## 📝 Notes Techniques

### Injection de Dépendances

Les services et actions sont injectés via le constructeur :

```php
public function __construct(
    private CategoryExpenseServiceInterface $categoryExpenseService,
    private CreateCategoryExpenseAction $createCategoryExpenseAction
) {}
```

### Service Provider

Les services sont enregistrés dans `AppServiceProvider` :

```php
$this->app->singleton(CategoryExpenseServiceInterface::class, CategoryExpenseService::class);
$this->app->singleton(OtherSourceExpenseServiceInterface::class, OtherSourceExpenseService::class);
```

## 🎯 Prochaines Améliorations Possibles

-   [ ] Recherche/filtrage des catégories et sources
-   [ ] Tri alphabétique ou par date
-   [ ] Pagination si le nombre d'éléments augmente
-   [ ] Export CSV/Excel des catégories et sources
-   [ ] Import en masse
-   [ ] Historique des modifications
-   [ ] Archivage au lieu de suppression
-   [ ] Icônes personnalisées pour chaque catégorie

## 📚 Référence Rapide

| Action              | Méthode Livewire             | Event                      |
| ------------------- | ---------------------------- | -------------------------- |
| Créer catégorie     | `openCreateCategoryModal()`  | `open-category-modal`      |
| Éditer catégorie    | `openEditCategoryModal($id)` | `open-edit-category-modal` |
| Supprimer catégorie | `deleteCategory($id)`        | `category-deleted`         |
| Créer source        | `openCreateSourceModal()`    | `open-source-modal`        |
| Éditer source       | `openEditSourceModal($id)`   | `open-edit-source-modal`   |
| Supprimer source    | `deleteSource($id)`          | `source-deleted`           |

---

**Développé avec ❤️ en suivant les principes SOLID et l'architecture service-action du projet Schoola**
