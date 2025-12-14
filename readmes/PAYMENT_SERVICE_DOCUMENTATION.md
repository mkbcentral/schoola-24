# 🏗️ PaymentService - Architecture & Documentation

## 📋 Vue d'ensemble

Architecture propre et performante pour la gestion des paiements basée sur le pattern **Service + DTO** avec mise en cache automatique et gestion multi-devises.

---

## 🎯 Responsabilités

### PaymentService

1. ✅ **Créer** un nouveau paiement
2. ✅ **Retourner** un paiement par ID
3. ✅ **Mettre à jour** un paiement
4. ✅ **Supprimer** un paiement
5. ✅ **Récupérer** la liste des paiements avec filtres multiples

### Fonctionnalités avancées

-   ✨ Filtrage multi-critères (10+ filtres)
-   💰 Calcul automatique des totaux **par devise** (CDF, USD, EUR, etc.)
-   📊 Statistiques (nombre payés/impayés, taux de paiement)
-   ⚡ Mise en cache automatique avec invalidation intelligente
-   🚀 Optimisation des requêtes (Eager Loading)

---

## 📁 Structure des fichiers

```
app/
├── Services/
│   ├── Contracts/
│   │   └── PaymentServiceInterface.php  # Interface du service
│   └── PaymentService.php               # Implémentation concrète
├── DTOs/
│   └── Payment/
│       ├── PaymentFilterDTO.php         # DTO pour les filtres
│       └── PaymentResultDTO.php         # DTO pour les résultats
└── Http/Controllers/Api/Payment/
    └── PaymentApiController.php         # Exemple d'utilisation (API)
```

---

## 🔧 Configuration

### 1. Enregistrer le service

Le service est déjà enregistré dans `RepositoryServiceProvider` :

```php
$this->app->bind(
    PaymentServiceInterface::class,
    PaymentService::class
);
```

### 2. Configuration du cache

Dans `.env` :

```env
CACHE_DRIVER=redis  # Recommandé pour production
# ou
CACHE_DRIVER=file   # Pour développement
```

---

## 💻 Utilisation

### 1. Injection dans un contrôleur

```php
use App\Services\Contracts\PaymentServiceInterface;
use App\DTOs\Payment\PaymentFilterDTO;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentServiceInterface $paymentService
    ) {}

    public function index(Request $request)
    {
        // Créer les filtres
        $filters = PaymentFilterDTO::fromArray($request->all());

        // Récupérer les résultats
        $result = $this->paymentService->getFilteredPayments($filters, perPage: 15);

        // Accéder aux données
        $payments = $result->payments;              // Liste paginée
        $totalCount = $result->totalCount;          // Nombre total
        $totalCDF = $result->getTotalForCurrency('CDF');  // Total en CDF
        $totalUSD = $result->getTotalForCurrency('USD');  // Total en USD
        $statistics = $result->statistics;          // Statistiques

        return view('payments.index', compact(
            'payments', 'totalCount', 'totalCDF', 'totalUSD', 'statistics'
        ));
    }
}
```

### 2. Injection dans Livewire

```php
use App\Services\Contracts\PaymentServiceInterface;
use App\DTOs\Payment\PaymentFilterDTO;

class PaymentList extends Component
{
    private PaymentServiceInterface $paymentService;

    public function boot(PaymentServiceInterface $paymentService): void
    {
        $this->paymentService = $paymentService;
    }

    public function render()
    {
        $filters = PaymentFilterDTO::fromArray([
            'month' => $this->month,
            'isPaid' => true,
            'currency' => 'CDF',
        ]);

        $result = $this->paymentService->getFilteredPayments($filters, 20);

        return view('livewire.payment-list', [
            'payments' => $result->payments,
            'totalCDF' => $result->getTotalForCurrency('CDF'),
        ]);
    }
}
```

### 3. API REST (exemple complet)

Voir `PaymentApiController.php` pour :

