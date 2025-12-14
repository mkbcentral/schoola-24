# Guide de Migration - Module School vers V2

## 📋 Aperçu

Ce guide vous aidera à migrer du module School actuel vers la nouvelle architecture V2.

## 🔄 Comparaison

### Ancien module
```
app/Livewire/Application/Admin/School/
└── SchoolListPage.php

resources/views/livewire/application/admin/school/
└── school-list-page.blade.php
```

### Nouveau module V2
```
app/Livewire/Application/V2/School/
├── SchoolManagementPage.php
├── Form/SchoolFormOffcanvas.php
└── Widget/SchoolStatsCard.php

app/Livewire/Forms/
└── SchoolForm.php (amélioré)

resources/views/livewire/application/v2/school/
├── school-management-page.blade.php
├── form/school-form-offcanvas.blade.php
└── widget/school-stats-card.blade.php
```

## 📝 Étapes de migration

### 1. Vérifier les dépendances

Assurez-vous que ces composants blade existent :
- `x-v2.breadcrumb`
- `x-v2.mini-stat-card`
- `x-v2.action-dropdown`
- `x-v2.empty-state`

Si manquants, ils peuvent être trouvés dans `resources/views/components/v2/`.

### 2. Mettre à jour les routes

**Option A : Route parallèle (recommandé pour transition)**
```php
// web.php ou admin.php
Route::get('/v2/schools', SchoolManagementPage::class)
    ->name('v2.schools.index')
    ->middleware(['auth', 'can:viewAny,App\Models\School']);

// Garder l'ancienne route temporairement
Route::get('/admin/schools/list', SchoolListPage::class)
    ->name('admin.schools.list.old');
```

**Option B : Remplacement direct**
```php
// Remplacer directement l'ancienne route
Route::get('/admin/schools', SchoolManagementPage::class)
    ->name('admin.schools.index')
    ->middleware(['auth', 'can:viewAny,App\Models\School']);
```

### 3. Mettre à jour les liens dans les menus

**Ancien**
```blade
<a href="{{ route('admin.schools.list') }}">Écoles</a>
```

**Nouveau**
```blade
<a href="{{ route('v2.schools.index') }}">Écoles</a>
<!-- ou -->
<a href="{{ route('admin.schools.index') }}">Écoles</a>
```

### 4. Mettre à jour les redirections

Si vous avez des redirections dans d'autres contrôleurs :

**Ancien**
```php
return redirect()->route('admin.schools.list');
```

**Nouveau**
```php
return redirect()->route('v2.schools.index');
```

### 5. Vérifier les permissions

Les mêmes policies sont utilisées :
- `viewAny` - Voir la liste
- `create` - Créer
- `update` - Modifier
- `delete` - Supprimer
- `toggleStatus` - Changer le statut
- `manageUsers` - Gérer les utilisateurs

Aucune modification nécessaire dans `SchoolPolicy`.

### 6. Tests de fonctionnalités

Vérifier que tout fonctionne :

- [ ] Affichage de la liste des écoles
- [ ] Recherche d'écoles
- [ ] Tri des colonnes
- [ ] Création d'une école
- [ ] Modification d'une école
- [ ] Suppression d'une école
- [ ] Toggle du statut
- [ ] Affichage des statistiques
- [ ] Accès aux utilisateurs
- [ ] Messages de succès/erreur
- [ ] Validation des formulaires
- [ ] Permissions

## 🎯 Différences clés

### Architecture

| Aspect | Ancien | Nouveau |
|--------|--------|---------|
| Injection de dépendances | Dans `render()` | Via `boot()` |
| Validation | Inline dans composant | Dans `SchoolForm` |
| Interface | AdminLTE | Bootstrap 5 moderne |
| Formulaire | Page séparée | Offcanvas intégré |
| Composants | Peu réutilisables | Composants V2 réutilisables |

### Fonctionnalités ajoutées

1. **Interface moderne**
   - Design cohérent avec les autres modules V2
   - Offcanvas pour création/édition
   - Meilleure UX

2. **Validation améliorée**
   - Validation centralisée dans `SchoolForm`
   - Messages d'erreur personnalisés
   - Règles de validation plus strictes

3. **Composants réutilisables**
   - Mini stat cards
   - Action dropdown
   - Empty state
   - Breadcrumb avec actions

4. **Performance**
   - Injection de services via boot()
   - Lazy loading des composants
   - Pagination optimisée

## ⚠️ Points d'attention

### 1. Redirections externes

Si `createSchool()` et `editSchool()` redirigent vers d'autres pages dans l'ancien module, le nouveau module utilise un Offcanvas. Il faudra peut-être adapter :

```php
// Si vous voulez garder les redirections :
public function createSchool()
{
    return redirect()->route('admin.schools.create');
}

// Sinon, utiliser l'offcanvas (recommandé) :
public function openCreateSchool()
{
    $this->dispatch('open-create-school');
}
```

### 2. Événements Livewire

Nouveaux événements à intégrer si vous utilisez des listeners :
- `school-saved`
- `success-message`
- `error-message`

### 3. CSS personnalisé

Si vous avez du CSS personnalisé pour l'ancien module, vérifiez qu'il fonctionne avec le nouveau design ou créez une nouvelle feuille de style.

## 🔧 Rollback (si nécessaire)

Si vous devez revenir en arrière :

1. **Restaurer l'ancienne route**
```php
Route::get('/admin/schools', SchoolListPage::class)
    ->name('admin.schools.index');
```

2. **Supprimer la nouvelle route**
```php
// Commenter ou supprimer
// Route::get('/v2/schools', SchoolManagementPage::class)...
```

3. **Mettre à jour les menus**
Restaurer les liens vers `admin.schools.list` ou `admin.schools.index`.

## 📊 Checklist de migration

- [ ] Sauvegarder la base de données
- [ ] Tester en environnement de développement
- [ ] Vérifier tous les composants blade V2
- [ ] Mettre à jour les routes
- [ ] Mettre à jour les menus
- [ ] Tester toutes les fonctionnalités
- [ ] Vérifier les permissions
- [ ] Tester sur différents navigateurs
- [ ] Former les utilisateurs si nécessaire
- [ ] Déployer en production
- [ ] Monitorer les erreurs

## 💡 Conseils

1. **Migration progressive**
   - Commencez avec la route parallèle (`/v2/schools`)
   - Testez pendant quelques jours
   - Puis remplacez l'ancienne route

2. **Documentation utilisateur**
   - Informez les utilisateurs du nouveau design
   - Créez des captures d'écran si nécessaire

3. **Monitoring**
   - Surveillez les logs après déploiement
   - Collectez les retours utilisateurs

## 🆘 Support

En cas de problème :
1. Vérifier les logs Laravel (`storage/logs/laravel.log`)
2. Vérifier la console du navigateur (F12)
3. Consulter la documentation Livewire
4. Tester les permissions utilisateur

## 📚 Ressources

- [SCHOOL_MODULE_V2.md](./SCHOOL_MODULE_V2.md) - Documentation complète
- [Livewire Documentation](https://livewire.laravel.com)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
