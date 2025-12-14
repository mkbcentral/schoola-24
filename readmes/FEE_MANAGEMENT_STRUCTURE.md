# Structure des Frais (Fee Management)

## Vue d'ensemble

Cette documentation décrit la structure complète pour la gestion des frais scolaires et d'inscription (CategoryRegistrationFee, CategoryFee, ScolarFee, RegistrationFee).

## Structure des dossiers

### 1. DTOs (`app/DTOs/Fee/`)

Les DTOs pour la gestion des frais :

```
app/DTOs/Fee/
├── CategoryRegistrationFeeDTO.php
├── CategoryFeeDTO.php
├── ScolarFeeDTO.php
└── RegistrationFeeDTO.php
```

**Namespace**: `App\DTOs\Fee`

**Responsabilités**:

- Transfert de données entre les couches
- Validation des types (via PHP type hints)
- Transformation de données (fromModel, fromRequest)
- Conversion en tableaux pour création/mise à jour

**Exemple d'utilisation**:

```php
use App\DTOs\Fee\CategoryRegistrationFeeDTO;

$dto = CategoryRegistrationFeeDTO::fromRequest($request->all());
$category = $this->categoryRegistrationFeeService->create($dto);
```

### 2. Services (`app/Services/Fee/`)

Les services de gestion des frais :

```
app/Services/Fee/
├── CategoryRegistrationFeeService.php
├── CategoryFeeService.php
├── ScolarFeeService.php
└── RegistrationFeeService.php
```

**Namespace**: `App\Services\Fee`

**Responsabilités**:

- Orchestration de la logique métier
- Validation de l'unicité des données
- Appel des Actions et Repositories
- Gestion du cache et logging

**Exemple d'utilisation**:

```php
use App\Services\Fee\CategoryFeeService;

public function __construct(
    private CategoryFeeService $categoryFeeService
) {}

$categoryFee = $this->categoryFeeService->create($dto);
```

### 3. Repositories (`app/Repositories/Fee/`)

Les repositories pour l'accès aux données :

```
app/Repositories/Fee/
├── CategoryRegistrationFeeRepository.php
├── CategoryFeeRepository.php
├── ScolarFeeRepository.php
└── RegistrationFeeRepository.php
```

**Namespace**: `App\Repositories\Fee`

**Responsabilités**:

- Accès aux données avec cache (TTL: 60 minutes)
- Eager loading des relations
- Requêtes optimisées et filtrées
- Statistiques et compteurs

**Exemple d'utilisation**:

```php
use App\Repositories\Fee\CategoryFeeRepository;

public function __construct(
    private CategoryFeeRepository $repository
) {}

$categories = $this->repository->getBySchoolYear($schoolYearId);
```

### 4. Actions

Les actions pour les opérations CRUD :

```
app/Actions/
├── CategoryRegistrationFee/
│   ├── CreateCategoryRegistrationFeeAction.php
│   ├── UpdateCategoryRegistrationFeeAction.php
│   └── DeleteCategoryRegistrationFeeAction.php
├── CategoryFee/
│   ├── CreateCategoryFeeAction.php
│   ├── UpdateCategoryFeeAction.php
│   └── DeleteCategoryFeeAction.php
├── ScolarFee/
│   ├── CreateScolarFeeAction.php
│   ├── UpdateScolarFeeAction.php
│   └── DeleteScolarFeeAction.php
└── RegistrationFee/
    ├── CreateRegistrationFeeAction.php
    ├── UpdateRegistrationFeeAction.php
    └── DeleteRegistrationFeeAction.php
```

**Namespaces**:

- `App\Actions\CategoryRegistrationFee`
- `App\Actions\CategoryFee`
- `App\Actions\ScolarFee`
- `App\Actions\RegistrationFee`

**Responsabilités**:

- Exécution des opérations atomiques
- Transactions de base de données
- Création/Mise à jour/Suppression des entités

## Entités et leurs relations

### 1. CategoryRegistrationFee (Catégorie de frais d'inscription)

**Champs**:

- `id`: Identifiant unique
- `name`: Nom de la catégorie
- `is_old`: Indicateur ancien/nouveau
- `school_id`: École associée

