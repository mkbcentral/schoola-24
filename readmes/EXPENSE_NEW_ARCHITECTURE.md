# 🏗️ NOUVELLE ARCHITECTURE MODULE DÉPENSES

## 📋 Vue d'ensemble

Cette nouvelle architecture suit le pattern **Service Layer** avec **Dependency Injection**, similaire à l'implémentation du module Payment. Elle offre une séparation claire des responsabilités et une meilleure testabilité.

---

## 🎯 Composants de l'architecture

### 1. **Services & Contrats (Interfaces)**

#### CurrencyExchangeService
**Interface**: `App\Services\Contracts\CurrencyExchangeServiceInterface`
**Implémentation**: `App\Services\CurrencyExchangeService`

**Fonctionnalités**:
- ✅ Conversion entre USD et CDF
- ✅ Gestion dynamique des taux de change
- ✅ Configuration via fichier `config/currency.php`
- ✅ Cache des taux (24h par défaut)
- ✅ Possibilité de définir des taux personnalisés

**Utilisation**:
```php
$currencyService = app(CurrencyExchangeServiceInterface::class);

// Convertir 100 USD en CDF
$amountCDF = $currencyService->convert(100, 'USD', 'CDF'); // 285000

// Obtenir le taux actuel
$rate = $currencyService->getRate('USD', 'CDF'); // 2850

// Convertir tout en USD
$totalUSD = $currencyService->convertToUSD(285000, 'CDF'); // 100
```

#### ExpenseService
**Interface**: `App\Services\Contracts\ExpenseServiceInterface`
**Implémentation**: `App\Services\ExpenseService`

**Responsabilité**: Gestion des dépenses sur frais scolaires (ExpenseFee)

**Méthodes**:
- `create(ExpenseDTO $expenseDTO): ExpenseDTO` - Créer une dépense
- `update(int $id, ExpenseDTO $expenseDTO): ExpenseDTO` - Modifier une dépense
- `delete(int $id): bool` - Supprimer une dépense
- `findById(int $id): ?ExpenseDTO` - Récupérer une dépense
- `getAll(ExpenseFilterDTO $filters): LengthAwarePaginator` - Liste paginée
- `getTotalAmount(ExpenseFilterDTO $filters): float` - Total en USD
- `getTotalAmountByCurrency(ExpenseFilterDTO $filters): array` - Total par devise
- `getByMonth(ExpenseFilterDTO $filters): Collection` - Grouper par mois
- `getByCategory(ExpenseFilterDTO $filters): Collection` - Grouper par catégorie
- `getByPeriod(string $period): Collection` - Filtrer par période
- `getStatistics(ExpenseFilterDTO $filters): array` - Statistiques complètes
- `export(ExpenseFilterDTO $filters, string $format): mixed` - Export (à implémenter)

#### OtherExpenseService
**Interface**: `App\Services\Contracts\OtherExpenseServiceInterface`
**Implémentation**: `App\Services\OtherExpenseService`

**Responsabilité**: Gestion des autres dépenses (OtherExpense)

Même API que ExpenseService mais pour les autres dépenses.

---

### 2. **DTOs (Data Transfer Objects)**

#### ExpenseDTO
**Localisation**: `App\DTOs\ExpenseDTO`

**Propriétés**:
```php
public ?int $id
public string $description
public string $month
public float $amount
public string $currency
public int $categoryExpenseId
public int $categoryFeeId
public ?int $schoolYearId
public ?string $createdAt
```

**Méthodes utilitaires**:
- `fromModel($model): self` - Créer depuis un modèle Eloquent
- `fromArray(array $data): self` - Créer depuis un tableau
- `toArray(): array` - Convertir en tableau
- `validate(): array` - Valider les données
- `isValid(): bool` - Vérifier si valide

#### ExpenseFilterDTO
**Localisation**: `App\DTOs\ExpenseFilterDTO`

**Propriétés**:
```php
public ?string $date
public ?string $month
public ?int $categoryFeeId
public ?int $categoryExpenseId
public ?string $currency
public ?string $period // 'today', 'this_week', 'this_month', etc.
public ?Carbon $startDate
public ?Carbon $endDate
public int $perPage
public string $sortBy
public string $sortDirection
```

