# Service d'Inscription (Registration Service)

## Architecture

Cette nouvelle architecture de service d'inscription suit les principes SOLID et utilise une structure en couches pour gérer les inscriptions des élèves.

### Structure des Dossiers

```
app/
├── DTOs/Registration/          # Data Transfer Objects
│   ├── CreateStudentDTO.php
│   ├── CreateRegistrationDTO.php
│   ├── UpdateRegistrationDTO.php
│   ├── RegistrationFilterDTO.php
│   └── RegistrationStatsDTO.php
├── Actions/Registration/       # Actions métier atomiques
│   ├── CreateStudentAction.php
│   ├── CreateRegistrationAction.php
│   ├── UpdateRegistrationAction.php
│   ├── DeleteRegistrationAction.php
│   └── CreateNewStudentRegistrationAction.php
├── Repositories/Registration/  # Couche d'accès aux données
│   └── RegistrationRepository.php
├── Services/Registration/      # Orchestration de la logique métier
│   └── RegistrationService.php
├── Http/Controllers/Registration/
│   └── RegistrationController.php
└── Providers/
    └── RegistrationServiceProvider.php
```

## Fonctionnalités

### 1. Inscription d'un Ancien Élève

Pour inscrire un élève déjà existant dans la base de données :

```php
use App\Services\Registration\RegistrationService;
use App\DTOs\Registration\CreateRegistrationDTO;

$registrationService = app(RegistrationService::class);

$dto = CreateRegistrationDTO::fromArray([
    'student_id' => 1,
    'class_room_id' => 5,
    'registration_fee_id' => 2,
    // school_year_id est optionnel, utilise DEFAULT_SCHOOL_YEAR_ID() par défaut
]);

$registration = $registrationService->registerExistingStudent($dto);
```

### 2. Inscription d'un Nouvel Élève

Pour créer un nouvel élève ET son inscription en une seule opération :

```php
use App\DTOs\Registration\CreateStudentDTO;
use App\DTOs\Registration\CreateRegistrationDTO;

// Données de l'élève
$studentDTO = CreateStudentDTO::fromArray([
    'name' => 'Jean Dupont',
    'gender' => 'M',
    'place_of_birth' => 'Paris',
    'date_of_birth' => '2010-05-15',
    'responsible_student_id' => 10,
]);

// Données de l'inscription
$registrationDTO = CreateRegistrationDTO::fromArray([
    'class_room_id' => 5,
    'registration_fee_id' => 2,
]);

$registration = $registrationService->registerNewStudent($studentDTO, $registrationDTO);
```

### 3. Mise à Jour d'une Inscription

```php
use App\DTOs\Registration\UpdateRegistrationDTO;

$dto = UpdateRegistrationDTO::fromArray([
    'class_room_id' => 6,
    'is_registered' => true,
]);

$registration = $registrationService->update($registrationId, $dto);
```

### 4. Suppression d'une Inscription

```php
$success = $registrationService->delete($registrationId);
```

### 5. Filtrage et Statistiques

#### Récupérer des inscriptions filtrées

```php
use App\DTOs\Registration\RegistrationFilterDTO;

$filter = RegistrationFilterDTO::fromArray([
    'school_year_id' => 1,        // Optionnel, utilise l'année par défaut
    'section_id' => 2,             // Filtrer par section
    'option_id' => 3,              // Filtrer par option
    'class_room_id' => 5,          // Filtrer par classe
    'gender' => 'M',               // 'M' ou 'F'
    'date_from' => '2024-09-01',   // Date d'inscription à partir de
    'date_to' => '2024-12-31',     // Date d'inscription jusqu'à
    'is_old' => true,              // Ancien élève
    'abandoned' => false,          // Non abandonné
    'is_registered' => true,       // Inscription confirmée
]);

// Avec pagination
$registrations = $registrationService->getFiltered($filter, perPage: 15);

// Sans pagination
$registrations = $registrationService->getFiltered($filter, paginate: false);
```

#### Récupérer des statistiques

```php
$stats = $registrationService->getStats($filter);

// Résultat:
[
    'total' => 250,
    'total_male' => 130,
    'total_female' => 120,
    'by_section' => [
        ['id' => 1, 'name' => 'Primaire', 'count' => 100],
        ['id' => 2, 'name' => 'Secondaire', 'count' => 150],
    ],
    'by_option' => [
        ['id' => 1, 'name' => 'Math-Physique', 'section_name' => 'Secondaire', 'count' => 80],
    ],
    'by_class' => [
        ['id' => 1, 'name' => '6ème A', 'option_name' => 'Générale', 'count' => 30],
    ],
]
```

