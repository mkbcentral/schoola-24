# Guide de Contribution - Schoola

Merci de votre intérêt pour contribuer à Schoola ! Ce document fournit les directives pour contribuer au projet.

## 📋 Table des matières

-   [Code de conduite](#code-de-conduite)
-   [Comment contribuer](#comment-contribuer)
-   [Standards de code](#standards-de-code)
-   [Processus de Pull Request](#processus-de-pull-request)
-   [Conventions de commit](#conventions-de-commit)
-   [Tests](#tests)

## 🤝 Code de conduite

Ce projet adhère à un code de conduite. En participant, vous acceptez de maintenir un environnement respectueux et inclusif.

## 🚀 Comment contribuer

### Rapporter un bug

1. Vérifiez que le bug n'a pas déjà été rapporté dans les [Issues](https://github.com/mkbcentral/schoola-24/issues)
2. Créez une nouvelle issue avec le template "Bug Report"
3. Décrivez clairement :
    - Les étapes pour reproduire le bug
    - Le comportement attendu
    - Le comportement actuel
    - Captures d'écran si applicable
    - Votre environnement (OS, version PHP, Laravel, etc.)

### Proposer une fonctionnalité

1. Créez une issue avec le template "Feature Request"
2. Expliquez :
    - Le problème que cela résout
    - La solution proposée
    - Les alternatives considérées
    - Des maquettes/exemples si possible

### Soumettre du code

1. **Fork** le repository
2. **Créez une branche** depuis `develop` :
    ```bash
    git checkout -b feature/ma-nouvelle-fonctionnalite
    # ou
    git checkout -b fix/correction-bug
    ```
3. **Codez** en suivant les standards
4. **Commitez** avec des messages clairs
5. **Testez** votre code
6. **Pushez** vers votre fork
7. **Ouvrez une Pull Request** vers `develop`

## 📝 Standards de code

### PHP

#### PSR-12

Suivez [PSR-12](https://www.php-fig.org/psr/psr-12/) pour le style de code PHP.

```bash
# Vérifier le code
./vendor/bin/pint --test

# Corriger automatiquement
./vendor/bin/pint
```

#### PHPStan

Analysez le code statiquement :

```bash
./vendor/bin/phpstan analyse
```

#### Conventions de nommage

-   **Classes** : PascalCase (`StudentController`, `PaymentService`)
-   **Méthodes** : camelCase (`createStudent()`, `processPayment()`)
-   **Variables** : camelCase (`$studentName`, `$totalAmount`)
-   **Constantes** : UPPER_SNAKE_CASE (`MAX_STUDENTS`, `DEFAULT_CURRENCY`)
-   **Tables DB** : snake_case pluriel (`students`, `payments`, `school_years`)
-   **Colonnes DB** : snake_case (`created_at`, `student_id`, `is_paid`)

#### Documentation

Utilisez PHPDoc pour documenter :

```php
/**
 * Crée un nouveau paiement pour un élève
 *
 * @param Registration $registration L'inscription de l'élève
 * @param ScolarFee $scolarFee Les frais scolaires
 * @param float $amount Le montant du paiement
 * @return Payment Le paiement créé
 * @throws PaymentException Si le paiement échoue
 */
public function createPayment(
    Registration $registration,
    ScolarFee $scolarFee,
    float $amount
): Payment {
    // ...
}
```

### JavaScript

#### ESLint

Respectez la configuration ESLint du projet :

```bash
npm run lint
```

#### Conventions

-   **Variables/Fonctions** : camelCase
-   **Constantes** : UPPER_SNAKE_CASE
-   **Composants** : PascalCase
-   Utilisez `const` par défaut, `let` si nécessaire
-   Pas de `var`
-   Utilisez les arrow functions pour les callbacks

```javascript
// ✅ Bon
const students = [];
const calculateTotal = (payments) => {
    return payments.reduce((sum, payment) => sum + payment.amount, 0);
};

// ❌ Mauvais
var students = [];
function calculateTotal(payments) {
    var sum = 0;
    for (var i = 0; i < payments.length; i++) {
        sum += payments[i].amount;
    }
    return sum;
}
```

### Livewire

#### Structure des composants

```php
namespace App\Livewire\Application\Student;

use Livewire\Component;
use Livewire\WithPagination;

class ListStudentPage extends Component
{
    use WithPagination;

    // 1. Propriétés publiques
    public string $search = '';
    public int $perPage = 10;

    // 2. Propriétés protégées/privées
    protected $listeners = ['studentCreated' => 'refreshList'];

    // 3. Méthodes du cycle de vie
    public function mount(): void
    {
        // Initialisation
    }

    // 4. Méthodes d'action
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // 5. Méthode render (toujours en dernier)
    public function render()
    {
        return view('livewire.application.student.list-student-page', [
            'students' => $this->getStudents(),
        ]);
    }

    // 6. Méthodes privées
    private function getStudents()
    {
        return Student::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate($this->perPage);
    }
}
```

### Base de données

#### Migrations

```php
// Nommage : YYYY_MM_DD_HHMMSS_action_table_name.php
// Exemple : 2024_11_24_000001_create_payments_table.php

Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->string('payment_number')->unique();
    $table->foreignIdFor(Registration::class)->constrained()->cascadeOnDelete();
    $table->decimal('amount', 10, 2);
    $table->boolean('is_paid')->default(false);
    $table->timestamps();
    $table->softDeletes(); // Si nécessaire

    // Indexes
    $table->index('payment_number');
    $table->index(['is_paid', 'created_at']);
});
```

## 🔄 Processus de Pull Request

### Checklist avant soumission

-   [ ] Le code respecte les standards PSR-12
-   [ ] Laravel Pint passe sans erreur
-   [ ] PHPStan niveau 5 passe sans erreur
-   [ ] Tous les tests passent
-   [ ] Nouveaux tests ajoutés si nécessaire
-   [ ] Documentation mise à jour
-   [ ] Pas de `dd()`, `dump()`, `console.log()` dans le code
-   [ ] Les migrations sont réversibles
-   [ ] Les messages de commit suivent les conventions

### Template de PR

```markdown
## Description

Brève description des changements

## Type de changement

-   [ ] Bug fix (non-breaking change)
-   [ ] Nouvelle fonctionnalité (non-breaking change)
-   [ ] Breaking change (fix ou feature qui casse la compatibilité)
-   [ ] Documentation

## Tests effectués

-   [ ] Test unitaire
-   [ ] Test d'intégration
-   [ ] Test manuel

## Checklist

-   [ ] Code suit les conventions du projet
-   [ ] Auto-review effectué
-   [ ] Tests ajoutés
-   [ ] Documentation mise à jour
```

## 📝 Conventions de commit

Utilisez [Conventional Commits](https://www.conventionalcommits.org/) :

```
<type>(<scope>): <description>

[corps optionnel]

[footer optionnel]
```

### Types

-   `feat`: Nouvelle fonctionnalité
-   `fix`: Correction de bug
-   `docs`: Documentation uniquement
-   `style`: Changements qui n'affectent pas le code (espaces, formatage)
-   `refactor`: Refactoring du code
-   `perf`: Amélioration de performance
-   `test`: Ajout ou correction de tests
-   `chore`: Maintenance (deps, config)

### Exemples

```bash
feat(payment): ajouter validation de montant minimum

fix(student): corriger calcul de l'âge pour dates futures

docs(readme): mettre à jour instructions d'installation

refactor(registration): extraire logique métier vers service

test(payment): ajouter tests pour paiements multiples
```

## 🧪 Tests

### Écrire des tests

Utilisez Pest PHP pour les tests :

```php
use App\Models\Payment;
use App\Models\Student;

it('can create a payment', function () {
    $student = Student::factory()->create();

    $payment = Payment::create([
        'student_id' => $student->id,
        'amount' => 100.00,
    ]);

    expect($payment)->toBeInstanceOf(Payment::class)
        ->and($payment->amount)->toBe(100.00);
});

it('validates payment amount is positive', function () {
    Payment::create(['amount' => -10]);
})->throws(ValidationException::class);
```

### Exécuter les tests

```bash
# Tous les tests
php artisan test

# Test spécifique
php artisan test --filter=PaymentTest

# Avec couverture
php artisan test --coverage --min=80
```

### Couverture minimale

-   **Modèles** : 80%
-   **Services** : 90%
-   **Controllers** : 70%
-   **Composants Livewire** : 75%

## 📚 Ressources

-   [Documentation Laravel](https://laravel.com/docs)
-   [Documentation Livewire](https://livewire.laravel.com/docs)
-   [PSR-12](https://www.php-fig.org/psr/psr-12/)
-   [Pest PHP](https://pestphp.com)

## ❓ Questions

Pour toute question, ouvrez une [Discussion](https://github.com/mkbcentral/schoola-24/discussions) ou contactez l'équipe.

---

**Merci de contribuer à Schoola ! 🎓**
