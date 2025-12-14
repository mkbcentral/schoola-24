# Dashboard Financier - Documentation

## Vue d'ensemble

Le module **Dashboard Financier** fournit une vue d'ensemble complète des recettes (paiements) et des dépenses de l'école, avec des graphiques interactifs et des statistiques en temps réel.

## Architecture

### Fichiers créés

```
app/
├── Services/
│   └── FinancialDashboardService.php           # Service métier pour les calculs
├── Livewire/
│   └── Application/
│       └── Dashboard/
│           └── Finance/
│               └── FinancialDashboardPage.php  # Composant Livewire
resources/
└── views/
    └── livewire/
        └── application/
            └── dashboard/
                └── finance/
                    └── financial-dashboard-page.blade.php  # Vue Blade
routes/
└── web.php                                      # Route ajoutée
```

## Fonctionnalités

### 1. **Statistiques en temps réel**

Le dashboard affiche trois cartes principales :

- **Recettes (Minerval)** : Total des paiements reçus
- **Dépenses sur Frais** : Total des dépenses liées aux frais scolaires
- **Solde** : Différence entre recettes et dépenses

Chaque carte affiche les montants en **USD** et **CDF** séparément.

### 2. **Filtres disponibles**

- **Mois** : Filtrer les données par mois spécifique
- **Date spécifique** : Filtrer par une date précise
- **Catégorie** : Filtrer par catégorie de frais (Minerval par défaut)
- **Réinitialiser** : Réinitialiser tous les filtres

### 3. **Graphiques interactifs**

Le dashboard comprend trois types de graphiques :

#### a) Évolution mensuelle (USD)
- Graphique en ligne montrant l'évolution des recettes, dépenses et solde en USD sur 12 mois

#### b) Évolution mensuelle (CDF)
- Graphique en ligne montrant l'évolution des recettes, dépenses et solde en CDF sur 12 mois

#### c) Comparaison annuelle
- Graphique en barres comparant les recettes et dépenses mensuelles (USD)

### 4. **Données calculées**

Le dashboard calcule automatiquement :
- Total des recettes par devise (USD et CDF)
- Total des dépenses par devise (USD et CDF)
- Solde net = Recettes - Dépenses
- Données mensuelles pour les graphiques (12 mois)

## Utilisation

### Accès au dashboard

```
URL: /finance/dashboard
Route Name: finance.dashboard
```

### Service FinancialDashboardService

Le service fournit plusieurs méthodes publiques :

```php
// Obtenir les données du dashboard avec filtres
$service->getDashboardData([
    'month' => '01',  // Optionnel
    'date' => '2024-01-15',  // Optionnel
]);

// Obtenir les données mensuelles pour graphiques
$service->getMonthlyChartData($year, $categoryId);

// Obtenir les catégories disponibles
$service->getAvailableCategories();

// Obtenir des statistiques détaillées
$service->getDetailedStatistics($filters);
```

## Structure des données

### Réponse de getDashboardData()

```php
[
    'revenues' => [
        'usd' => 15000.00,
        'cdf' => 25000000.00,
        'total' => 15025000.00
    ],
    'expenses' => [
        'usd' => 8000.00,
        'cdf' => 12000000.00,
        'total' => 8012000.00
    ],
    'balance' => [
        'usd' => 7000.00,
        'cdf' => 13000000.00
    ],
    'minerval_category_id' => 1
]
```

### Réponse de getMonthlyChartData()

```php
[
    'labels' => ['Jan', 'Fév', 'Mar', ...],
    'revenues' => [
        'usd' => [1200, 1500, 1800, ...],
        'cdf' => [2000000, 2500000, 3000000, ...]
    ],
    'expenses' => [
        'usd' => [800, 900, 1000, ...],
        'cdf' => [1500000, 1600000, 1700000, ...]
    ],
    'balance' => [
        'usd' => [400, 600, 800, ...],
        'cdf' => [500000, 900000, 1300000, ...]
    ]
]
```

## Logique métier

### Calcul des recettes

Les recettes sont calculées à partir de la table `payments` :
- Jointure avec `scolar_fees`, `category_fees` et `registrations`
- Filtrage par année scolaire active
- Uniquement les paiements marqués comme payés (`is_paid = true`)
- Agrégation par devise (USD et CDF)

### Calcul des dépenses

Les dépenses sont calculées à partir de la table `expense_fees` :
- Filtrage par année scolaire active
- Filtrage par catégorie de frais (Minerval par défaut)
- Agrégation par devise (USD et CDF)

### Catégorie par défaut (Minerval)

Le système utilise la première catégorie marquée avec `is_for_dash = true` comme catégorie par défaut. Cette catégorie représente généralement le **Minerval** (frais de scolarité).

## Technologies utilisées