#### Récupérer inscriptions ET statistiques en une seule requête

```php
$result = $registrationService->getFilteredWithStats($filter, perPage: 15);

// Résultat:
[
    'registrations' => LengthAwarePaginator,
    'stats' => [...],
]
```

### 6. Autres Méthodes Utiles

```php
// Vérifier si un élève est déjà inscrit
$isRegistered = $registrationService->isStudentRegistered($studentId, $schoolYearId);

// Obtenir toutes les inscriptions d'un élève
$registrations = $registrationService->getByStudentId($studentId);

// Marquer comme abandonné
$registration = $registrationService->markAsAbandoned($registrationId);

// Marquer comme non abandonné
$registration = $registrationService->markAsNotAbandoned($registrationId);

// Exempter des frais d'inscription
$registration = $registrationService->markFeeExempted($registrationId);

// Changer de classe
$registration = $registrationService->changeClass($registrationId, $newClassRoomId);

// Compter les inscriptions par classe
$count = $registrationService->countByClassRoom($classRoomId);
```

## Utilisation dans un Contrôleur API

Voir `app/Http/Controllers/Registration/RegistrationController.php` pour un exemple complet.

### Routes suggérées

```php
// routes/api.php
use App\Http\Controllers\Registration\RegistrationController;

Route::prefix('registrations')->group(function () {
    Route::get('/', [RegistrationController::class, 'index']);
    Route::get('/stats', [RegistrationController::class, 'stats']);
    Route::get('/{id}', [RegistrationController::class, 'show']);
    Route::post('/existing-student', [RegistrationController::class, 'registerExistingStudent']);
    Route::post('/new-student', [RegistrationController::class, 'registerNewStudent']);
    Route::put('/{id}', [RegistrationController::class, 'update']);
    Route::delete('/{id}', [RegistrationController::class, 'destroy']);
    Route::post('/{id}/abandon', [RegistrationController::class, 'markAsAbandoned']);
    Route::post('/{id}/change-class', [RegistrationController::class, 'changeClass']);
});
```

## Exemples de Requêtes API

### 1. Lister avec filtres

```http
GET /api/registrations?section_id=2&gender=M&date_from=2024-09-01&per_page=20
```

### 2. Inscrire un ancien élève

```http
POST /api/registrations/existing-student
Content-Type: application/json

{
    "student_id": 15,
    "class_room_id": 8,
    "registration_fee_id": 3
}
```

### 3. Inscrire un nouvel élève

```http
POST /api/registrations/new-student
Content-Type: application/json

{
    "student": {
        "name": "Marie Dubois",
        "gender": "F",
        "place_of_birth": "Lyon",
        "date_of_birth": "2011-03-20",
        "responsible_student_id": 12
    },
    "registration": {
        "class_room_id": 8,
        "registration_fee_id": 3
    }
}
```

### 4. Obtenir les statistiques

```http
GET /api/registrations/stats?section_id=2&school_year_id=1
```

## Avantages de cette Architecture

1. **Séparation des responsabilités** : Chaque couche a un rôle bien défini
2. **Testabilité** : Chaque composant peut être testé unitairement
3. **Réutilisabilité** : Les Actions et le Repository peuvent être réutilisés
4. **Maintenabilité** : Le code est organisé et facile à maintenir
5. **Type Safety** : Les DTOs assurent la cohérence des données
6. **Flexibilité** : Facile d'ajouter de nouvelles fonctionnalités

## Prochaines Étapes

Pour implémenter les vues Livewire, vous pourrez :

1. Créer des composants Livewire qui utilisent `RegistrationService`
2. Utiliser les DTOs pour valider les formulaires
3. Afficher les statistiques avec les données retournées par `getStats()`
4. Créer des filtres interactifs avec Livewire

Exemple de composant Livewire :

```php
use App\Services\Registration\RegistrationService;
use Livewire\Component;

class RegistrationList extends Component
{
    public function __construct(
        private RegistrationService $registrationService
    ) {}

    public function render()
    {
        $filter = RegistrationFilterDTO::fromArray([
            'section_id' => $this->sectionId,
            'gender' => $this->gender,
        ]);

        $result = $this->registrationService->getFilteredWithStats($filter);

        return view('livewire.registration-list', $result);
    }
}
```