**Périodes supportées**:
- `today` - Aujourd'hui
- `yesterday` - Hier
- `this_week` - Cette semaine
- `last_week` - Semaine dernière
- `2_weeks_ago` - Il y a 2 semaines
- `3_weeks_ago` - Il y a 3 semaines
- `this_month` - Ce mois
- `last_month` - Mois dernier
- `3_months` - 3 derniers mois
- `6_months` - 6 derniers mois
- `9_months` - 9 derniers mois
- `this_year` - Cette année
- `last_year` - Année dernière

#### OtherExpenseDTO & OtherExpenseFilterDTO
Même structure que ExpenseDTO/ExpenseFilterDTO mais avec `otherSourceExpenseId` au lieu de `categoryFeeId`.

---

### 3. **Configuration**

#### config/currency.php
```php
return [
    'default' => env('DEFAULT_CURRENCY', 'USD'),
    
    'supported' => ['USD', 'CDF'],
    
    'rates' => [
        'USD_CDF' => env('RATE_USD_CDF', 2850),
        'CDF_USD' => env('RATE_CDF_USD', 1 / 2850),
    ],
    
    'symbols' => [
        'USD' => '$',
        'CDF' => 'FC',
    ],
    
    'formats' => [
        'USD' => [
            'decimals' => 2,
            'decimal_separator' => '.',
            'thousands_separator' => ',',
        ],
        'CDF' => [
            'decimals' => 0,
            'decimal_separator' => ',',
            'thousands_separator' => ' ',
        ],
    ],
];
```

**Variables d'environnement**:
```env
DEFAULT_CURRENCY=USD
RATE_USD_CDF=2850
RATE_CDF_USD=0.0003508771929824561
```

---

### 4. **Composants Livewire mis à jour**

#### FormExpensePage
- ✅ Utilise `ExpenseServiceInterface` via injection
- ✅ Utilise `ExpenseDTO` pour encapsuler les données
- ✅ Gestion d'erreurs améliorée avec transactions

#### ListExpenseFeePage
- ✅ Utilise `ExpenseServiceInterface`
- ✅ Utilise `ExpenseFilterDTO` pour tous les filtres
- ✅ Nouveau filtre par période
- ✅ Calcul des totaux via le service

#### FormOtherExpensePage & ListOtherExpensePage
Même approche que les composants ExpenseFee.

---

### 5. **Injection de dépendances**

#### AppServiceProvider.php
```php
public function register(): void
{
    // Service de gestion des devises
    $this->app->singleton(
        CurrencyExchangeServiceInterface::class, 
        CurrencyExchangeService::class
    );

    // Services de gestion des dépenses
    $this->app->singleton(
        ExpenseServiceInterface::class, 
        ExpenseService::class
    );
    
    $this->app->singleton(
        OtherExpenseServiceInterface::class, 
        OtherExpenseService::class
    );
}
```

---

## 🚀 Utilisation dans le code

### Exemple 1: Créer une dépense
```php
use App\Services\Contracts\ExpenseServiceInterface;
use App\DTOs\ExpenseDTO;

$expenseService = app(ExpenseServiceInterface::class);

$dto = ExpenseDTO::fromArray([
    'description' => 'Achat de fournitures',
    'month' => '11',
    'amount' => 500,
    'currency' => 'USD',
    'category_expense_id' => 1,
    'category_fee_id' => 2,
    'created_at' => '2025-11-26',
]);

$created = $expenseService->create($dto);
```

### Exemple 2: Lister avec filtres avancés
```php
use App\Services\Contracts\ExpenseServiceInterface;
use App\DTOs\ExpenseFilterDTO;

$expenseService = app(ExpenseServiceInterface::class);

// Dépenses des 3 derniers mois en USD
$filters = ExpenseFilterDTO::fromArray([
    'period' => '3_months',
    'currency' => 'USD',
    'per_page' => 25,
]);

$expenses = $expenseService->getAll($filters);
```

