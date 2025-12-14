# Gestion Dynamique des Taux de Change

## Vue d'ensemble

Le système de gestion des taux de change permet de gérer dynamiquement les taux de conversion USD/CDF à partir de la base de données, avec un système de cache et de fallback pour garantir la disponibilité.

## Architecture

### Modèle de données

**Table: `rates`**

```sql
- id (bigint, PK)
- amount (double) : Le taux de conversion (1 USD = X CDF)
- school_id (bigint, FK) : Référence à l'école
- is_changed (boolean) : false = taux actif, true = taux archivé
- created_at, updated_at
```

### Service Principal

**CurrencyExchangeService** (`app/Services/CurrencyExchangeService.php`)

Le service implémente un système de fallback à plusieurs niveaux :

1. **Cache** (priorité 1) : TTL de 24h
2. **Base de données** (priorité 2) : Requête sur la table `rates`
3. **Configuration** (priorité 3) : Fichier `config/currency.php`

#### Méthodes principales

```php
// Charger les taux depuis la base de données
private function loadRatesFromDatabase(): ?float

// Obtenir le taux actif actuel
public function getCurrentRateFromDB(): float

// Mettre à jour le taux (crée un nouveau et archive l'ancien)
public function updateRateInDB(float $newRate): bool

// Obtenir l'historique des taux
public function getRateHistory(int $limit = 10, ?int $schoolId = null)

// Rafraîchir le cache
public function refreshRates(): void

// Vider le cache
public function clearCache(): void
```

### Composant Livewire

**ManageExchangeRatePage** (`app/Livewire/Application/Finance/Rate/ManageExchangeRatePage.php`)

Composant de gestion de l'interface utilisateur pour :

-   Afficher le taux actuel
-   Modifier le taux
-   Voir l'historique des changements
-   Rafraîchir le cache

**Propriétés :**

-   `$newRate` : Nouveau taux à définir
-   `$message` : Message de succès/erreur
-   `$messageType` : Type de message (success/error)

**Méthodes :**

-   `mount()` : Initialise avec le taux actuel
-   `updateRate()` : Valide et enregistre le nouveau taux
-   `refreshRate()` : Rafraîchit le cache et recharge le taux
-   `render()` : Prépare les données pour la vue

### Vue Blade

**manage-exchange-rate-page.blade.php**
(`resources/views/livewire/application/finance/rate/manage-exchange-rate-page.blade.php`)

Interface divisée en sections :

1. **Formulaire de mise à jour**

    - Affichage du taux actuel
    - Input pour le nouveau taux
    - Aperçu de conversion
    - Validation en temps réel

2. **Historique des taux**

    - Table avec date, montant et statut
    - Badge "Actif" pour le taux en cours
    - Badge "Archivé" pour les anciens taux
    - Affichage du temps relatif (diffForHumans)

3. **Exemples de conversion**
    - Conversions rapides : 100, 500, 1000 USD
    - Mise à jour dynamique selon le taux

## Routes

```php
// Route principale (liste des taux)
Route::get('rate', MainRatePage::class)->name('finance.rate');

// Route de gestion (modification du taux)
Route::get('rate/manage', ManageExchangeRatePage::class)->name('rate.manage');
```

**URL d'accès :** `/rate/manage`

## Utilisation

### Dans les services

```php
use App\Services\Contracts\CurrencyExchangeServiceInterface;

class ExpenseService implements ExpenseServiceInterface
{
    public function __construct(
        private CurrencyExchangeServiceInterface $currencyService
    ) {}

    public function getTotalAmountByCurrency(): array
    {
        $expenses = ExpenseFee::all();

        $totalUSD = $expenses->where('currency', 'USD')->sum('amount');
        $totalCDF = $expenses->where('currency', 'CDF')->sum('amount');

        // Conversion automatique avec le taux actuel
        $totalInUSD = $totalUSD + $this->currencyService->convertToUSD($totalCDF, 'CDF');
        $totalInCDF = $this->currencyService->convert($totalUSD, 'USD', 'CDF') + $totalCDF;

        return [
            'USD' => $totalInUSD,
            'CDF' => $totalInCDF,
        ];
    }
}
```

### Dans les composants Livewire

```php
use App\Services\Contracts\CurrencyExchangeServiceInterface;

class MyComponent extends Component
{
    public function mount(CurrencyExchangeServiceInterface $currencyService)
    {
        $currentRate = $currencyService->getCurrentRateFromDB();
        $convertedAmount = $currencyService->convert(100, 'USD', 'CDF');
    }
}
```

### Dans les vues Blade

```blade
@php
    $currencyService = app(\App\Services\Contracts\CurrencyExchangeServiceInterface::class);
    $rate = $currencyService->getCurrentRateFromDB();
@endphp

<p>Taux actuel : 1 USD = {{ app_format_number($rate, 0) }} CDF</p>
```

## Logique de mise à jour

Lorsqu'un nouveau taux est enregistré :

1. **Marquage des anciens taux** : Tous les taux existants avec `is_changed = false` sont mis à jour avec `is_changed = true`
2. **Création du nouveau taux** : Un nouveau record est créé avec `is_changed = false`
3. **Rafraîchissement du cache** : Le cache est vidé pour forcer le rechargement
4. **Logging** : L'opération est loggée pour audit

```php
// Dans CurrencyExchangeService::updateRateInDB()
DB::transaction(function () use ($newRate, $schoolId) {
    // 1. Archiver les anciens taux
    Rate::where('school_id', $schoolId)
        ->where('is_changed', false)
        ->update(['is_changed' => true]);

    // 2. Créer le nouveau taux
    Rate::create([
        'amount' => $newRate,
        'school_id' => $schoolId,
        'is_changed' => false,
    ]);

    // 3. Vider le cache
    $this->clearCache();
});
```

