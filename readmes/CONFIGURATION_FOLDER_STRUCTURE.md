# Structure des dossiers de Configuration

## Vue d'ensemble

Cette documentation décrit la nouvelle structure organisationnelle des fichiers de configuration (Section, Option, ClassRoom, SchoolYear, Rate).

## Structure des dossiers

### 1. DTOs (`app/DTOs/Configuration/`)

Les DTOs de configuration sont regroupés dans le dossier `Configuration/` :

```
app/DTOs/Configuration/
├── ClassRoomDTO.php
├── OptionDTO.php
├── RateDTO.php
├── SchoolYearDTO.php
└── SectionDTO.php
```

**Namespace**: `App\DTOs\Configuration`

**Responsabilités**:

-   Transfert de données entre les couches
-   Validation des types (via PHP type hints)
-   Transformation de données (fromModel, fromRequest)
-   Immutabilité des données

**Exemple d'utilisation**:

```php
use App\DTOs\Configuration\SectionDTO;

$dto = SectionDTO::fromRequest($request);
$section = $this->sectionService->create($dto);
```

### 2. Services (`app/Services/Configuration/`)

Les services de configuration sont regroupés dans le dossier `Configuration/` :

```
app/Services/Configuration/
├── ClassRoomService.php
├── OptionService.php
├── RateService.php
├── SchoolYearService.php
└── SectionService.php
```

**Namespace**: `App\Services\Configuration`

**Responsabilités**:

-   Orchestration de la logique métier
-   Validation des données
-   Appel des Actions et Repositories
-   Gestion du cache

**Exemple d'utilisation**:

```php
use App\Services\Configuration\SectionService;

public function __construct(
    private SectionService $sectionService
) {}
```

### 3. Repositories (`app/Repositories/Configuration/`)

Les repositories de configuration sont regroupés dans le dossier `Configuration/` :

```
app/Repositories/Configuration/
├── ClassRoomRepository.php
├── OptionRepository.php
├── RateRepository.php
├── SchoolYearRepository.php
└── SectionRepository.php
```

**Namespace**: `App\Repositories\Configuration`

**Responsabilités**:

-   Accès aux données
-   Gestion du cache (TTL: 60 minutes)
-   Eager loading des relations
-   Statistiques et compteurs

**Exemple d'utilisation**:

```php
use App\Repositories\Configuration\SectionRepository;

public function __construct(
    private SectionRepository $repository
) {}
```

### 4. Livewire Components (`app/Livewire/Application/V2/Configuration/Form/`)

Les composants Livewire de formulaires sont regroupés dans le sous-dossier `Form/` :

```
app/Livewire/Application/V2/Configuration/Form/
├── ClassRoomFormOffcanvas.php
├── OptionFormOffcanvas.php
├── RateFormOffcanvas.php
├── SchoolYearFormOffcanvas.php
└── SectionFormOffcanvas.php
```

**Namespace**: `App\Livewire\Application\V2\Configuration\Form`

**Responsabilités**:

-   Gestion des formulaires offcanvas
-   Validation des entrées utilisateur
-   Communication entre composants (events)
-   Invalidation du cache

**Exemple d'utilisation dans Blade**:

```blade
@livewire('application.v2.configuration.form.section-form-offcanvas')
```

### 5. Vues Blade (`resources/views/livewire/application/v2/configuration/form/`)

Les vues Blade des formulaires sont regroupées dans le sous-dossier `form/` :

```
resources/views/livewire/application/v2/configuration/form/
├── class-room-form-offcanvas.blade.php
├── option-form-offcanvas.blade.php
├── rate-form-offcanvas.blade.php
├── school-year-form-offcanvas.blade.php
└── section-form-offcanvas.blade.php
```

**Responsabilités**:

-   Interface utilisateur des formulaires
-   Intégration Bootstrap 5 (offcanvas)
-   Composants réutilisables (x-form.input, x-form.button)

## Architecture globale

