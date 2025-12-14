<?php

namespace App\Actions\Expense;

use App\Services\Contracts\ExpenseServiceInterface;
use App\Services\Contracts\OtherExpenseServiceInterface;

class DeleteExpenseAction
{
    public function __construct(
        private readonly ExpenseServiceInterface $expenseService,
        private readonly OtherExpenseServiceInterface $otherExpenseService
    ) {}

    /**
     * Exécuter l'action de suppression
     *
     * @param string $expenseType 'fee' ou 'other'
     * @param int $id ID de la dépense
     * @return array ['success' => bool, 'message' => string]
     */
    public function execute(string $expenseType, int $id): array
    {
        try {
            $service = $expenseType === 'fee'
                ? $this->expenseService
                : $this->otherExpenseService;

            $service->delete($id);

            return [
                'success' => true,
                'message' => 'Dépense supprimée avec succès',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ];
        }
    }
}
