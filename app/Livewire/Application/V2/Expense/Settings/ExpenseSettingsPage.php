<?php

namespace App\Livewire\Application\Finance\Expense\Settings;

use App\Actions\Expense\DeleteCategoryExpenseAction;
use App\Actions\Expense\DeleteOtherSourceExpenseAction;
use App\Services\Expense\CategoryExpenseServiceInterface;
use App\Services\Expense\OtherSourceExpenseServiceInterface;
use Livewire\Attributes\On;
use Livewire\Component;

class ExpenseSettingsPage extends Component
{
    public string $activeTab = 'categories'; // 'categories' or 'sources'

    public string $search = '';

    /**
     * Switch between tabs
     */
    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->search = ''; // Reset search when switching tabs
    }

    /**
     * Get all category expenses
     */
    public function getCategoryExpenses(CategoryExpenseServiceInterface $categoryExpenseService)
    {
        $categories = $categoryExpenseService->getAllBySchool();

        if ($this->search) {
            return $categories->filter(function ($category) {
                return stripos($category->name, $this->search) !== false;
            });
        }

        return $categories;
    }

    /**
     * Get all other source expenses
     */
    public function getOtherSourceExpenses(OtherSourceExpenseServiceInterface $otherSourceExpenseService)
    {
        $sources = $otherSourceExpenseService->getAllBySchool();

        if ($this->search) {
            return $sources->filter(function ($source) {
                return stripos($source->name, $this->search) !== false;
            });
        }

        return $sources;
    }

    /**
     * Open modal to create category
     */
    public function openCreateCategoryModal(): void
    {
        $this->dispatch('open-category-modal');
    }

    /**
     * Open modal to edit category
     */
    public function openEditCategoryModal(int $id): void
    {
        $this->dispatch('open-edit-category-modal', categoryId: $id);
    }

    /**
     * Confirm delete category
     */
    public function confirmDeleteCategory(int $id, CategoryExpenseServiceInterface $categoryExpenseService): void
    {
        $category = $categoryExpenseService->findById($id);

        if (! $category) {
            $this->dispatch('delete-failed', message: 'Catégorie non trouvée');

            return;
        }

        $this->dispatch('confirm-delete-category', [
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }

    /**
     * Delete category
     */
    public function deleteCategory(int $id, DeleteCategoryExpenseAction $deleteCategoryExpenseAction): void
    {
        $result = $deleteCategoryExpenseAction->execute($id);

        if ($result['success']) {
            $this->dispatch('category-deleted', message: $result['message']);
        } else {
            $this->dispatch('delete-failed', message: $result['message']);
        }
    }

    /**
     * Open modal to create source
     */
    public function openCreateSourceModal(): void
    {
        $this->dispatch('open-source-modal');
    }

    /**
     * Open modal to edit source
     */
    public function openEditSourceModal(int $id): void
    {
        $this->dispatch('open-edit-source-modal', sourceId: $id);
    }

    /**
     * Confirm delete source
     */
    public function confirmDeleteSource(int $id, OtherSourceExpenseServiceInterface $otherSourceExpenseService): void
    {
        $source = $otherSourceExpenseService->findById($id);

        if (! $source) {
            $this->dispatch('delete-failed', message: 'Source non trouvée');

            return;
        }

        $this->dispatch('confirm-delete-source', [
            'id' => $source->id,
            'name' => $source->name,
        ]);
    }

    /**
     * Delete source
     */
    public function deleteSource(int $id, DeleteOtherSourceExpenseAction $deleteOtherSourceExpenseAction): void
    {
        $result = $deleteOtherSourceExpenseAction->execute($id);

        if ($result['success']) {
            $this->dispatch('source-deleted', message: $result['message']);
        } else {
            $this->dispatch('delete-failed', message: $result['message']);
        }
    }

    /**
     * Refresh data after changes
     */
    #[On('category-saved')]
    #[On('source-saved')]
    public function refresh(): void
    {
        // Simply refresh the component
    }

    public function render(
        CategoryExpenseServiceInterface $categoryExpenseService,
        OtherSourceExpenseServiceInterface $otherSourceExpenseService
    ) {
        return view('livewire.application.finance.expense.settings.expense-settings-page', [
            'categoryExpenses' => $this->getCategoryExpenses($categoryExpenseService),
            'otherSourceExpenses' => $this->getOtherSourceExpenses($otherSourceExpenseService),
        ]);
    }
}
