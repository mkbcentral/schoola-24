<?php

namespace App\Livewire\Financial\Expense\Settings;

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
    
    // Modal states
    public bool $showCategoryModal = false;
    public bool $showSourceModal = false;
    public ?int $editingCategoryId = null;
    public ?int $editingSourceId = null;
    
    // Form data
    public array $categoryFormData = [
        'name' => '',
        'description' => '',
        'is_active' => true,
    ];
    
    public array $sourceFormData = [
        'name' => '',
        'description' => '',
        'is_active' => true,
    ];

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
        $this->resetCategoryForm();
        $this->editingCategoryId = null;
        $this->showCategoryModal = true;
    }

    /**
     * Open modal to edit category
     */
    public function openEditCategoryModal(int $id, CategoryExpenseServiceInterface $categoryExpenseService): void
    {
        $category = $categoryExpenseService->findById($id);
        
        if ($category) {
            $this->editingCategoryId = $id;
            $this->categoryFormData = [
                'name' => $category->name,
                'description' => $category->description ?? '',
                'is_active' => $category->is_active,
            ];
            $this->showCategoryModal = true;
        }
    }
    
    /**
     * Close category modal
     */
    public function closeCategoryModal(): void
    {
        $this->showCategoryModal = false;
        $this->resetCategoryForm();
    }
    
    /**
     * Reset category form
     */
    private function resetCategoryForm(): void
    {
        $this->categoryFormData = [
            'name' => '',
            'description' => '',
            'is_active' => true,
        ];
        $this->editingCategoryId = null;
    }
    
    /**
     * Save category
     */
    public function saveCategory(CategoryExpenseServiceInterface $categoryExpenseService): void
    {
        $this->validate([
            'categoryFormData.name' => 'required|string|max:255',
            'categoryFormData.description' => 'nullable|string',
            'categoryFormData.is_active' => 'boolean',
        ]);
        
        try {
            if ($this->editingCategoryId) {
                $categoryExpenseService->update($this->editingCategoryId, $this->categoryFormData);
                $this->dispatch('category-saved', message: 'Catégorie modifiée avec succès');
            } else {
                $categoryExpenseService->create($this->categoryFormData);
                $this->dispatch('category-saved', message: 'Catégorie créée avec succès');
            }
            
            $this->closeCategoryModal();
        } catch (\Exception $e) {
            $this->dispatch('save-failed', message: 'Erreur: ' . $e->getMessage());
        }
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
        $this->resetSourceForm();
        $this->editingSourceId = null;
        $this->showSourceModal = true;
    }

    /**
     * Open modal to edit source
     */
    public function openEditSourceModal(int $id, OtherSourceExpenseServiceInterface $otherSourceExpenseService): void
    {
        $source = $otherSourceExpenseService->findById($id);
        
        if ($source) {
            $this->editingSourceId = $id;
            $this->sourceFormData = [
                'name' => $source->name,
                'description' => $source->description ?? '',
                'is_active' => $source->is_active,
            ];
            $this->showSourceModal = true;
        }
    }
    
    /**
     * Close source modal
     */
    public function closeSourceModal(): void
    {
        $this->showSourceModal = false;
        $this->resetSourceForm();
    }
    
    /**
     * Reset source form
     */
    private function resetSourceForm(): void
    {
        $this->sourceFormData = [
            'name' => '',
            'description' => '',
            'is_active' => true,
        ];
        $this->editingSourceId = null;
    }
    
    /**
     * Save source
     */
    public function saveSource(OtherSourceExpenseServiceInterface $otherSourceExpenseService): void
    {
        $this->validate([
            'sourceFormData.name' => 'required|string|max:255',
            'sourceFormData.description' => 'nullable|string',
            'sourceFormData.is_active' => 'boolean',
        ]);
        
        try {
            if ($this->editingSourceId) {
                $otherSourceExpenseService->update($this->editingSourceId, $this->sourceFormData);
                $this->dispatch('source-saved', message: 'Source modifiée avec succès');
            } else {
                $otherSourceExpenseService->create($this->sourceFormData);
                $this->dispatch('source-saved', message: 'Source créée avec succès');
            }
            
            $this->closeSourceModal();
        } catch (\Exception $e) {
            $this->dispatch('save-failed', message: 'Erreur: ' . $e->getMessage());
        }
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
        return view('livewire.application.finance.expense.settings.expense-settings-page-tailwind', [
            'categoryExpenses' => $this->getCategoryExpenses($categoryExpenseService),
            'otherSourceExpenses' => $this->getOtherSourceExpenses($otherSourceExpenseService),
        ]);
    }
}
