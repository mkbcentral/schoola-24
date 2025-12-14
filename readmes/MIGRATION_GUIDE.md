# 🔄 Guide de Migration - Repository Pattern

## Vue d'ensemble

Ce guide vous aide à migrer votre code existant vers le nouveau Repository Pattern implémenté pour améliorer l'architecture et les performances.

---

## 📋 Checklist de Migration

### ✅ Étapes complétées

-   [x] PaymentRepositoryInterface créée
-   [x] PaymentRepository implémenté
-   [x] RepositoryServiceProvider enregistré
-   [x] Model Payment nettoyé
-   [x] Méthodes dépréciées marquées
-   [x] Documentation ARCHITECTURE.md créée

### 🔄 À faire

-   [ ] Migrer les composants Livewire existants
-   [ ] Migrer les contrôleurs existants
-   [ ] Créer les tests pour le repository
-   [ ] Supprimer les méthodes dépréciées après migration

---

## 🔍 Comment identifier le code à migrer

### Rechercher dans votre code :

```bash
# Rechercher les appels directs au modèle Payment
grep -r "Payment::" app/

# Rechercher les scopes utilisés
grep -r "scopeFilter\|scopeNotFilter\|reusableScopeData" app/

# Rechercher les méthodes statiques dépréciées
grep -r "getTotalAmountByCategoryForMonthOrDate\|getListReceiptsYear\|getPaymentsByMonthAndCategory" app/
```

---

## 📝 Exemples de Migration

### 1. Migration d'un Composant Livewire

#### ❌ AVANT (ancien code)

```php
<?php

namespace App\Livewire\Application\Payment;

use App\Models\Payment;
use Livewire\Component;

class PaymentList extends Component
{
    public string $search = '';
    public ?string $month = null;

    public function render()
    {
        // Appel direct au modèle avec scope
        $payments = Payment::query()
            ->filter([
                'key_to_search' => $this->search,
                'month' => $this->month,
            ])
            ->paginate(15);

        // Méthode statique dépréciée
        $totals = Payment::getTotalAmountByCategoryForMonthOrDate(
            $this->month,
            null
        );

        return view('livewire.payment.list', [
            'payments' => $payments,
            'totals' => $totals,
        ]);
    }
}
```

#### ✅ APRÈS (nouveau code avec repository)

```php
<?php

namespace App\Livewire\Application\Payment;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use Livewire\Component;

class PaymentList extends Component
{
    public string $search = '';
    public ?string $month = null;

    // Injection de dépendances
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository
    ) {}

    public function render()
    {
        // Utilisation du repository avec eager loading automatique
        $payments = $this->paymentRepository->getAllWithFilters([
            'key_to_search' => $this->search,
            'month' => $this->month,
        ], perPage: 15);

        // Méthode du repository avec cache automatique
        $totals = $this->paymentRepository->getTotalAmountByCategory(
            $this->month,
            null
        );

        // Bonus : statistiques avec cache
        $stats = $this->paymentRepository->getPaymentStatistics([
            'month' => $this->month,
        ]);

        return view('livewire.payment.list', [
            'payments' => $payments,
            'totals' => $totals,
            'stats' => $stats, // Nouvelles statistiques
        ]);
    }
}
```

---

### 2. Migration d'un Contrôleur

#### ❌ AVANT

```php
<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::query()
            ->filter($request->all())
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function statistics(Request $request)
    {
        $categoryId = $request->input('category_id');

        $receipts = Payment::getListReceiptsYear($categoryId);
        $byMonth = Payment::getPaymentsByMonthAndCategory($categoryId);

        return view('payments.statistics', compact('receipts', 'byMonth'));
    }
}
```

#### ✅ APRÈS

