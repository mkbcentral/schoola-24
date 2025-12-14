# 🔄 Migration PaymentFeature - TODO List

## Status : Migration en cours ✅

Le `PaymentFeature` a été converti pour utiliser le Repository Pattern avec injection de dépendances.

### ✅ Complété

-   [x] `PaymentFeature` converti en classe non-statique
-   [x] Interface `IPayment` mise à jour (méthodes non-statiques)
-   [x] `PaymentRepository` intégré dans `PaymentFeature`
-   [x] `RepositoryServiceProvider` configuré pour `IPayment`
-   [x] `ListReportPaymentPage.php` migré

### 🔄 Fichiers à migrer (18 occurrences)

Les fichiers suivants utilisent encore `PaymentFeature::` de manière statique et doivent être migrés :

#### 📊 Composants Livewire (Haute priorité)

1. **ListPaymentByDatePage.php** (2 occurrences)

    - Ligne 122: `PaymentFeature::getList(...)`
    - Ligne 135: `PaymentFeature::getTotal(...)`
    - Migration: Injecter via `boot(IPayment $paymentFeature)`

2. **ListReportPaymentByTranchPage.php** (2 occurrences)

    - Ligne 93: `PaymentFeature::getList(...)`
    - Ligne 112: `PaymentFeature::getTotal(...)`
    - Migration: Injecter via `boot(IPayment $paymentFeature)`

3. **PaymentForm.php** (1 occurrence)
    - Ligne 32: `PaymentFeature::create($input)`
    - Migration: Injecter via propriété `public IPayment $paymentFeature;`

#### 🎯 Contrôleurs (Haute priorité)

4. **PrintPaymentController.php** (2 occurrences)

    - Ligne 33: `PaymentFeature::getList(...)`
    - Ligne 72: `PaymentFeature::getList(...)`
    - Migration: Injection dans le constructeur

5. **MakePaymentController.php** (1 occurrence)

    - Ligne 43: `PaymentFeature::create($inputs)`
    - Migration: Injection dans le constructeur

6. **PaymentRepportPaymentController.php** (2 occurrences)

    - Ligne 28: `PaymentFeature::getTotal(...)`
    - Ligne 71: `PaymentFeature::getTotal(...)`
    - Migration: Injection dans le constructeur

7. **StudentPaymentStatusController.php** (1 occurrence)
    - Ligne 28: `PaymentFeature::getSinglePaymentForStudentWithMonth(...)`
    - Migration: Injection dans le constructeur

#### 🏗️ Modèles (Priorité moyenne)

8. **CategoryFee.php** (2 occurrences)

    - Ligne 63: `PaymentFeature::getTotal(...)`
    - Ligne 80: `PaymentFeature::getTotal(...)`
    - ⚠️ **Attention** : Appelé dans des méthodes du modèle
    - Migration: Utiliser `app(IPayment::class)->getTotal(...)` ou injecter dans les contrôleurs

9. **Registration.php** (2 occurrences)
    - Ligne 235: `PaymentFeature::getSinglePaymentForStudentWithMonth(...)`
    - Ligne 247: `PaymentFeature::getSinglePaymentForStudentWithTranche(...)`
    - ⚠️ **Attention** : Appelé dans des méthodes du modèle
    - Migration: Utiliser `app(IPayment::class)->getMethod(...)` ou refactoriser

#### ℹ️ Autres Features (Basse priorité)

10. **FormStudentPage.php** (1 occurrence)

    -   Ligne 53: `OtherPaymentFeature::createPaymentForRegistration($registration)`
    -   Note: C'est `OtherPaymentFeature`, pas `PaymentFeature` - peut rester statique pour l'instant

11. **NewRegistrationForm.php** (1 occurrence)
    -   Ligne 76: `OtherPaymentFeature::createPaymentForRegistration($registration)`
    -   Note: C'est `OtherPaymentFeature`, pas `PaymentFeature` - peut rester statique pour l'instant