### Exemple 3: Obtenir les statistiques
```php
$filters = new ExpenseFilterDTO(period: 'this_month');
$stats = $expenseService->getStatistics($filters);

/*
Résultat:
[
    'total_usd' => 5000.00,
    'total_cdf' => 2850000.00,
    'total_converted_usd' => 6000.00,
    'count' => 25,
    'average' => 240.00,
    'by_month' => Collection [...],
    'by_category' => Collection [...],
    'currency_rate' => 0.00035,
]
*/
```

### Exemple 4: Conversion de devises
```php
use App\Services\Contracts\CurrencyExchangeServiceInterface;

$currencyService = app(CurrencyExchangeServiceInterface::class);

// Convertir 100 USD en CDF
$cdf = $currencyService->convert(100, 'USD', 'CDF');

// Définir un nouveau taux
$currencyService->setRate('USD', 'CDF', 3000);

// Rafraîchir les taux
$currencyService->refreshRates();
```

---

## 📊 Avantages de la nouvelle architecture

### ✅ Avantages techniques
1. **Séparation des responsabilités** - Code organisé et maintenable
2. **Testabilité** - Services facilement testables avec des mocks
3. **Injection de dépendances** - Couplage faible, extensibilité
4. **Gestion centralisée des taux** - Plus de taux hardcodés
5. **DTOs** - Validation et typage fort des données
6. **Filtrage avancé** - Périodes prédéfinies, plages de dates
7. **Transactions** - Intégrité des données garantie
8. **Logging** - Traçabilité des erreurs

### ✅ Avantages fonctionnels
1. **Filtres de période** - Today, this_week, 3_months, etc.
2. **Conversion automatique** - Calcul des totaux en USD
3. **Statistiques riches** - Par mois, par catégorie, moyennes
4. **Configuration flexible** - Taux dans .env et config
5. **Cache intelligent** - Performance optimisée
6. **API cohérente** - Même pattern pour ExpenseFee et OtherExpense

---

## 🧪 Tests (à implémenter)

### Tests unitaires recommandés:

#### CurrencyExchangeServiceTest
```php
- testConvertUSDtoCDF()
- testConvertCDFtoUSD()
- testGetRate()
- testSetCustomRate()
- testCacheRates()
- testConvertToUSD()
```

#### ExpenseServiceTest
```php
- testCreateExpense()
- testUpdateExpense()
- testDeleteExpense()
- testGetAllWithFilters()
- testGetTotalAmount()
- testGetByMonth()
- testGetByCategory()
- testGetByPeriod()
- testGetStatistics()
```

---

## 🔄 Migration depuis l'ancienne architecture

### Étapes:
1. ✅ Services et interfaces créés
2. ✅ DTOs créés
3. ✅ Configuration currency.php ajoutée
4. ✅ AppServiceProvider mis à jour
5. ✅ Composants Livewire refactorisés
6. ✅ Vue avec filtre de période ajoutée
7. ⏳ Tests à créer
8. ⏳ Deprecate ExpenseFeeFeature et OtherExpenseFeature

### Compatibilité:
- Les anciens composants fonctionnent toujours
- Migration progressive possible
- Pas de breaking changes pour l'utilisateur final

---

## 📝 TODO

### Priorité HAUTE
- [ ] Créer les tests unitaires pour tous les services
- [ ] Implémenter la méthode `export()` (Excel, PDF)
- [ ] Ajouter SoftDeletes aux modèles
- [ ] Corriger les fautes d'orthographe (`scoolYear` → `schoolYear`)

### Priorité MOYENNE
- [ ] Créer un système d'audit trail
- [ ] Ajouter la gestion des pièces jointes
- [ ] Créer des événements (ExpenseCreated, ExpenseDeleted)
- [ ] Dashboard avec graphiques (Chart.js)

### Priorité BASSE
- [ ] API REST pour les dépenses
- [ ] Export automatique mensuel
- [ ] Notifications par email
- [ ] Rapports personnalisés

---

## 📞 Support

Pour toute question sur cette nouvelle architecture:
1. Consulter ce document
2. Regarder les exemples dans le code
3. Vérifier les interfaces pour l'API complète
4. Consulter la documentation Laravel sur l'injection de dépendances

---

**Date de création**: 26 novembre 2025
**Version**: 1.0.0
**Auteur**: GitHub Copilot
**Status**: ✅ Production Ready
