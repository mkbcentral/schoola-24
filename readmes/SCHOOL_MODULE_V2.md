# Module School - Architecture V2

## 📋 Vue d'ensemble

Le module School a été complètement refactorisé pour suivre l'architecture V2 de l'application, en cohérence avec les modules User, Configuration, Fee et Registration.

## 🗂️ Structure des fichiers

### Composants Livewire
```
app/Livewire/Application/V2/School/
├── SchoolManagementPage.php          # Page principale de gestion
└── Form/
    └── SchoolFormOffcanvas.php       # Formulaire d'édition/création
```

### Forms de validation
```
app/Livewire/Forms/
└── SchoolForm.php                     # Form Livewire avec validation
```

### Vues Blade
```
resources/views/livewire/application/v2/school/
├── school-management-page.blade.php
└── form/
    └── school-form-offcanvas.blade.php
```

## ✨ Fonctionnalités

### SchoolManagementPage
- ✅ Injection de dépendances via `boot()`
- ✅ Liste paginée des écoles avec recherche
- ✅ Statistiques (total, actives, inactives, utilisateurs)
- ✅ Tri des colonnes
- ✅ Actions : Créer, Modifier, Supprimer, Toggle Status, Voir utilisateurs
- ✅ Gestion des permissions (Policies)
- ✅ Messages de succès/erreur

### SchoolForm (Validation)
- ✅ Validation des champs (name, type, phone, email, address)
- ✅ Règles de validation avec unicité
- ✅ Messages d'erreur personnalisés en français
- ✅ Méthodes `store()` et `update()`
- ✅ Méthode `setSchool()` pour édition

### SchoolFormOffcanvas
- ✅ Utilise `SchoolForm` pour la validation
- ✅ Injection de service via `boot()`
- ✅ Gestion des événements Livewire
- ✅ Interface utilisateur moderne (Offcanvas Bootstrap)

## 🎨 Composants réutilisables utilisés

### Composants V2
- `<x-v2.breadcrumb>` - Fil d'ariane avec actions
- `<x-v2.mini-stat-card>` - Cartes de statistiques
- `<x-v2.action-dropdown>` - Menu d'actions
- `<x-v2.empty-state>` - État vide

### Interface utilisateur
- Design moderne avec Bootstrap 5
- Cards avec ombres et bordures arrondies
- Icônes Bootstrap Icons
- Responsive design
- Loading states
- Transitions fluides

## 📊 Données affichées

### Liste des écoles
- Numéro de ligne
- Logo de l'école (si disponible)
- Nom et adresse
- Type d'école (badge)
- Email et téléphone
- Nombre d'utilisateurs
- Statut (actif/inactif)
- Actions contextuelles

### Statistiques
- Total des écoles
- Écoles actives
- Écoles inactives
- Utilisateurs total

## 🔐 Permissions

Le module respecte les policies Laravel :
- `create` - Créer une école
- `update` - Modifier une école
- `delete` - Supprimer une école
- `toggleStatus` - Changer le statut
- `manageUsers` - Voir les utilisateurs

## 🚀 Utilisation

### Route recommandée
```php
Route::get('/schools', SchoolManagementPage::class)
    ->name('admin.schools.index')
    ->middleware(['auth', 'can:viewAny,App\Models\School']);
```

### Migration depuis l'ancien module
L'ancien composant `SchoolListPage` peut être progressivement remplacé par le nouveau `SchoolManagementPage`. Les deux peuvent coexister pendant la transition.

## 🔄 Événements Livewire

### Émis
- `school-saved` - Après création/modification
- `success-message` - Message de succès
- `error-message` - Message d'erreur
- `show-school-offcanvas` - Ouvrir le formulaire
- `hide-school-offcanvas` - Fermer le formulaire

### Écoutés
- `school-saved` - Rafraîchir la liste

## 📝 Validation

### Champs obligatoires
- Nom de l'école (unique)
- Type d'école
- Email (unique, format email)
- Téléphone (min 9 caractères)

### Champs optionnels
- Adresse
- Statut application (défaut: active)
- Statut école (défaut: active)

## 🎯 Avantages de l'architecture V2

1. **Maintenabilité** : Code modulaire et bien organisé
2. **Réutilisabilité** : Composants blade réutilisables
3. **Testabilité** : Services injectés, faciles à mocker
4. **Validation** : Centralisée dans les Forms Livewire
5. **UX moderne** : Interface utilisateur améliorée
6. **Performance** : Lazy loading, pagination optimisée
7. **Accessibilité** : Labels, ARIA, navigation au clavier

## 🔧 Prochaines améliorations possibles

- [ ] Upload de logo d'école
- [ ] Gestion des années scolaires par école
- [ ] Export de la liste (Excel/PDF)
- [ ] Filtres avancés (type, statut, date de création)
- [ ] Vue détaillée d'une école
- [ ] Historique des modifications
- [ ] Notifications par email lors de création

## 📚 Références

- [Documentation Livewire Forms](https://livewire.laravel.com/docs/forms)
- [Bootstrap 5 Offcanvas](https://getbootstrap.com/docs/5.3/components/offcanvas/)
- [Laravel Validation](https://laravel.com/docs/validation)