- **Backend** : Laravel 11 + Livewire 3
- **Frontend** : Chart.js 4.4.0
- **Styling** : Bootstrap 5 + Bootstrap Icons

## Composant Livewire

### Propriétés publiques

```php
public $month_filter;              // Filtre par mois
public $date_filter;               // Filtre par date
public $category_fee_id_filter;    // Filtre par catégorie

// Statistiques
public $total_revenue_usd;
public $total_revenue_cdf;
public $total_expense_usd;
public $total_expense_cdf;
public $balance_usd;
public $balance_cdf;

// Données graphiques
public $chartData;
```

### Méthodes

- `mount()` : Initialisation du composant
- `updatedMonthFilter()` : Recharge les données lors du changement de mois
- `updatedDateFilter()` : Recharge les données lors du changement de date
- `updatedCategoryFeeIdFilter()` : Recharge les données lors du changement de catégorie
- `resetFilters()` : Réinitialise tous les filtres
- `loadDashboardData()` : Charge les données du service

## Personnalisation

### Modifier la catégorie par défaut

Pour changer la catégorie par défaut (Minerval), modifiez la méthode `getMinervalCategoryId()` dans `FinancialDashboardService.php` :

```php
private function getMinervalCategoryId(): ?int
{
    $category = CategoryFee::where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
        ->where('name', 'Votre catégorie')  // Modifier ici
        ->first();

    return $category?->id;
}
```

### Ajouter des filtres supplémentaires

Pour ajouter d'autres filtres (ex: section, option) :

1. Ajouter la propriété publique dans le composant Livewire
2. Ajouter le filtre dans la méthode `calculateRevenues()` et `calculateExpenses()`
3. Ajouter le champ de filtre dans la vue Blade

### Personnaliser les graphiques

Les options de Chart.js peuvent être modifiées dans la section `@push('scripts')` de la vue Blade :

```javascript
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    // Ajouter vos options ici
};
```

## Performance

### Optimisations implémentées

1. **Agrégation au niveau de la base de données** : Les totaux sont calculés via SQL `SUM()` et `GROUP BY`
2. **Requêtes distinctes par devise** : Évite les calculs en PHP
3. **Eager Loading** : Les relations sont chargées efficacement via PaymentRepository
4. **Mise en cache potentielle** : Le service peut être étendu avec du cache Redis/Memcached

### Suggestions d'amélioration

Pour les grandes volumétries de données :

```php
// Ajouter du cache dans FinancialDashboardService
use Illuminate\Support\Facades\Cache;

public function getDashboardData(array $filters = []): array
{
    $cacheKey = 'financial_dashboard_' . md5(json_encode($filters));
    
    return Cache::remember($cacheKey, 300, function () use ($filters) {
        // ... logique existante
    });
}
```

## Tests

### Tester le dashboard

```php
// Test unitaire du service
$service = app(FinancialDashboardService::class);
$data = $service->getDashboardData(['month' => '01']);

expect($data)->toHaveKeys(['revenues', 'expenses', 'balance']);
expect($data['revenues'])->toHaveKeys(['usd', 'cdf', 'total']);
```

### Tester le composant Livewire

```php
Livewire::test(FinancialDashboardPage::class)
    ->assertSet('month_filter', date('m'))
    ->assertViewHas('categories')
    ->call('resetFilters')
    ->assertSet('month_filter', date('m'));
```

## Dépendances

### Modèles requis
- `Payment`
- `ExpenseFee`
- `CategoryFee`
- `SchoolYear`
- `ScolarFee`
- `Registration`

### Repositories
- `PaymentRepositoryInterface` (utilisé dans le service)

### Widgets Blade
- `x-widget.list-month-fr` : Sélecteur de mois en français

## Prochaines étapes

### Améliorations possibles

1. **Export PDF/Excel** : Ajouter un bouton d'export des statistiques
2. **Comparaison multi-années** : Comparer les données sur plusieurs années scolaires
3. **Notifications** : Alertes automatiques si les dépenses dépassent les recettes
4. **Prévisions** : Utiliser l'IA pour prédire les recettes/dépenses futures
5. **Filtres avancés** : Par section, option, classe
6. **Dashboard personnalisable** : Permettre aux utilisateurs de choisir les widgets à afficher

## Support

Pour toute question ou problème :
- Vérifier que `is_for_dash = true` est défini sur au moins une CategoryFee
- Vérifier que SchoolYear::DEFAULT_SCHOOL_YEAR_ID() retourne un ID valide
- Consulter les logs Laravel pour les erreurs SQL

## Changelog

### Version 1.0.0 (2024-01-15)
- Création initiale du module
- Graphiques Chart.js interactifs
- Filtres par mois, date et catégorie
- Support multi-devises (USD/CDF)