---

## 📝 Exemples de Migration

### Pour Livewire Component

#### ❌ Avant

```php
class ListPaymentByDatePage extends Component
{
    public function render()
    {
        return view('...', [
            'payments' => PaymentFeature::getList(...),
        ]);
    }
}
```

#### ✅ Après

```php
class ListPaymentByDatePage extends Component
{
    private IPayment $paymentFeature;

    public function boot(IPayment $paymentFeature): void
    {
        $this->paymentFeature = $paymentFeature;
    }

    public function render()
    {
        return view('...', [
            'payments' => $this->paymentFeature->getList(...),
        ]);
    }
}
```

### Pour Contrôleur

#### ❌ Avant

```php
class PrintPaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = PaymentFeature::getList(...);
        return view('...', compact('payments'));
    }
}
```

#### ✅ Après

```php
class PrintPaymentController extends Controller
{
    public function __construct(
        private IPayment $paymentFeature
    ) {}

    public function index(Request $request)
    {
        $payments = $this->paymentFeature->getList(...);
        return view('...', compact('payments'));
    }
}
```

### Pour Modèle

#### ❌ Avant (dans CategoryFee.php)

```php
public function getTotalAmountForMonth(string $month): float
{
    return PaymentFeature::getTotal($month, null, $this->id, ...);
}
```

#### ✅ Après (Option 1 - Helper app())

```php
public function getTotalAmountForMonth(string $month): float
{
    return app(IPayment::class)->getTotal($month, null, $this->id, ...);
}
```

#### ✅ Après (Option 2 - Refactoriser)

```php
// Dans le modèle - juste retourner les données brutes
public function getPaymentsForMonth(string $month)
{
    return $this->payments()->where('month', $month)->where('is_paid', true);
}

// Dans le contrôleur - calculer le total
public function show(CategoryFee $categoryFee, IPayment $paymentFeature)
{
    $total = $paymentFeature->getTotal($month, null, $categoryFee->id, ...);
}
```

---

## ⚡ Script de Migration Rapide

Rechercher tous les appels statiques :

```bash
grep -r "PaymentFeature::" app/
```

Vérifier après migration :

```bash
grep -r "PaymentFeature::" app/ | grep -v "OtherPaymentFeature"
```

---

## 🧪 Tests après Migration

Pour chaque fichier migré, tester :

1. ✅ Aucune erreur de syntaxe

    ```bash
    php artisan route:list
    ```

2. ✅ L'injection fonctionne

    - Tester la page/endpoint dans le navigateur/Postman

3. ✅ Les données s'affichent correctement

    - Vérifier que les filtres fonctionnent
    - Vérifier que les totaux sont corrects

4. ✅ Le cache fonctionne
    - Vérifier Laravel Debugbar : nombre de requêtes réduit
    - Vérifier les logs Redis

---

## 📊 Priorités

1. **Phase 1** : Composants Livewire affichant des listes (3 fichiers)
2. **Phase 2** : Contrôleurs API et Print (4 fichiers)
3. **Phase 3** : Forms Livewire (1 fichier)
4. **Phase 4** : Modèles (2 fichiers - nécessite réflexion sur architecture)

---

## ⚠️ Points d'Attention

### Livewire V3

-   ✅ Utiliser `boot()` pour injection
-   ❌ Pas de `mount()` pour injection (sauf paramètres de route)
-   ✅ Propriétés privées OK pour services

### Modèles Eloquent

-   ⚠️ Éviter l'injection dans les modèles
-   ✅ Préférer `app(IPayment::class)` si absolument nécessaire
-   ✅ Mieux : Déplacer la logique dans des contrôleurs/services

### Cache

-   Le repository gère automatiquement le cache
-   Pas besoin de `Cache::remember()` manuel
-   Cache invalidé automatiquement sur create/update/delete

---

**Dernière mise à jour** : 24 novembre 2025
**Par** : Migration automatique vers Repository Pattern
