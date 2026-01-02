<?php

namespace App\Livewire\Application\V2\Expense;

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

class ExpenseManagementPageRefactored extends Component
{
    use WithPagination;
    use WithExpenseFilters;
    use WithFlashMessages;

    // Type de dépense actif
    public string $expenseType = 'fee';

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
        $this->dispatch('openExpenseModal', expenseType: $this->expenseType);
    }

    /**
     * Ouvrir le modal pour éditer une dépense
     */
    public function openEditModal(int $id): void
    {
        $this->clearMessage();
        $this->dispatch('openExpenseEditModal', id: $id, expenseType: $this->expenseType);
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
        return view('livewire.application.v2.expense.expense-management-page', [
            'expenses' => $this->getExpenses(),
            'statistics' => $this->getStatistics(),
            'categoryExpenses' => CategoryExpense::where('school_id', School::DEFAULT_SCHOOL_ID())->orderBy('name')->get(),
            'categoryFees' => $this->categoryFeeService->getAllCategoryFees(),
            'otherSources' => OtherSourceExpense::where('school_id', School::DEFAULT_SCHOOL_ID())->orderBy('name')->get(),
            'currentRate' => $this->currencyService->getCurrentRateFromDB(),
        ]);
    }
}
