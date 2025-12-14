<?php

namespace App\Actions\Expense;

use App\Models\CategoryExpense;
use App\Services\Expense\CategoryExpenseServiceInterface;

class CreateCategoryExpenseAction
{
    public function __construct(
        private CategoryExpenseServiceInterface $categoryExpenseService
    ) {}

    /**
     * Execute the action to create a new category expense
     *
     * @param  array  $data  ['name' => string]
     * @return array ['success' => bool, 'message' => string, 'categoryExpense' => CategoryExpense|null]
     */
    public function execute(array $data): array
    {
        try {
            $categoryExpense = $this->categoryExpenseService->create($data);

            return [
                'success' => true,
                'message' => 'Catégorie créée avec succès',
                'categoryExpense' => $categoryExpense,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage(),
                'categoryExpense' => null,
            ];
        }
    }
}
