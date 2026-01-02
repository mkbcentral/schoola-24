# Module d'Authentification V2

## 📋 Vue d'ensemble

Ce module implémente un système d'authentification complet et sécurisé basé sur Livewire 3 avec les fonctionnalités suivantes :

- ✅ Limitation des tentatives de connexion (3 maximum)
- ✅ Blocage temporaire de 5 minutes après 3 échecs
- ✅ Support username/email comme identifiant
- ✅ Vérification du compte actif
- ✅ Interface élégante et responsive
- ✅ Logs complets de toutes les actions
- ✅ Messages informatifs sur les tentatives restantes

## 🗂️ Structure des fichiers

### DTOs
- `app/DTOs/Auth/LoginDTO.php` - Encapsulation des données de connexion

### Repositories
- `app/Repositories/AuthRepository.php` - Gestion du cache et de la persistance

### Actions
- `app/Actions/Auth/AttemptLoginAction.php` - Logique de tentative de connexion
- `app/Actions/Auth/TrackLoginAttemptAction.php` - Suivi des tentatives

### Services
- `app/Services/AuthenticationService.php` - Orchestration de l'authentification

### Livewire
- `app/Livewire/Application/V2/Auth/Login.php` - Composant Livewire

### Views
- `resources/views/livewire/application/v2/auth/login.blade.php` - Interface utilisateur

### Migrations
- `database/migrations/2024_12_14_000001_add_last_login_fields_to_users_table.php`

## 🚀 Installation

### 1. Exécuter la migration

```bash
php artisan migrate
```

### 2. Ajouter la route

Dans `routes/web.php`, ajoutez :

```php
use App\Livewire\Application\V2\Auth\Login;

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// Pour les routes protégées
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Déconnexion
    Route::post('/logout', function () {
        app(\App\Services\AuthenticationService::class)->logout();
        return redirect()->route('login');
    })->name('logout');
});
```

### 3. Vérifier le layout guest

Assurez-vous d'avoir un layout `layouts.guest` dans `resources/views/layouts/guest.blade.php` :

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Schoola' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    @livewireStyles
</head>
<body>
    {{ $slot }}
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>
</html>
```

## 🎨 Caractéristiques de l'interface

### Design

- **Layout split-screen** : Formulaire à gauche, description à droite
- **Responsive** : S'adapte parfaitement aux mobiles (masque la section droite)
- **Gradient animé** : Fond dégradé élégant
- **Animations fluides** : Transitions CSS3 pour une UX agréable
- **Icons Bootstrap** : Icônes modernes et cohérentes

### Composants du formulaire

1. **Champ Identifiant** : Accepte username ou email avec validation en temps réel
2. **Champ Mot de passe** : Avec toggle pour afficher/masquer
3. **Checkbox "Se souvenir"** : Pour la connexion persistante
4. **Lien mot de passe oublié** : Redirection vers la réinitialisation
5. **Bouton de connexion** : Avec loader pendant le traitement

### Alertes dynamiques

- **Erreurs de validation** : Affichées sous chaque champ
- **Erreurs globales** : Badge alert en haut du formulaire
- **Tentatives restantes** : Info box bleue
- **Compte bloqué** : Alerte orange avec temps restant

## 🔒 Sécurité

### Limitation des tentatives

```php
// Configuration dans AuthRepository
const MAX_ATTEMPTS = 3;           // 3 tentatives maximum
const LOCKOUT_TIME = 300;         // 5 minutes de blocage (en secondes)
const ATTEMPTS_TTL = 900;         // 15 minutes de conservation des tentatives
```

### Système de cache

Les tentatives sont stockées dans le cache Laravel avec :
- Clé unique par identifiant (hashée)
- TTL automatique
- Nettoyage après connexion réussie

### Logs de sécurité

Tous les événements sont loggés :
- Tentatives échouées
- Connexions réussies
- Comptes bloqués
- Réinitialisations

## 💻 Utilisation

### Connexion simple

```php
$authService = app(\App\Services\AuthenticationService::class);

