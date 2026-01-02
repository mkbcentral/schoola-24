# MODULE DE GESTION DES SOUSCRIPTIONS

## 📋 Vue d'ensemble

Ce module permet aux écoles de souscrire à différents modules fonctionnels de l'application (Paiements, Dépenses, Rapports avancés, Stock, SMS, etc.) avec une gestion complète des abonnements, des périodes d'essai et du contrôle d'accès.

## 🚀 Installation et Configuration

### 1. Exécuter les migrations

```bash
php artisan migrate
```

### 2. Charger les données de test

```bash
php artisan db:seed --class=ModuleSeeder
```

Cela créera :
- 4 plans de souscription (Mensuel, Trimestriel, Semestriel, Annuel)
- 6 modules pré-configurés avec navigation

### 3. Enregistrer les Policies dans AuthServiceProvider

Ajoutez dans `app/Providers/AuthServiceProvider.php` :

```php
use App\Models\Module;
use App\Models\SchoolModule;
use App\Policies\ModulePolicy;
use App\Policies\SchoolModulePolicy;

protected $policies = [
    Module::class => ModulePolicy::class,
    SchoolModule::class => SchoolModulePolicy::class,
];
```

## 🎯 Utilisation

### Pour les Écoles

#### Accéder au tableau de bord des modules
```
URL: /school/subscriptions/my-modules
Route: school.modules.dashboard
```

#### Souscrire à un nouveau module
```
URL: /school/subscriptions
Route: school.subscriptions.index
```

#### Voir l'historique des souscriptions
```
URL: /school/subscriptions/history
Route: school.subscriptions.history
```

### Vérifier l'accès à un module dans le code

#### Dans un contrôleur ou composant Livewire
```php
use Illuminate\Support\Facades\Auth;

$school = Auth::user()->school;

// Vérifier l'accès
if ($school->hasModule('payment')) {
    // L'école a accès au module payment
}

// Obtenir les jours restants
$daysRemaining = $school->moduleDaysRemaining('payment');

// Vérifier une fonctionnalité spécifique
if ($school->hasModuleFeature('payment', 'export_formats')) {
    // La fonctionnalité est disponible
}
```

#### Utiliser le middleware sur une route
```php
Route::get('/payments', PaymentController::class)
    ->middleware(['auth', 'module.access:payment']);
```

#### Dans une vue Blade
```blade
@if(Auth::user()->school->hasModule('payment'))
    <a href="{{ route('payment.list') }}">Voir les paiements</a>
@endif
```

### Services disponibles

#### SubscriptionService
```php
use App\Services\Subscription\SubscriptionService;

$subscriptionService = app(SubscriptionService::class);

// Souscrire à un module
$subscription = $subscriptionService->subscribe(
    $school,
    $module,
    $plan,
    $isTrial = false,
    $paidAmount = 5000,
    $paymentReference = 'REF123'
);

// Renouveler une souscription
$subscriptionService->renew($subscription, $newPlan);

// Suspendre/Activer
$subscriptionService->suspend($subscription, 'Raison');
$subscriptionService->activate($subscription);

// Vérifier les expirations
$expiredCount = $subscriptionService->checkExpiredSubscriptions();
```

#### ModuleAccessService
```php
use App\Services\Subscription\ModuleAccessService;

$accessService = app(ModuleAccessService::class);

// Vérifier l'accès
$hasAccess = $accessService->canAccess($school, 'payment');

// Obtenir les modules accessibles
$modules = $accessService->getAccessibleModules($school);

// Obtenir les sections de navigation
$sections = $accessService->getAccessibleSections($school, $module);
```

#### SubscriptionPricingService
```php
use App\Services\Subscription\SubscriptionPricingService;

$pricingService = app(SubscriptionPricingService::class);

// Calculer le prix
$price = $pricingService->calculatePrice($module, $plan);

// Obtenir les économies
$savings = $pricingService->getSavings($module, $plan);

// Recommander un plan
$bestPlan = $pricingService->recommendPlan($module, $expectedMonths = 12);
```

