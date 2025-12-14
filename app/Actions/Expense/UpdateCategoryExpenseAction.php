<?php

namespace App\Actions\Expense;

use App\Models\CategoryExpense;
use App\Services\Expense\CategoryExpenseServiceInterface;

class UpdateCategoryExpenseAction
{
    public function __construct(
        private CategoryExpenseServiceInterface $categoryExpenseService
    ) {}

    /**
     * Execute the action to update a category expense
     *
     * @param  int  $id
     * @param  array  $data  ['name' => string]
     * @return array ['success' => bool, 'message' => string]
     */
    public function execute(int $id, array $data): array
    {
        try {
            $categoryExpense = $this->categoryExpenseService->findById($id);

            if (! $categoryExpense) {
                return [
                    'success' => false,
                    'message' => 'Catégorie non trouvée',
                ];
            }

            $this->categoryExpenseService->update($categoryExpense, $data);

            return [
                'success' => true,
                'message' => 'Catégorie modifiée avec succès',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la modification: ' . $e->getMessage(),
            ];
        }
    }
}