## Gestion du cache

**Clé de cache :** `currency.rates`  
**TTL :** 24 heures (86400 secondes)

### Invalidation du cache

Le cache est automatiquement invalidé lors de :

-   Mise à jour d'un taux via `updateRateInDB()`
-   Appel manuel à `refreshRates()`
-   Appel manuel à `clearCache()`

### Rechargement du cache

Le cache est rechargé automatiquement au premier accès après invalidation grâce à la méthode `Cache::remember()`.

## Système de fallback

```
1. Cache → [HIT] Retourne le taux depuis le cache
          ↓ [MISS]
2. Base de données → [FOUND] Retourne le taux actif (is_changed=false)
                    → [EMPTY] Retourne le dernier taux créé
                    ↓ [ERROR/NULL]
3. Configuration → Retourne le taux par défaut (2850 CDF)
```

## Logging et débogage

Tous les événements importants sont loggés :

```php
// Chargement depuis la base de données
Log::info("Loading exchange rate from database for school {$schoolId}");

// Taux chargé avec succès
Log::info("Exchange rate loaded from database", [
    'school_id' => $schoolId,
    'rate' => $rate,
    'is_changed' => $rateRecord->is_changed,
]);

// Erreur de chargement
Log::error("Failed to load exchange rate from database", [
    'school_id' => $schoolId,
    'error' => $e->getMessage(),
]);
```

## Tests

Les tests sont disponibles dans :

-   `tests/Unit/Services/CurrencyExchangeServiceTest.php`

Tests couverts :

-   ✅ Chargement depuis la base de données
-   ✅ Fallback vers la configuration
-   ✅ Mise à jour du taux
-   ✅ Archivage des anciens taux
-   ✅ Gestion du cache
-   ✅ Récupération de l'historique
-   ✅ Conversions de devises

## Configuration

**Fichier :** `config/currency.php`

```php
return [
    'default' => 'USD',
    'rates' => [
        'USD_CDF' => 2850, // Taux de fallback
    ],
    'supported' => [
        'USD' => ['symbol' => '$', 'name' => 'Dollar américain'],
        'CDF' => ['symbol' => 'FC', 'name' => 'Franc congolais'],
    ],
];
```

## Permissions et sécurité

La route de gestion du taux est protégée par :

-   Middleware `auth` : Authentification requise
-   Middleware `verified` : Email vérifié requis
-   Middleware `access.chercker` : Contrôle d'accès personnalisé

**Recommandation :** Ajouter un gate spécifique pour la gestion des taux :

```php
// Dans AuthServiceProvider
Gate::define('manage-exchange-rate', function (User $user) {
    return $user->hasRole(['Super Admin', 'Comptable', 'Directeur']);
});

// Dans la route
Route::get('rate/manage', ManageExchangeRatePage::class)
    ->name('rate.manage')
    ->middleware('can:manage-exchange-rate');
```

## Améliorations futures

1. **Audit trail complet** : Enregistrer qui a modifié le taux et pourquoi
2. **Notifications** : Alerter les administrateurs lors d'un changement de taux
3. **Historique avancé** : Graphiques d'évolution des taux
4. **Multi-devises** : Support de plusieurs paires de devises
5. **API externe** : Synchronisation automatique avec des API de taux de change
6. **Approbation** : Workflow d'approbation pour les changements de taux
7. **Export** : Export de l'historique en CSV/Excel/PDF

## Intégration avec les modules existants

### Module Payment

Le service PaymentService utilise déjà CurrencyExchangeService pour les conversions automatiques.

### Module Expense

Les services ExpenseService et OtherExpenseService utilisent CurrencyExchangeService pour les statistiques multi-devises.

### Module Registration

Le module utilise le modèle Rate directement via `Rate::DEFAULT_RATE()`. **À migrer** vers CurrencyExchangeService pour cohérence.

## Migration des usages existants

### Avant (ancien système)

```php
$rate = Rate::DEFAULT_RATE();
$amountInCDF = $amountInUSD * $rate;
```

### Après (nouveau système)

```php
$amountInCDF = app(CurrencyExchangeServiceInterface::class)
    ->convert($amountInUSD, 'USD', 'CDF');
```

## Dépannage

### Le taux ne se met pas à jour

1. Vérifier les logs dans `storage/logs/laravel.log`
2. Vérifier que la base de données contient bien le nouveau taux
3. Vider le cache manuellement : `php artisan cache:clear`
4. Utiliser le bouton "Rafraîchir" dans l'interface

### Erreur "No rate found"

1. Vérifier qu'au moins un taux existe dans la table `rates`
2. Vérifier la configuration dans `config/currency.php`
3. Vérifier les permissions de la base de données

### Le cache ne se rafraîchit pas

1. Vérifier la configuration du driver de cache dans `.env`
2. Si `CACHE_DRIVER=file`, vérifier les permissions de `storage/framework/cache`
3. Tester avec `php artisan cache:forget currency.rates`

## Commandes utiles

```bash
# Vider le cache complet
php artisan cache:clear

# Vider le cache de la clé spécifique
php artisan tinker
>>> Cache::forget('currency.rates');

# Lister tous les taux
php artisan tinker
>>> Rate::all();

# Obtenir le taux actif
php artisan tinker
>>> Rate::where('is_changed', false)->first();

# Tester une conversion
php artisan tinker
>>> app(\App\Services\Contracts\CurrencyExchangeServiceInterface::class)->convert(100, 'USD', 'CDF');
```

---

**Date de création :** {{ date('Y-m-d') }}  
**Version :** 1.0.0  
**Auteur :** GitHub Copilot