## 📊 Structure des Tables

### modules
- Configuration des modules disponibles
- Fonctionnalités (JSON)
- Navigation (JSON)
- Prix de base

### school_modules
- Souscriptions des écoles aux modules
- Statut, dates d'expiration
- Paramètres personnalisés (JSON)

### subscription_plans
- Plans de souscription (Mensuel, Annuel, etc.)
- Prix et réductions

### subscription_histories
- Historique de toutes les actions sur les souscriptions
- Traçabilité complète

## 🎨 Personnalisation

### Ajouter un nouveau module

1. Créer une constante dans `ModuleType.php`
```php
const CUSTOM_MODULE = 'custom_module';
```

2. Ajouter dans la base de données
```php
Module::create([
    'name' => 'Mon Module',
    'code' => 'custom_module',
    'description' => 'Description',
    'icon' => 'fas fa-star',
    'base_price' => 5000,
    'trial_days' => 14,
    'features' => [
        'feature1' => true,
        'max_items' => 100,
    ],
    'navigation' => [
        'main_route' => 'custom.index',
        'sections' => [
            ['name' => 'Vue 1', 'route' => 'custom.view1', 'icon' => 'fa-list'],
            ['name' => 'Vue 2', 'route' => 'custom.view2', 'icon' => 'fa-chart'],
        ],
    ],
]);
```

### Modifier les plans de souscription

Éditez le seeder `ModuleSeeder.php` ou ajoutez directement dans la base.

### Personnaliser les permissions

Modifiez les policies `ModulePolicy.php` et `SchoolModulePolicy.php` selon vos besoins.

## 🔒 Sécurité et Permissions

### Rôles autorisés

**Pour gérer les modules (CRUD):**
- ROOT
- APP_ADMIN

**Pour souscrire/gérer les souscriptions de son école:**
- ADMIN_SCHOOL
- SCHOOL_BOSS

**Pour consulter:**
- SCHOOL_MANAGER

## 🔔 Tâches Planifiées

Ajoutez dans `app/Console/Kernel.php` :

```php
protected function schedule(Schedule $schedule)
{
    // Vérifier les expirations tous les jours à 2h du matin
    $schedule->call(function () {
        app(SubscriptionService::class)->checkExpiredSubscriptions();
    })->daily()->at('02:00');
}
```

## 📝 Exemple Complet : Souscrire à un module

```php
use App\Models\School;
use App\Models\Module;
use App\Models\SubscriptionPlan;
use App\Services\Subscription\SubscriptionService;

// Récupérer les entités
$school = School::find(1);
$module = Module::where('code', 'payment')->first();
$plan = SubscriptionPlan::where('code', 'annual')->first();

// Souscrire
$subscriptionService = app(SubscriptionService::class);

$subscription = $subscriptionService->subscribe(
    school: $school,
    module: $module,
    plan: $plan,
    isTrial: false,
    paidAmount: $plan->getFinalPrice(),
    paymentReference: 'PAYMENT-2025-001'
);

// Vérifier l'accès
if ($school->hasModule('payment')) {
    echo "✅ Accès accordé au module Payment!";
}
```

## 🛠️ Commandes Artisan Utiles

```bash
# Vérifier les souscriptions expirées
php artisan tinker
>>> app(\App\Services\Subscription\SubscriptionService::class)->checkExpiredSubscriptions();

# Lister les modules d'une école
>>> School::find(1)->activeModules()->with('module')->get();

# Effacer le cache d'accès
>>> app(\App\Services\Subscription\ModuleAccessService::class)->clearSchoolAccessCache(School::find(1));
```

## 📞 Support et Contribution

Pour toute question ou suggestion, contactez l'équipe de développement.

---

**Créé le:** 15 Décembre 2025  
**Version:** 1.0.0
