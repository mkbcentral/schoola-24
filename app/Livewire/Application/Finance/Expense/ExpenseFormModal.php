<?php

namespace App\Livewire\Application\Finance\Expense;

use App\Actions\Expense\SaveExpenseAction;
use App\Livewire\Forms\ExpenseForm;
use App\Models\CategoryExpense;
use App\Models\OtherSourceExpense;
use App\Models\School;
use App\Services\CategoryFee\CategoryFeeService;
use App\Services\Contracts\ExpenseServiceInterface;
use App\Services\Contracts\OtherExpenseServiceInterface;
use Livewire\Component;

class ExpenseFormModal extends Component
{
    // État du formulaire
    public bool $isEditing = false;

    // Type de dépense
    public string $expenseType = 'fee';

    // Formulaire
    public ExpenseForm $form;

    // Services
    private ExpenseServiceInterface $expenseService;
    private OtherExpenseServiceInterface $otherExpenseService;
    private CategoryFeeService $categoryFeeService;
    private SaveExpenseAction $saveExpenseAction;

    // Listeners
    protected $listeners = [
        'openExpenseModal' => 'openModal',
        'openExpenseEditModal' => 'openEditModal',
        'closeExpenseModal' => 'closeModal',
    ];

    /**
     * Injection des dépendances
     */
    public function boot(
        ExpenseServiceInterface $expenseService,
        OtherExpenseServiceInterface $otherExpenseService,
        CategoryFeeService $categoryFeeService,
        SaveExpenseAction $saveExpenseAction
    ): void {
        $this->expenseService = $expenseService;
        $this->otherExpenseService = $otherExpenseService;
        $this->categoryFeeService = $categoryFeeService;
        $this->saveExpenseAction = $saveExpenseAction;
    }

    /**
     * Initialisation
     */
    public function mount(): void
    {
        $this->form->month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
    }

    /**
     * Ouvrir le modal pour créer
     */
    public function openModal(string $expenseType): void
    {
        $this->expenseType = $expenseType;
        $this->form->reset();
        $this->form->month = str_pad(date('m'), 2, '0', STR_PAD_LEFT);
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    /**
     * Ouvrir le modal pour éditer
     */
    public function openEditModal(int $id, string $expenseType): void
    {
        $this->expenseType = $expenseType;
        $this->form->reset();
        $this->isEditing = true;
        $this->resetErrorBag();
        $this->loadExpense($id);
    }

    /**
     * Charger une dépense
     */
    private function loadExpense(int $id): void
    {
        $service = $this->expenseType === 'fee'
            ? $this->expenseService
            : $this->otherExpenseService;

        $expense = $service->findById($id);

        if ($expense) {
            $this->form->loadFromDTO($expense);
        }
    }

    /**
     * Sauvegarder
     */
    public function save(): void
    {
        // Validation selon le type
        if ($this->expenseType === 'fee') {
            $this->form->categoryFeeId = $this->form->categoryFeeId ?: 0;
            $this->validate();
        } else {
            $this->form->otherSourceExpenseId = $this->form->otherSourceExpenseId ?: 0;
            $this->validate();
        }

        // Sauvegarder
        $result = $this->saveExpenseAction->execute(
            $this->expenseType,
            $this->form->toArray(),
            $this->isEditing
        );

        if ($result['success']) {
            // Dispatch pour le toast de succès
            $this->dispatch('added', ['message' => $result['message']]);

            // Dispatch pour fermer le offcanvas et rafraîchir la liste
            $this->dispatch('expenseSaved', [
                'message' => $result['message'],
                'type' => 'success'
            ]);
            $this->closeModal();
        } else {
            // Dispatch pour le toast d'erreur
            $this->dispatch('error', ['message' => $result['message']]);
        }
    }

    /**
     * Fermer le modal (appelé par Bootstrap)
     */
    public function closeModal(): void
    {
        $this->form->reset();
        $this->resetErrorBag();
    }

    /**
     * Propriétés calculées pour cache des données
     */
    #[\Livewire\Attributes\Computed]
    public function categoryExpenses()
    {
        return CategoryExpense::where('school_id', School::DEFAULT_SCHOOL_ID())
            ->orderBy('name')
            ->get();
    }

    #[\Livewire\Attributes\Computed]
    public function categoryFees()
    {
        return $this->categoryFeeService->getAllCategoryFees();
    }

    #[\Livewire\Attributes\Computed]
    public function otherSources()
    {
        return OtherSourceExpense::where('school_id', School::DEFAULT_SCHOOL_ID())
            ->orderBy('name')
            ->get();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('livewire.application.finance.expense.expense-form-modal');
    }
}