-   `GET /api/payments` - Liste avec filtres
-   `GET /api/payments/{id}` - Détails d'un paiement
-   `POST /api/payments` - Créer un paiement
-   `PUT /api/payments/{id}` - Mettre à jour
-   `DELETE /api/payments/{id}` - Supprimer
-   `GET /api/payments/statistics` - Statistiques uniquement

---

## 🔍 Filtres disponibles

### PaymentFilterDTO - Tous les filtres supportés

| Filtre          | Type      | Description             | Exemple                 |
| --------------- | --------- | ----------------------- | ----------------------- |
| `date`          | `?string` | Date exacte de paiement | `2025-11-24`            |
| `month`         | `?string` | Mois de paiement        | `JANVIER`               |
| `period`        | `?string` | Période (début:fin)     | `2025-01-01:2025-01-31` |
| `categoryFeeId` | `?int`    | ID catégorie de frais   | `1`                     |
| `feeId`         | `?int`    | ID frais scolaire       | `5`                     |
| `sectionId`     | `?int`    | ID section              | `2`                     |
| `optionId`      | `?int`    | ID option               | `3`                     |
| `classRoomId`   | `?int`    | ID classe               | `10`                    |
| `isPaid`        | `?bool`   | Statut de paiement      | `true`                  |
| `userId`        | `?int`    | ID utilisateur créateur | `7`                     |
| `currency`      | `?string` | Devise                  | `CDF`, `USD`            |
| `search`        | `?string` | Recherche par nom élève | `Jean Dupont`           |

### Exemples de filtrage

```php
// Filtre simple
$filters = PaymentFilterDTO::fromArray([
    'month' => 'JANVIER',
    'isPaid' => true,
]);

// Filtre par période
$filters = PaymentFilterDTO::fromArray([
    'period' => '2025-01-01:2025-01-31',
    'currency' => 'USD',
]);

// Filtre multi-critères
$filters = PaymentFilterDTO::fromArray([
    'month' => 'FEVRIER',
    'categoryFeeId' => 1,
    'sectionId' => 2,
    'classRoomId' => 10,
    'isPaid' => true,
    'currency' => 'CDF',
    'search' => 'Jean',
]);
```

---

## 💰 Gestion Multi-devises

### Calcul automatique par devise

Le service calcule **automatiquement** les totaux pour chaque devise présente :

```php
$result = $this->paymentService->getFilteredPayments($filters);

// Récupérer le total pour une devise spécifique
$totalCDF = $result->getTotalForCurrency('CDF');  // 150000.00
$totalUSD = $result->getTotalForCurrency('USD');  // 450.00

// Récupérer toutes les devises
$currencies = $result->getCurrencies();  // ['CDF', 'USD']

// Vérifier si une devise existe
if ($result->hasCurrency('EUR')) {
    echo "Montant EUR : " . $result->getTotalForCurrency('EUR');
}

// Tableau complet des totaux
$allTotals = $result->totalsByCurrency;
// ['CDF' => 150000.00, 'USD' => 450.00]
```

### Affichage dans Blade

```blade
<div class="totals">
    @foreach($totalsByCurrency as $currency => $amount)
        <div class="total-item">
            <span class="currency">{{ $currency }}</span>
            <span class="amount">{{ number_format($amount, 2) }}</span>
        </div>
    @endforeach
</div>
```

---

## 📊 Statistiques

### Données disponibles

```php
$result = $this->paymentService->getFilteredPayments($filters);

$stats = $result->statistics;
/*
[
    'paid_count' => 125,        // Nombre de paiements effectués
    'unpaid_count' => 18,       // Nombre de paiements non effectués
    'payment_rate' => 87.41,    // Taux de paiement en %
]
*/
```

### Exemple d'affichage

```blade
<div class="statistics">
    <div class="stat-card">
        <h4>Paiements effectués</h4>
        <p>{{ $statistics['paid_count'] }}</p>
    </div>
    <div class="stat-card">
        <h4>Paiements en attente</h4>
        <p>{{ $statistics['unpaid_count'] }}</p>
    </div>
    <div class="stat-card">
        <h4>Taux de paiement</h4>
        <p>{{ $statistics['payment_rate'] }}%</p>
    </div>
</div>
```

---

