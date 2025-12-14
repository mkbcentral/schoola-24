<?php

namespace App\Livewire\Application\Finance\Expense\Settings;

use App\Actions\Expense\CreateCategoryExpenseAction;
use App\Actions\Expense\UpdateCategoryExpenseAction;
use App\Livewire\Forms\CategoryExpenseForm;
use App\Services\Expense\CategoryExpenseServiceInterface;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoryExpenseFormModal extends Component
{
    public CategoryExpenseForm $form;

    public bool $isEditMode = false;

    public ?int $categoryId = null;

    /**
     * Open modal for create
     */
    #[On('open-category-modal')]
    public function openModal(): void
    {
        $this->reset();
        $this->isEditMode = false;
        $this->categoryId = null;
    }

    /**
     * Open modal for edit
     */
    #[On('open-edit-category-modal')]
    public function openEditModal(int $categoryId, CategoryExpenseServiceInterface $categoryExpenseService): void
    {
        $category = $categoryExpenseService->findById($categoryId);

        if ($category) {
            $this->categoryId = $category->id;
            $this->form->name = $category->name;
            $this->isEditMode = true;
        }
    }

    /**
     * Save category (create or update)
     */
    public function save(
        CreateCategoryExpenseAction $createCategoryExpenseAction,
        UpdateCategoryExpenseAction $updateCategoryExpenseAction
    ): void {
        $this->validate();

        if ($this->isEditMode && $this->categoryId) {
            $result = $updateCategoryExpenseAction->execute($this->categoryId, $this->form->toArray());
        } else {
            $result = $createCategoryExpenseAction->execute($this->form->toArray());
        }

        if ($result['success']) {
            $this->dispatch('added');
            $this->dispatch('category-saved');
            $this->closeModal();
        } else {
            $this->dispatch('error', message: $result['message']);
        }
    }

    /**
     * Close modal and reset form
     */
    public function closeModal(): void
    {
        $this->reset();
        $this->dispatch('close-category-modal');
    }

    public function render()
    {
        return view('livewire.application.finance.expense.settings.category-expense-form-modal');
    }
}