```php
<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository
    ) {}

    public function index(Request $request)
    {
        $payments = $this->paymentRepository->getAllWithFilters(
            $request->all()
        );

        return view('payments.index', compact('payments'));
    }

    public function statistics(Request $request)
    {
        $categoryId = $request->input('category_id');

        // Méthodes du repository avec cache
        $receipts = $this->paymentRepository->getYearlyReceiptsByCategory($categoryId);
        $byMonth = $this->paymentRepository->getPaymentsByMonthAndCategory($categoryId);

        // Bonus : nouvelles méthodes disponibles
        $stats = $this->paymentRepository->getPaymentStatistics([
            'categoryId' => $categoryId,
        ]);

        return view('payments.statistics', compact('receipts', 'byMonth', 'stats'));
    }
}
```

---

### 3. Migration de Services/Actions

#### ❌ AVANT

```php
<?php

namespace App\Services;

use App\Models\Payment;

class PaymentService
{
    public function getMonthlyReport(string $month)
    {
        $payments = Payment::query()
            ->filter(['month' => $month, 'isPaid' => true])
            ->get();

        $totals = Payment::getTotalAmountByCategoryForMonthOrDate($month, null);

        return [
            'payments' => $payments,
            'totals' => $totals,
            'count' => $payments->count(),
        ];
    }
}
```

#### ✅ APRÈS

```php
<?php

namespace App\Services;

use App\Repositories\Contracts\PaymentRepositoryInterface;

class PaymentService
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository
    ) {}

    public function getMonthlyReport(string $month)
    {
        // Utilisation du repository
        $payments = $this->paymentRepository->getAllWithFilters([
            'month' => $month,
            'isPaid' => true,
        ], perPage: 10000); // Grande limite pour récupérer tout

        $totals = $this->paymentRepository->getTotalAmountByCategory($month, null);

        // Bonus : utiliser les nouvelles statistiques
        $stats = $this->paymentRepository->getPaymentStatistics([
            'month' => $month,
        ]);

        return [
            'payments' => $payments,
            'totals' => $totals,
            'stats' => $stats,
        ];
    }
}
```

---

### 4. Migration de Tests

#### ❌ AVANT

```php
public function test_can_get_payments_by_month(): void
{
    Payment::factory()->count(5)->create(['month' => 'JANVIER']);
    Payment::factory()->count(3)->create(['month' => 'FEVRIER']);

    $payments = Payment::query()
        ->filter(['month' => 'JANVIER'])
        ->get();

    $this->assertCount(5, $payments);
}
```

#### ✅ APRÈS

```php
public function test_can_get_payments_by_month(): void
{
    Payment::factory()->count(5)->create(['month' => 'JANVIER']);
    Payment::factory()->count(3)->create(['month' => 'FEVRIER']);

    $repository = app(PaymentRepositoryInterface::class);
    $payments = $repository->getAllWithFilters(['month' => 'JANVIER']);

    $this->assertCount(5, $payments);
}
```

---

## 🎯 Priorités de Migration

### Haute priorité (migrer d'abord)

1. **Composants Livewire fréquemment utilisés**

    - ListPaymentPage
    - MainPaymentPage
    - PaymentDashboard

2. **Contrôleurs API** (si vous en avez)

    - Bénéficient grandement du cache

3. **Services métier**
    - Centralisation de la logique

### Priorité moyenne

4. **Autres composants Livewire**
5. **Commandes Artisan**
6. **Jobs**

### Basse priorité

7. **Code legacy rarement utilisé**
    - Peut rester avec les méthodes dépréciées temporairement

---

## 📊 Table de Correspondance

