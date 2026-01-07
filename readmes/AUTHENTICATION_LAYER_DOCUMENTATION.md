# Documentation de la Couche d'Authentification Livewire

## Vue d'ensemble

Cette documentation décrit la couche d'authentification complète implémentée avec Laravel Fortify et Livewire, offrant une expérience utilisateur moderne et réactive.

## Architecture

### Composants Livewire créés

#### 1. Login (`app/Livewire/Auth/Login.php`)
**Fonctionnalités:**
- Authentification par email et mot de passe
- Option "Se souvenir de moi"
- Limitation de taux (Rate limiting) - 5 tentatives par minute
- Validation des identifiants
- Redirection après connexion réussie
- Gestion des erreurs et messages d'authentification

**Vue:** `resources/views/livewire/auth/login.blade.php`
- Formulaire Bootstrap 4.6.2 responsive
- États de chargement avec spinners
- Validation en temps réel
- Lien vers mot de passe oublié et inscription

#### 2. Register (`app/Livewire/Auth/Register.php`)
**Fonctionnalités:**
- Inscription de nouveaux utilisateurs
- Validation complète des données
- Hash sécurisé des mots de passe
- Confirmation du mot de passe
- Acceptation des conditions d'utilisation
- Connexion automatique après inscription
- Événement `Registered` déclenché

**Vue:** `resources/views/livewire/auth/register.blade.php`

#### 3. ForgotPassword (`app/Livewire/Auth/ForgotPassword.php`)
**Fonctionnalités:**
- Demande de réinitialisation de mot de passe
- Envoi d'email avec lien de réinitialisation
- Validation de l'adresse email
- Message de confirmation

**Vue:** `resources/views/livewire/auth/forgot-password.blade.php`

#### 4. ResetPassword (`app/Livewire/Auth/ResetPassword.php`)
**Fonctionnalités:**
- Réinitialisation du mot de passe
- Validation du token de réinitialisation
- Confirmation du nouveau mot de passe
- Mise à jour sécurisée du mot de passe
- Génération d'un nouveau remember token
- Événement `PasswordReset` déclenché

**Vue:** `resources/views/livewire/auth/reset-password.blade.php`

#### 5. VerifyEmail (`app/Livewire/Auth/VerifyEmail.php`)
**Fonctionnalités:**
- Vérification de l'adresse email
- Renvoi du lien de vérification
- Redirection si déjà vérifié
- Déconnexion disponible

**Vue:** `resources/views/livewire/auth/verify-email.blade.php`

#### 6. ConfirmPassword (`app/Livewire/Auth/ConfirmPassword.php`)
**Fonctionnalités:**
- Confirmation du mot de passe pour actions sensibles
- Validation du mot de passe actuel
- Session de confirmation (timeout configurable)
- Redirection après confirmation

**Vue:** `resources/views/livewire/auth/confirm-password.blade.php`

#### 7. TwoFactorChallenge (`app/Livewire/Auth/TwoFactorChallenge.php`)
**Fonctionnalités:**
- Authentification à deux facteurs
- Support des codes TOTP (Time-based One-Time Password)
- Support des codes de récupération
- Basculement entre code et code de récupération
- Validation des codes
- Suppression automatique des codes de récupération utilisés

**Vue:** `resources/views/livewire/auth/two-factor-challenge.blade.php`

## Configuration Fortify

### FortifyServiceProvider

Le provider a été mis à jour pour utiliser les composants Livewire:

```php
// Vues personnalisées
Fortify::loginView(fn() => view('guest-view'));
Fortify::registerView(fn() => view('auth.register-view'));
Fortify::requestPasswordResetLinkView(fn() => view('auth.forgot-password-view'));
Fortify::resetPasswordView(fn($request) => view('auth.reset-password-view', ['request' => $request]));
Fortify::verifyEmailView(fn() => view('auth.verify-email-view'));
Fortify::twoFactorChallengeView(fn() => view('auth.two-factor-challenge-view'));
Fortify::confirmPasswordView(fn() => view('auth.confirm-password-view'));
```

### Features activées dans `config/fortify.php`

```php
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    // Features::emailVerification(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]),
],
```

## Routes

Fortify gère automatiquement les routes suivantes:

### Authentification
- `GET /login` - Page de connexion
- `POST /login` - Traitement de la connexion
- `POST /logout` - Déconnexion

### Inscription
- `GET /register` - Page d'inscription
- `POST /register` - Traitement de l'inscription