```
┌─────────────────────────────────────────┐
│          Blade Views (form/)            │
│  (Bootstrap 5 Offcanvas UI)             │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│     Livewire Components (Form/)         │
│  (Form Logic + Event Handling)          │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│    Services (Configuration/)            │
│  (Business Logic + Validation)          │
└─────────────────┬───────────────────────┘
                  │
          ┌───────┴────────┐
          │                │
┌─────────▼─────┐  ┌───────▼──────────┐
│    Actions    │  │   Repositories   │
│   (CRUD ops)  │  │ (Configuration/) │
│ + DTOs (Conf.)│  └─────────┬────────┘
└───────────────┘            │
                   ┌─────────▼─────────┐
                   │   Models (DB)     │
                   └───────────────────┘
```

## Avantages de cette structure

### 1. **Organisation claire**

-   Tous les fichiers de configuration sont regroupés
-   Séparation entre formulaires et pages principales
-   Namespace explicite reflétant la structure

### 2. **Maintenabilité**

-   Facile de localiser les fichiers liés à la configuration
-   Réduction du nombre de fichiers à la racine des dossiers
-   Convention de nommage cohérente

### 3. **Scalabilité**

-   Ajout facile de nouvelles entités de configuration
-   Structure prête pour d'autres modules (Payment, Registration, etc.)
-   Pattern réutilisable

### 4. **Cohérence**

-   Même structure pour tous les modules de configuration
-   Import statements clairs et explicites
-   Découverte facile via IDE autocomplete

## Exemples de code

### Import dans un Service

```php
<?php

namespace App\Services\Configuration;

use App\Actions\Section\CreateSectionAction;
use App\Repositories\Configuration\SectionRepository;
use App\DTOs\Configuration\SectionDTO;

class SectionService
{
    public function __construct(
        private SectionRepository $repository,
        private CreateSectionAction $createAction
    ) {}
}
```

### Import dans un Livewire Component

```php
<?php

namespace App\Livewire\Application\V2\Configuration\Form;

use App\Services\Configuration\SectionService;
use Livewire\Component;

class SectionFormOffcanvas extends Component
{
    public function __construct(
        private SectionService $sectionService
    ) {}
}
```

### Import dans une page de gestion

```php
<?php

namespace App\Livewire\Application\V2\Configuration;

use App\Services\Configuration\SectionService;
use App\Services\Configuration\OptionService;
use App\Services\Configuration\ClassRoomService;
use Livewire\Component;

class SectionManagementPage extends Component
{
    public function __construct(
        private SectionService $sectionService,
        private OptionService $optionService,
        private ClassRoomService $classRoomService
    ) {}
}
```

## Migration

Tous les imports ont été mis à jour automatiquement :

-   ✅ DTOs: `App\DTOs\SectionDTO` → `App\DTOs\Configuration\SectionDTO`
-   ✅ Services: `App\Services\SectionService` → `App\Services\Configuration\SectionService`
-   ✅ Repositories: `App\Repositories\SectionRepository` → `App\Repositories\Configuration\SectionRepository`
-   ✅ Tous les composants Livewire Form
-   ✅ Toutes les Actions (Create, Update, Delete)
-   ✅ Toutes les pages de management

## Routes

Les routes restent inchangées :

-   `/config/manage/v2` - Configuration générale (SchoolYear, Rate)
-   `/config/section-manage/v2` - Gestion Section/Option/ClassRoom

## Cache

Le système de cache reste identique avec :

-   TTL: 60 minutes
-   Invalidation automatique lors des create/update/delete
-   Keys: `sections_all`, `options_all`, `classrooms_all`, etc.

## Events Livewire

Les events de communication entre composants :

-   `sectionSaved` - Section créée/modifiée
-   `optionSaved` - Option créée/modifiée
-   `classRoomSaved` - ClassRoom créée/modifiée

Ces events permettent la mise à jour automatique des listes déroulantes et tableaux.