**Relations**:

- `hasMany` RegistrationFee

**Méthodes du Service**:

- `create(CategoryRegistrationFeeDTO $dto)`: Créer
- `update(int $id, CategoryRegistrationFeeDTO $dto)`: Mettre à jour
- `delete(int $id)`: Supprimer
- `getBySchool(int $schoolId)`: Par école
- `getByOldStatus(int $schoolId, bool $isOld)`: Par statut
- `getStatistics(int $schoolId)`: Statistiques

### 2. CategoryFee (Catégorie de frais scolaires)

**Champs**:

- `id`: Identifiant unique
- `name`: Nom de la catégorie
- `school_year_id`: Année scolaire
- `school_id`: École associée
- `is_state_fee`: Frais d'état
- `currency`: Devise (USD/CDF)
- `is_paid_in_installment`: Paiement échelonné
- `is_paid_for_registration`: Payé à l'inscription
- `is_for_dash`: Pour tableau de bord
- `is_accessory`: Accessoire

**Relations**:

- `belongsTo` SchoolYear
- `hasMany` ScolarFee

**Méthodes du Service**:

- `create(CategoryFeeDTO $dto)`: Créer
- `update(int $id, CategoryFeeDTO $dto)`: Mettre à jour
- `delete(int $id)`: Supprimer
- `getBySchool(int $schoolId)`: Par école
- `getBySchoolYear(int $schoolYearId)`: Par année scolaire
- `getStateFees(int $schoolYearId)`: Frais d'état
- `getStatistics(int $schoolId)`: Statistiques

### 3. ScolarFee (Frais scolaire)

**Champs**:

- `id`: Identifiant unique
- `name`: Nom du frais
- `amount`: Montant
- `category_fee_id`: Catégorie de frais
- `class_room_id`: Classe
- `is_changed`: Modifié

**Relations**:

- `belongsTo` CategoryFee
- `belongsTo` ClassRoom
- `hasMany` Payment

**Méthodes du Service**:

- `create(ScolarFeeDTO $dto)`: Créer
- `update(int $id, ScolarFeeDTO $dto)`: Mettre à jour
- `delete(int $id)`: Supprimer
- `getByCategoryFee(int $categoryFeeId)`: Par catégorie
- `getByClassRoom(int $classRoomId)`: Par classe
- `getChanged()`: Frais modifiés
- `getStatistics(int $categoryFeeId)`: Statistiques

### 4. RegistrationFee (Frais d'inscription)

**Champs**:

- `id`: Identifiant unique
- `name`: Nom du frais
- `amount`: Montant
- `option_id`: Option
- `category_registration_fee_id`: Catégorie
- `school_year_id`: Année scolaire
- `currency`: Devise (USD/CDF)

**Relations**:

- `belongsTo` Option
- `belongsTo` CategoryRegistrationFee
- `hasMany` Registration

**Méthodes du Service**:

- `create(RegistrationFeeDTO $dto)`: Créer
- `update(int $id, RegistrationFeeDTO $dto)`: Mettre à jour
- `delete(int $id)`: Supprimer
- `getByOption(int $optionId)`: Par option
- `getByCategoryRegistrationFee(int $categoryId)`: Par catégorie
- `getBySchoolYear(int $schoolYearId)`: Par année scolaire
- `getStatistics(int $categoryId)`: Statistiques

## Architecture globale

```
┌─────────────────────────────────────────┐
│     Livewire Components (à venir)       │
│       (Form + Management Pages)         │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│         Services (Fee/)                 │
│  (Business Logic + Validation)          │
└─────────────────┬───────────────────────┘
                  │
          ┌───────┴────────┐
          │                │
┌─────────▼─────┐  ┌───────▼──────────┐
│    Actions    │  │   Repositories   │
│   (CRUD ops)  │  │      (Fee/)      │
│ + DTOs (Fee/) │  └─────────┬────────┘
└───────────────┘            │
                   ┌─────────▼─────────┐
                   │   Models (DB)     │
                   │ - CategoryRegis.. │
                   │ - CategoryFee     │
                   │ - ScolarFee       │
                   │ - RegistrationFee │
                   └───────────────────┘
```

## Fonctionnalités principales