### Réinitialisation de mot de passe
- `GET /forgot-password` - Demande de réinitialisation
- `POST /forgot-password` - Envoi du lien
- `GET /reset-password/{token}` - Formulaire de réinitialisation
- `POST /reset-password` - Traitement de la réinitialisation

### Vérification d'email
- `GET /email/verify` - Page de vérification
- `GET /email/verify/{id}/{hash}` - Lien de vérification
- `POST /email/verification-notification` - Renvoi du lien

### Authentification à deux facteurs
- `GET /two-factor-challenge` - Challenge 2FA
- `POST /two-factor-challenge` - Validation du code

### Confirmation de mot de passe
- `GET /user/confirm-password` - Demande de confirmation
- `POST /user/confirm-password` - Validation

## Sécurité

### Rate Limiting
- **Login:** 5 tentatives par minute par email/IP
- **Two-Factor:** 5 tentatives par minute par session

### Protection
- Hash bcrypt des mots de passe
- CSRF protection sur tous les formulaires
- Session regeneration après authentification
- Throttling key translitéré pour éviter les attaques
- Password confirmation pour actions sensibles

## Design et UX

### Interface utilisateur
- **Framework:** Bootstrap 4.6.2
- **Design:** Cards avec ombres douces
- **Responsive:** Mobile-first approach
- **Icônes:** Bootstrap Icons
- **États de chargement:** Spinners animés
- **Feedback:** Messages d'erreur inline avec validation

### Expérience utilisateur
- Navigation fluide avec Livewire Wire:navigate
- Validation en temps réel
- États de chargement clairs
- Messages d'erreur contextuels
- Design cohérent sur toutes les pages

## Utilisation

### Connexion simple
```php
// Le composant Login gère automatiquement:
// - La validation
// - Le rate limiting
// - La session
// - La redirection
```

### Avec remember me
```blade
<input type="checkbox" wire:model="remember">
```

### Mot de passe oublié
```php
// Workflow automatique:
// 1. Demande (ForgotPassword)
// 2. Email envoyé
// 3. Réinitialisation (ResetPassword)
// 4. Confirmation et redirection
```

### Authentification à deux facteurs
```php
// Si activée dans le profil utilisateur:
// 1. Login normal
// 2. Redirection vers TwoFactorChallenge
// 3. Validation du code
// 4. Accès accordé
```

## Personnalisation

### Changer les redirections
Dans `config/fortify.php`:
```php
'home' => '/dashboard', // Après connexion
```

### Personnaliser les vues
Les vues se trouvent dans:
- `resources/views/livewire/auth/`
- Utiliser Bootstrap 4.6.2 pour la cohérence

### Ajouter des champs
1. Modifier le composant Livewire
2. Ajouter la validation
3. Mettre à jour la vue
4. Modifier l'action Fortify si nécessaire

## Tests

### Tests manuels à effectuer
- ✅ Connexion avec identifiants valides
- ✅ Connexion avec identifiants invalides
- ✅ Rate limiting (6+ tentatives)
- ✅ Remember me
- ✅ Inscription nouveau utilisateur
- ✅ Mot de passe oublié
- ✅ Réinitialisation de mot de passe
- ✅ Vérification d'email
- ✅ Confirmation de mot de passe
- ✅ Authentification 2FA avec code
- ✅ Authentification 2FA avec code de récupération

## Maintenance

### Fichiers à surveiller
- `app/Livewire/Auth/` - Composants
- `resources/views/livewire/auth/` - Vues
- `app/Providers/FortifyServiceProvider.php` - Configuration
- `config/fortify.php` - Features et options

### Dépendances
- Laravel 10+
- Laravel Fortify
- Livewire 3+
- Bootstrap 4.6.2
- Bootstrap Icons

## Notes importantes

1. **Email:** Configurer le service d'email pour les fonctionnalités de réinitialisation et vérification
2. **2FA:** Nécessite une configuration supplémentaire côté utilisateur
3. **Layouts:** Utilise `components.layouts.guest` pour toutes les pages d'auth
4. **Traductions:** Messages en français, personnalisables dans `lang/fr/`

## Support et Documentation

- [Laravel Fortify](https://laravel.com/docs/10.x/fortify)
- [Livewire 3](https://livewire.laravel.com/)
- [Bootstrap 4.6.2](https://getbootstrap.com/docs/4.6/)
