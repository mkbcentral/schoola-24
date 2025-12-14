# 🏫 Système de Gestion des Écoles

## Vue d'ensemble

Ce module permet aux utilisateurs avec le rôle **APP_ADMIN** de gérer l'ensemble des écoles dans l'application. Les fonctionnalités incluent :

- ✅ Créer de nouvelles écoles
- ✅ Modifier les informations des écoles
- ✅ Activer/Désactiver des écoles
- ✅ Créer automatiquement un utilisateur ADMIN_SCHOOL pour chaque nouvelle école
- ✅ Gérer les utilisateurs de chaque école
- ✅ Réinitialiser les mots de passe des utilisateurs

---

## 📁 Architecture

### Structure des fichiers

```
app/
├── Actions/School/               # Actions métier
│   ├── CreateSchoolAction.php
│   ├── UpdateSchoolAction.php
│   ├── DeleteSchoolAction.php
│   └── CreateSchoolUserAction.php
│
├── DTOs/School/                  # Data Transfer Objects
│   ├── CreateSchoolDTO.php
│   └── UpdateSchoolDTO.php
│
├── Services/
│   └── SchoolManagementService.php
│
├── Repositories/
│   └── SchoolRepository.php
│
├── Policies/
│   └── SchoolPolicy.php
│
└── Livewire/Application/Admin/School/
    ├── SchoolListPage.php
    ├── CreateSchoolPage.php
    ├── EditSchoolPage.php
    └── SchoolUsersPage.php
```

---

## 🔒 Permissions et Autorisations

### Rôles autorisés

- **APP_ADMIN** : Accès complet à la gestion des écoles
- **ROOT** : Accès complet + suppression des écoles

### Matrice des permissions

| Action | APP_ADMIN | ROOT |
|--------|-----------|------|
| Voir la liste des écoles | ✅ | ✅ |
| Créer une école | ✅ | ✅ |
| Modifier une école | ✅ | ✅ |
| Supprimer une école | ❌ | ✅ |
| Gérer les utilisateurs | ✅ | ✅ |
| Activer/Désactiver école | ✅ | ✅ |

---

## 🚀 Utilisation

### 1. Créer une école

**Route** : `/administration/school-management/create`

**Processus** :
1. Remplir les informations de l'école (nom, type, email, téléphone)
2. Renseigner les informations de l'administrateur par défaut
3. Soumettre le formulaire
4. Un mot de passe temporaire est généré automatiquement
5. L'administrateur reçoit un email avec ses identifiants

**Code exemple** :
```php
$dto = CreateSchoolDTO::fromArray([
    'name' => 'École Primaire XYZ',
    'type' => 'Primaire',
    'email' => 'contact@ecole-xyz.cd',
    'phone' => '+243 XXX XXX XXX',
    'admin_name' => 'Jean Dupont',
    'admin_username' => 'jean.dupont',
    'admin_email' => 'jean@ecole-xyz.cd',
]);

$action = app(CreateSchoolAction::class);
$result = $action->execute($dto);
```

### 2. Gérer les utilisateurs d'une école

**Route** : `/administration/school-management/{schoolId}/users`

**Actions disponibles** :
- ➕ Créer un nouvel utilisateur
- 🔄 Activer/Désactiver un utilisateur
- 🔑 Réinitialiser le mot de passe
- 🗑️ Supprimer un utilisateur

### 3. Modifier une école

**Route** : `/administration/school-management/{schoolId}/edit`

Permet de modifier :
- Nom de l'école
- Type
- Email et téléphone
- Logo
- Statut (actif/inactif)

---

## 🔧 Configuration

### Enregistrer la Policy

Dans `app/Providers/AuthServiceProvider.php` :

```php
use App\Models\School;
use App\Policies\SchoolPolicy;

protected $policies = [
    School::class => SchoolPolicy::class,
];
```

### Service Provider

Le `SchoolManagementService` et `SchoolRepository` sont injectés automatiquement via le conteneur Laravel.

---

## 📊 Base de données