### Gestion du cache

- **TTL**: 60 minutes pour toutes les requêtes
- **Invalidation**: Automatique lors des create/update/delete
- **Clés de cache**:
  - `category_registration_fees_all`
  - `category_fees_all`
  - `scolar_fees_all`
  - `registration_fees_all`
  - Clés spécifiques par école/année/catégorie

### Validation

- **Unicité**: Vérification avant création/modification
- **Existence**: Validation des relations (school, option, classRoom)
- **Logging**: Tous les événements sont loggés

### Statistiques

Chaque service fournit des méthodes de statistiques :

- Comptage total
- Montants totaux
- Répartition par catégorie/option/classe

## Exemples d'utilisation

### Créer une catégorie de frais d'inscription

```php
use App\Services\Fee\CategoryRegistrationFeeService;
use App\DTOs\Fee\CategoryRegistrationFeeDTO;

class MyController
{
    public function __construct(
        private CategoryRegistrationFeeService $service
    ) {}

    public function store(Request $request)
    {
        $dto = CategoryRegistrationFeeDTO::fromRequest([
            'name' => $request->name,
            'is_old' => $request->is_old,
            'school_id' => auth()->user()->school_id,
        ]);

        $category = $this->service->create($dto);

        if (!$category) {
            return back()->with('error', 'Catégorie déjà existante');
        }

        return back()->with('success', 'Catégorie créée avec succès');
    }
}
```

### Récupérer les frais scolaires par classe

```php
use App\Services\Fee\ScolarFeeService;

class PaymentController
{
    public function __construct(
        private ScolarFeeService $scolarFeeService
    ) {}

    public function getFeesForClassRoom(int $classRoomId)
    {
        $fees = $this->scolarFeeService->getByClassRoom($classRoomId);

        return view('payment.fees', compact('fees'));
    }
}
```

### Obtenir les statistiques

```php
use App\Services\Fee\CategoryFeeService;

$stats = $this->categoryFeeService->getStatistics($schoolId);

// Résultat:
// [
//     'total' => 15,
//     'state_fees' => 5,
//     'installment_fees' => 8,
// ]
```

## Points importants

### 1. Gestion des devises

- `CategoryFee` et `RegistrationFee` supportent USD et CDF
- Configurable au niveau de chaque frais

### 2. Frais d'état

- `CategoryFee::is_state_fee` identifie les frais gouvernementaux
- Filtrage disponible via `getStateFees()`

### 3. Paiement échelonné

- `CategoryFee::is_paid_in_installment` pour les paiements en plusieurs fois
- Utilisé dans la logique de paiement

### 4. Relations importantes

- **CategoryRegistrationFee** → **RegistrationFee** (1:N)
- **CategoryFee** → **ScolarFee** (1:N)
- **ScolarFee** → **ClassRoom** (N:1)
- **RegistrationFee** → **Option** (N:1)

## Prochaines étapes

- [ ] Créer les composants Livewire pour les formulaires
- [ ] Créer les pages de gestion
- [ ] Ajouter les routes
- [ ] Créer les vues Blade avec offcanvas
- [ ] Implémenter les événements Livewire pour la synchronisation
- [ ] Ajouter les tests unitaires

## Fichiers créés

### DTOs (4 fichiers)

✅ CategoryRegistrationFeeDTO.php
✅ CategoryFeeDTO.php
✅ ScolarFeeDTO.php
✅ RegistrationFeeDTO.php

### Services (4 fichiers)

✅ CategoryRegistrationFeeService.php
✅ CategoryFeeService.php
✅ ScolarFeeService.php
✅ RegistrationFeeService.php

### Repositories (4 fichiers)

✅ CategoryRegistrationFeeRepository.php
✅ CategoryFeeRepository.php
✅ ScolarFeeRepository.php
✅ RegistrationFeeRepository.php

### Actions (12 fichiers - 3 par entité)

✅ Create/Update/Delete pour CategoryRegistrationFee
✅ Create/Update/Delete pour CategoryFee
✅ Create/Update/Delete pour ScolarFee
✅ Create/Update/Delete pour RegistrationFee

**Total**: 24 fichiers backend créés avec succès! 🎉
