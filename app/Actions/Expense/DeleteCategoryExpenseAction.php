<?php

namespace App\Actions\Expense;

use App\Services\Expense\CategoryExpenseServiceInterface;

class DeleteCategoryExpenseAction
{
    public function __construct(
        private CategoryExpenseServiceInterface $categoryExpenseService
    ) {}

    /**
     * Execute the action to delete a category expense
     *
     * @param  int  $id
     * @return array ['success' => bool, 'message' => string]
     */
    public function execute(int $id): array
    {
        try {
            $categoryExpense = $this->categoryExpenseService->findById($id);

            if (! $categoryExpense) {
                return [
                    'success' => false,
                    'message' => 'Catégorie non trouvée',
                ];
            }

            // Check if category has expenses
            if ($this->categoryExpenseService->hasExpenses($categoryExpense)) {
                return [
                    'success' => false,
                    'message' => 'Impossible de supprimer une catégorie avec des dépenses associées',
                ];
            }

            $this->categoryExpenseService->delete($categoryExpense);

            return [
                'success' => true,
                'message' => 'Catégorie supprimée avec succès',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage(),
            ];
        }
    }
}
