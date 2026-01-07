<?php

namespace App\Livewire\Financial\Expense;

use App\Actions\Expense\DeleteExpenseAction;
use App\Actions\Expense\SaveExpenseAction;
use App\DTOs\ExpenseFilterDTO;
use App\DTOs\OtherExpenseFilterDTO;
use App\Livewire\Traits\WithExpenseFilters;
use App\Livewire\Traits\WithFlashMessages;
use App\Models\CategoryExpense;
use App\Models\OtherSourceExpense;
use App\Models\School;
use App\Services\CategoryFee\CategoryFeeService;
use App\Services\Contracts\CurrencyExchangeServiceInterface;
use App\Services\Contracts\ExpenseServiceInterface;
use App\Services\Contracts\OtherExpenseServiceInterface;
use Livewire\Component;
use Livewire\WithPagination;

class ExpenseManagementPage extends Component
{
    use WithPagination;
    use WithExpenseFilters;
    use WithFlashMessages;

    // Type de dépense actif
    public string $expenseType = 'fee';

    // Données du formulaire de dépense
    public array $expenseFormData = [
        'description' => '',
        'month' => '',
        'amount' => '',
        'currency' => 'USD',
        'categoryExpenseId' => '',
        'categoryFeeId' => '',
        'otherSourceExpenseId' => '',
    ];

    public bool $isEditingExpense = false;
    public ?int $editingExpenseId = null;

    // Pas de modal ni de form - géré par ExpenseFormModal

    // Services injectés
    private ExpenseServiceInterface $expenseService;
    private OtherExpenseServiceInterface $otherExpenseService;
    private CategoryFeeService $categoryFeeService;
    private CurrencyExchangeServiceInterface $currencyService;
    private DeleteExpenseAction $deleteExpenseAction;
    private \App\Actions\Expense\ToggleExpenseValidationAction $toggleValidationAction;

    // Listeners
    protected $listeners = [
        'expenseSaved' => 'handleExpenseSaved',
    ];

    protected $paginationTheme = 'bootstrap';

    /**
     * Injection des dépendances via boot()
     */
    public function boot(
        ExpenseServiceInterface $expenseService,
        OtherExpenseServiceInterface $otherExpenseService,
        CategoryFeeService $categoryFeeService,
        CurrencyExchangeServiceInterface $currencyService,
        DeleteExpenseAction $deleteExpenseAction,
        \App\Actions\Expense\ToggleExpenseValidationAction $toggleValidationAction
    ): void {
        $this->expenseService = $expenseService;
        $this->otherExpenseService = $otherExpenseService;
        $this->categoryFeeService = $categoryFeeService;
        $this->currencyService = $currencyService;
        $this->deleteExpenseAction = $deleteExpenseAction;
        $this->toggleValidationAction = $toggleValidationAction;
    }

    /**
     * Initialisation du composant
     */
    public function mount(): void
    {
        $this->initializeFilters();
    }

    /**
     * Changer le type de dépense
     */
    public function switchExpenseType(string $type): void
    {
        $this->expenseType = $type;
        $this->resetFilters();
        $this->clearMessage();
    }

    /**
     * Ouvrir le modal pour créer une dépense
     */
    public function openCreateModal(): void
    {
        $this->clearMessage();
        $this->resetExpenseForm();
        $this->isEditingExpense = false;
        $this->editingExpenseId = null;
        $this->dispatch('open-expense-form-modal');
    }

    /**
     * Ouvrir le modal pour éditer une dépense
     */
    public function openEditModal(int $id): void
    {
        $this->clearMessage();
        $this->isEditingExpense = true;
        $this->editingExpenseId = $id;
        
        // Charger les données de la dépense
        if ($this->expenseType === 'fee') {
            $expense = \App\Models\ExpenseFee::find($id);
        } else {
            $expense = \App\Models\OtherExpense::find($id);
        }

        if ($expense) {
            $this->expenseFormData = [
                'description' => $expense->description,
                'month' => $expense->month,
                'amount' => $expense->amount,
                'currency' => $expense->currency,
                'categoryExpenseId' => $expense->category_expense_id,
                'categoryFeeId' => $expense->category_fee_id ?? '',
                'otherSourceExpenseId' => $expense->other_source_expense_id ?? '',
            ];
        }
        
        $this->dispatch('open-expense-form-modal');
    }