## ⚡ Performance & Cache

### Mise en cache automatique

Le service met **automatiquement en cache** :

-   ✅ Les listes de paiements filtrées
-   ✅ Les totaux par devise
-   ✅ Les statistiques

**Durée** : 60 minutes (configurable dans `PaymentService::CACHE_TTL`)

### Invalidation automatique

Le cache est **automatiquement invalidé** lors de :

-   Création d'un paiement
-   Modification d'un paiement
-   Suppression d'un paiement

### Invalidation manuelle

```php
$this->paymentService->clearCache();
```

### Clés de cache uniques

Chaque combinaison de filtres génère une clé unique :

```
payments.filtered.a3d5f8b2c1e4d7a9.perpage_15
payments.filtered.e7c2a1b4f9d3e5a8.perpage_20
```

---

## 🎨 DTOs (Data Transfer Objects)

### PaymentFilterDTO

Encapsule tous les filtres de recherche.

**Avantages** :

-   ✅ Type-safe (typage strict)
-   ✅ Immutable (readonly properties)
-   ✅ Validation centralisée
-   ✅ Conversion automatique (array → DTO)

### PaymentResultDTO

Encapsule les résultats de recherche.

**Contenu** :

-   `payments` : Liste paginée
-   `totalCount` : Nombre total d'éléments
-   `totalsByCurrency` : Totaux par devise
-   `statistics` : Statistiques additionnelles

**Méthodes utiles** :

```php
$result->getTotalForCurrency('CDF');
$result->getCurrencies();
$result->hasCurrency('USD');
$result->toArray();
$result->toJson();
```

---

## 🧪 Tests

### Test du service

```php
use App\Services\PaymentService;
use App\DTOs\Payment\PaymentFilterDTO;

it('can create a payment', function () {
    $service = app(PaymentServiceInterface::class);

    $payment = $service->create([
        'registration_id' => 1,
        'scolar_fee_id' => 1,
        'month' => 'JANVIER',
    ]);

    expect($payment)->toBeInstanceOf(Payment::class);
    expect($payment->payment_number)->toStartWith('PAY-');
});

it('calculates totals by currency correctly', function () {
    $service = app(PaymentServiceInterface::class);

    $filters = PaymentFilterDTO::fromArray(['isPaid' => true]);
    $result = $service->getFilteredPayments($filters);

    expect($result->totalsByCurrency)->toBeArray();
    expect($result->getTotalForCurrency('CDF'))->toBeFloat();
});
```

---

## 🔄 Migration depuis PaymentFeature

### Avant (PaymentFeature)

```php
$payments = PaymentFeature::getList($date, $month, null, ...);
$total = PaymentFeature::getTotal($date, $month, ...);
```

### Après (PaymentService)

```php
$filters = PaymentFilterDTO::fromArray([
    'date' => $date,
    'month' => $month,
    // ... autres filtres
]);

$result = $this->paymentService->getFilteredPayments($filters);
$payments = $result->payments;
$totalCDF = $result->getTotalForCurrency('CDF');
$totalUSD = $result->getTotalForCurrency('USD');
```

---

## ✅ Avantages de cette architecture

1. **Séparation des responsabilités** : Service ≠ Repository ≠ Contrôleur
2. **Type-safety** : DTOs avec typage strict (PHP 8.2+)
3. **Performance** : Cache automatique + Eager Loading
4. **Multi-devises** : Gestion native des différentes devises
5. **Testabilité** : Interface mockable facilement
6. **Maintenabilité** : Logique centralisée dans le service
7. **Réutilisabilité** : Même service pour API, Livewire, CLI

---

## 📚 Ressources

-   `PaymentServiceInterface.php` - Contrat du service
-   `PaymentService.php` - Implémentation
-   `PaymentFilterDTO.php` - Filtres
-   `PaymentResultDTO.php` - Résultats
-   `PaymentApiController.php` - Exemple API
-   `PaymentListWithService.php` - Exemple Livewire

---

**Créé le** : 24 novembre 2025  
**Architecture** : Service + DTO Pattern avec cache multi-devises