| Ancien code                                         | Nouveau code (Repository)                                   |
| --------------------------------------------------- | ----------------------------------------------------------- |
| `Payment::query()->filter($filters)->paginate()`    | `$paymentRepository->getAllWithFilters($filters, $perPage)` |
| `Payment::find($id)`                                | `$paymentRepository->findById($id)`                         |
| `Payment::create($data)`                            | `$paymentRepository->create($data)`                         |
| `Payment::getTotalAmountByCategoryForMonthOrDate()` | `$paymentRepository->getTotalAmountByCategory()`            |
| `Payment::getListReceiptsYear()`                    | `$paymentRepository->getYearlyReceiptsByCategory()`         |
| `Payment::getPaymentsByMonthAndCategory()`          | `$paymentRepository->getPaymentsByMonthAndCategory()`       |
| `Payment::where('is_paid', false)->get()`           | `$paymentRepository->getUnpaidPayments()`                   |
| N/A (nouveau)                                       | `$paymentRepository->getStudentPayments()`                  |
| N/A (nouveau)                                       | `$paymentRepository->getTotalForPeriod()`                   |
| N/A (nouveau)                                       | `$paymentRepository->getPaymentStatistics()`                |

---

## 🧪 Tester après Migration

### 1. Tests fonctionnels

```bash
php artisan test --filter=Payment
```

### 2. Vérifier les logs

```bash
tail -f storage/logs/laravel.log
```

Les méthodes dépréciées déclenchent des warnings qui apparaîtront dans les logs.

### 3. Performance

Avant/Après la migration, mesurez :

-   Nombre de requêtes SQL (Laravel Debugbar)
-   Temps de réponse (Network tab)
-   Utilisation mémoire

---

## ⚠️ Points d'Attention

### 1. Injection de dépendances dans Livewire

Livewire v3 supporte l'injection dans le constructeur :

```php
public function __construct(
    private PaymentRepositoryInterface $paymentRepository
) {}
```

### 2. Cache

Le repository utilise le cache automatiquement. Si vous avez besoin de données en temps réel :

```php
// Invalider le cache manuellement
Cache::tags(['payments'])->flush();
```

### 3. Tests

Mockez le repository dans les tests :

```php
$mock = Mockery::mock(PaymentRepositoryInterface::class);
$mock->shouldReceive('getAllWithFilters')->andReturn(collect());
$this->app->instance(PaymentRepositoryInterface::class, $mock);
```

---

## 🚀 Après la Migration

### 1. Nettoyage

Une fois que tout le code utilise le repository, supprimez les méthodes dépréciées :

```php
// Dans Payment.php, supprimer :
// - scopeFilter()
// - scopeNotFilter()
// - reusableScopeData()
// - getTotalAmountByCategoryForMonthOrDate()
// - getListReceiptsYear()
// - getPaymentsByMonthAndCategory()
```

### 2. Documentation

Mettez à jour votre documentation interne avec les nouvelles méthodes.

### 3. Formation

Formez l'équipe sur le nouveau pattern :

-   Montrez les avantages (performance, testabilité)
-   Partagez ce guide
-   Faites des code reviews

---

## 📚 Ressources

-   [ARCHITECTURE.md](./ARCHITECTURE.md) - Documentation détaillée
-   [PaymentRepository.php](./app/Repositories/PaymentRepository.php) - Code source
-   [ListPaymentPage.php](./app/Livewire/Application/Payment/ListPaymentPage.php) - Exemple d'utilisation

---

## ❓ FAQ

### Q: Puis-je encore utiliser `Payment::find($id)` ?

**R:** Oui, pour les opérations simples. Mais `$paymentRepository->findById($id)` charge automatiquement toutes les relations.

### Q: Le cache peut-il causer des problèmes ?

**R:** Non, il est invalidé automatiquement lors des modifications (create, update, delete).

### Q: Dois-je migrer tout d'un coup ?

**R:** Non, migrez progressivement. Les méthodes dépréciées continueront de fonctionner.

### Q: Comment déboguer les requêtes du repository ?

**R:** Utilisez Laravel Debugbar ou `DB::enableQueryLog()`.

### Q: Peut-on utiliser le repository dans les jobs/commands ?

**R:** Oui ! L'injection de dépendances fonctionne partout.

---

**Bonne migration ! 🎉**

En cas de question, consultez `ARCHITECTURE.md` ou contactez l'équipe.