### Migrations nécessaires

Si les colonnes suivantes n'existent pas, ajoutez-les :

```php
Schema::table('schools', function (Blueprint $table) {
    $table->boolean('is_active')->default(true)->after('school_status');
    $table->timestamp('subscription_start')->nullable()->after('is_active');
    $table->timestamp('subscription_end')->nullable()->after('subscription_start');
    $table->json('settings')->nullable()->after('subscription_end');
});
```

---

## 🧪 Tests

### Tester la création d'une école

```php
// Test unitaire
$service = app(SchoolManagementService::class);
$dto = CreateSchoolDTO::fromArray([...]);
$result = $service->createSchoolWithAdmin($dto);

$this->assertInstanceOf(School::class, $result['school']);
$this->assertInstanceOf(User::class, $result['admin']);
$this->assertNotEmpty($result['temp_password']);
```

---

## 📧 Notifications Email

### Configuration à implémenter

Les méthodes suivantes sont préparées mais nécessitent l'implémentation des Mailables :

```php
// Dans SchoolManagementService.php
private function sendWelcomeEmail(User $user, string $password, School $school)
{
    // TODO: Créer la Mailable WelcomeSchoolAdmin
    Mail::to($user->email)->send(new WelcomeSchoolAdmin($user, $password, $school));
}

private function sendPasswordResetEmail(User $user, string $newPassword)
{
    // TODO: Créer la Mailable PasswordResetNotification
    Mail::to($user->email)->send(new PasswordResetNotification($user, $newPassword));
}
```

### Créer les Mailables

```bash
php artisan make:mail WelcomeSchoolAdmin
php artisan make:mail PasswordResetNotification
```

---

## 🎨 Interface utilisateur

### Pages disponibles

1. **Liste des écoles** : Vue d'ensemble avec statistiques
2. **Créer une école** : Formulaire complet avec validation
3. **Modifier une école** : Mise à jour des informations
4. **Utilisateurs de l'école** : Gestion complète des utilisateurs

### Composants Blade réutilisables

- `x-navigation.bread-crumb`
- `x-form.search-input`
- `x-form.app-button`
- `x-errors.data-empty`

---

## 🔐 Sécurité

### Validations

- Email unique pour les écoles
- Username et email uniques pour les utilisateurs
- Génération de mots de passe sécurisés (12 caractères minimum)
- Protection contre la suppression du dernier ADMIN_SCHOOL

### Middleware

Les routes sont protégées par :
```php
Route::middleware(['can:viewAny,App\Models\School'])
```

---

## 📝 Notes importantes

1. **Mot de passe temporaire** : Toujours affiché après création (à noter impérativement)
2. **Dernier administrateur** : Impossible de supprimer le dernier ADMIN_SCHOOL d'une école
3. **Email requis** : Les notifications nécessitent une configuration SMTP valide
4. **Logo** : Stocké dans `storage/app/public/schools/logos`

---

## 🆘 Dépannage

### Erreur : "Le rôle ADMIN_SCHOOL n'existe pas"

Vérifiez que le rôle existe dans la table `roles` :
```sql
SELECT * FROM roles WHERE name = 'ADMIN_SCHOOL';
```

Si absent, créez-le :
```php
Role::create(['name' => 'ADMIN_SCHOOL', 'is_for_school' => true]);
```

### Erreur : "Unauthorized"

Vérifiez que l'utilisateur connecté a le rôle APP_ADMIN ou ROOT :
```php
Auth::user()->role->name === 'APP_ADMIN'
```

---

## 🔄 Améliorations futures

- [ ] Système d'abonnement avec dates de validité
- [ ] Tableau de bord statistiques par école
- [ ] Export CSV/Excel des écoles
- [ ] Historique des modifications
- [ ] Notifications push pour les nouveaux utilisateurs
- [ ] Authentification à deux facteurs pour APP_ADMIN

---

## 👥 Auteur

Implémenté par GitHub Copilot  
Date : Décembre 2024  
Version : 1.0