    /**
     * Enregistrer une dépense
     */
    public function saveExpense(): void
    {
        $this->validate([
            'expenseFormData.description' => 'required|string|max:255',
            'expenseFormData.month' => 'required|string',
            'expenseFormData.amount' => 'required|numeric|min:0',
            'expenseFormData.currency' => 'required|in:USD,CDF',
            'expenseFormData.categoryExpenseId' => 'required|exists:category_expenses,id',
        ]);

        try {
            if ($this->expenseType === 'fee') {
                $this->validate([
                    'expenseFormData.categoryFeeId' => 'required|exists:category_fees,id',
                ]);

                if ($this->isEditingExpense && $this->editingExpenseId) {
                    $expense = \App\Models\ExpenseFee::find($this->editingExpenseId);
                    $expense->update([
                        'description' => $this->expenseFormData['description'],
                        'month' => $this->expenseFormData['month'],
                        'amount' => $this->expenseFormData['amount'],
                        'currency' => $this->expenseFormData['currency'],
                        'category_expense_id' => $this->expenseFormData['categoryExpenseId'],
                        'category_fee_id' => $this->expenseFormData['categoryFeeId'],
                    ]);
                    $this->success('Dépense modifiée avec succès');
                } else {
                    \App\Models\ExpenseFee::create([
                        'description' => $this->expenseFormData['description'],
                        'month' => $this->expenseFormData['month'],
                        'amount' => $this->expenseFormData['amount'],
                        'currency' => $this->expenseFormData['currency'],
                        'category_expense_id' => $this->expenseFormData['categoryExpenseId'],
                        'category_fee_id' => $this->expenseFormData['categoryFeeId'],
                        'school_id' => \App\Models\School::DEFAULT_SCHOOL_ID(),
                        'school_year_id' => session('school_year_id'),
                        'date' => now(),
                    ]);
                    $this->success('Dépense créée avec succès');
                }
            } else {
                $this->validate([
                    'expenseFormData.otherSourceExpenseId' => 'required|exists:other_source_expenses,id',
                ]);

                if ($this->isEditingExpense && $this->editingExpenseId) {
                    $expense = \App\Models\OtherExpense::find($this->editingExpenseId);
                    $expense->update([
                        'description' => $this->expenseFormData['description'],
                        'month' => $this->expenseFormData['month'],
                        'amount' => $this->expenseFormData['amount'],
                        'currency' => $this->expenseFormData['currency'],
                        'category_expense_id' => $this->expenseFormData['categoryExpenseId'],
                        'other_source_expense_id' => $this->expenseFormData['otherSourceExpenseId'],
                    ]);
                    $this->success('Dépense modifiée avec succès');
                } else {
                    \App\Models\OtherExpense::create([
                        'description' => $this->expenseFormData['description'],
                        'month' => $this->expenseFormData['month'],
                        'amount' => $this->expenseFormData['amount'],
                        'currency' => $this->expenseFormData['currency'],
                        'category_expense_id' => $this->expenseFormData['categoryExpenseId'],
                        'other_source_expense_id' => $this->expenseFormData['otherSourceExpenseId'],
                        'school_id' => \App\Models\School::DEFAULT_SCHOOL_ID(),
                        'school_year_id' => session('school_year_id'),
                        'date' => now(),
                    ]);
                    $this->success('Dépense créée avec succès');
                }
            }

            $this->dispatch('close-expense-form-modal');
            $this->resetExpenseForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->error('Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Réinitialiser le formulaire
     */
    private function resetExpenseForm(): void
    {
        $this->expenseFormData = [
            'description' => '',
            'month' => '',
            'amount' => '',
            'currency' => 'USD',
            'categoryExpenseId' => '',
            'categoryFeeId' => '',
            'otherSourceExpenseId' => '',
        ];
        $this->isEditingExpense = false;
        $this->editingExpenseId = null;
    }

    /**
     * Gérer la sauvegarde depuis le modal
     */
    public function handleExpenseSaved(array $data): void
    {
        if ($data['type'] === 'success') {
            $this->success($data['message']);
        } else {
            $this->error($data['message']);
        }
        $this->resetPage();
    }

    /**
     * Demande de confirmation pour supprimer une dépense
     */
    public function confirmDelete(int $id): void
    {
        // Récupérer le modèle complet avec les relations
        if ($this->expenseType === 'fee') {
            $expense = \App\Models\ExpenseFee::with('categoryExpense')->find($id);
        } else {
            $expense = \App\Models\OtherExpense::with('categoryExpense')->find($id);
        }

        if (!$expense) {
            $this->error('Dépense introuvable');
            return;
        }

        $this->dispatch('delete-expense-dialog', [
            'expenseId' => $expense->id,
            'description' => $expense->description,
            'amount' => $expense->amount,
            'currency' => $expense->currency,
            'category' => $expense->categoryExpense->name ?? 'N/A',
            'month' => format_fr_month_name($expense->month),
        ]);
    }

    /**
     * Supprimer une dépense
     */
    public function deleteExpense(int $id): void
    {
        $result = $this->deleteExpenseAction->execute($this->expenseType, $id);

        if ($result['success']) {
            $this->dispatch('expense-deleted', ['message' => $result['message']]);
            $this->resetPage();
        } else {
            $this->dispatch('delete-expense-failed', ['message' => $result['message']]);
        }
    }

    /**
     * Basculer l'état de validation d'une dépense
     */
    public function toggleValidation(int $id): void
    {
        $result = $this->toggleValidationAction->execute($this->expenseType, $id);

        if ($result['success']) {
            $this->success($result['message']);
            // Pas besoin de resetPage, juste rafraîchir
        } else {
            $this->error($result['message']);
        }
    }



    /**
     * Obtenir les dépenses filtrées
     */
    private function getExpenses()
    {
        $service = $this->expenseType === 'fee'
            ? $this->expenseService
            : $this->otherExpenseService;

        $filterClass = $this->expenseType === 'fee'
            ? ExpenseFilterDTO::class
            : OtherExpenseFilterDTO::class;

        $filter = $filterClass::fromArray($this->getFilterArray($this->expenseType));

        return $service->getAll($filter, 15);
    }

    /**
     * Obtenir les statistiques
     */
    private function getStatistics(): array
    {
        $service = $this->expenseType === 'fee'
            ? $this->expenseService
            : $this->otherExpenseService;

        $filterClass = $this->expenseType === 'fee'
            ? ExpenseFilterDTO::class
            : OtherExpenseFilterDTO::class;

        $filter = $filterClass::fromArray($this->getFilterArray($this->expenseType));

        $totals = $service->getTotalAmountByCurrency($filter);
        $statistics = $service->getStatistics($filter);

        return [
            'totalUSD' => $totals['USD'] ?? 0,
            'totalCDF' => $totals['CDF'] ?? 0,
            'totalUSDConverted' => ($totals['USD'] ?? 0) + $this->currencyService->convertToUSD($totals['CDF'] ?? 0, 'CDF'),
            'totalCDFConverted' => $this->currencyService->convert($totals['USD'] ?? 0, 'USD', 'CDF') + ($totals['CDF'] ?? 0),
            'count' => $statistics['count'] ?? 0,
            'averageAmount' => $statistics['average_amount'] ?? 0,
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return view('livewire.application.finance.expense.expense-management-page-v2', [
            'expenses' => $this->getExpenses(),
            'statistics' => $this->getStatistics(),
            'categoryExpenses' => CategoryExpense::where('school_id', School::DEFAULT_SCHOOL_ID())->orderBy('name')->get(),
            'categoryFees' => $this->categoryFeeService->getAllCategoryFees(),
            'otherSources' => OtherSourceExpense::where('school_id', School::DEFAULT_SCHOOL_ID())->orderBy('name')->get(),
            'currentRate' => $this->currencyService->getCurrentRateFromDB(),
        ]);
    }
}