$loginDTO = \App\DTOs\Auth\LoginDTO::fromArray([
    'identifier' => 'john.doe@example.com',
    'password' => 'password123',
    'remember' => true,
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);

$result = $authService->login($loginDTO);

if ($result['success']) {
    // Connexion réussie
    $user = $result['user'];
} else {
    // Afficher le message d'erreur
    echo $result['message'];
}
```

### Vérifier le statut de blocage

```php
$authService = app(\App\Services\AuthenticationService::class);

if ($authService->isLocked('john.doe')) {
    $lockoutTime = $authService->getLockoutTime('john.doe');
    echo "Bloqué pour encore {$lockoutTime} secondes";
}
```

### Déconnexion

```php
$authService = app(\App\Services\AuthenticationService::class);
$authService->logout();
```

## 🧪 Test

### Tester le blocage

1. Essayez de vous connecter 3 fois avec un mauvais mot de passe
2. À la 3ème tentative, vous serez bloqué pour 5 minutes
3. Les tentatives suivantes afficheront le temps restant

### Test de validation

- Champ vide → Message d'erreur
- Email invalide → Accepté (c'est peut-être un username)
- Mot de passe < 4 caractères → Message d'erreur

## 🔧 Personnalisation

### Modifier la limite de tentatives

Dans `app/Repositories/AuthRepository.php` :

```php
private const MAX_ATTEMPTS = 5;        // 5 tentatives au lieu de 3
private const LOCKOUT_TIME = 600;      // 10 minutes au lieu de 5
```

### Personnaliser les messages

Dans `app/Livewire/Application/V2/Auth/Login.php`, méthode `messages()` :

```php
protected function messages(): array
{
    return [
        'identifier.required' => 'Votre message personnalisé',
        // ...
    ];
}
```

### Modifier le design

Éditez `resources/views/livewire/application/v2/auth/login.blade.php` :

- Classes Bootstrap 5
- Variables CSS dans la balise `<style>`
- Icônes Bootstrap Icons

## 📊 Structure des données

### LoginDTO

```php
class LoginDTO
{
    public readonly string $identifier;   // username ou email
    public readonly string $password;     // mot de passe
    public readonly bool $remember;       // se souvenir
    public readonly ?string $ipAddress;   // IP du client
    public readonly ?string $userAgent;   // User agent
}
```

### Résultat de connexion

```php
[
    'success' => bool,                    // Succès ou échec
    'user' => ?User,                      // Utilisateur si succès
    'message' => string,                  // Message à afficher
    'remainingAttempts' => ?int,          // Tentatives restantes
    'lockoutTime' => ?int,                // Temps de blocage en secondes
]
```

## 🐛 Dépannage

### Problème : "Class not found"

```bash
composer dump-autoload
php artisan optimize:clear
```

### Problème : Cache non nettoyé

```bash
php artisan cache:clear
php artisan config:clear
```

### Problème : Layout non trouvé

Créez `resources/views/layouts/guest.blade.php` avec le contenu minimal ci-dessus.

### Problème : Routes non reconnues

Vérifiez que les routes sont dans `routes/web.php` et exécutez :

```bash
php artisan route:clear
php artisan route:cache
```

## 📝 Notes importantes

1. **Cache Driver** : Le système utilise le cache configuré dans `config/cache.php`. Pour la production, utilisez Redis ou Memcached.

2. **Sessions** : Assurez-vous que les sessions sont correctement configurées dans `config/session.php`.

3. **Sécurité** : En production, activez HTTPS et configurez les headers de sécurité appropriés.

4. **Performance** : Les tentatives sont stockées en cache, pas en base de données, pour de meilleures performances.

## 🚀 Améliorations futures possibles

- [ ] Two-Factor Authentication (2FA)
- [ ] Connexion avec OAuth (Google, Facebook)
- [ ] Notifications email lors de connexions suspectes
- [ ] Historique détaillé des connexions en base de données
- [ ] Captcha après X tentatives
- [ ] Blocage par IP en plus du username
- [ ] Dashboard admin pour gérer les blocages

## 📞 Support

Pour toute question ou problème, consultez :
- Documentation Laravel : https://laravel.com/docs
- Documentation Livewire : https://livewire.laravel.com
- Documentation Bootstrap 5 : https://getbootstrap.com
