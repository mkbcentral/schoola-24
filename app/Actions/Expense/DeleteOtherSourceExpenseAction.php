<?php

namespace App\Actions\Expense;

use App\Services\Expense\OtherSourceExpenseServiceInterface;

class DeleteOtherSourceExpenseAction
{
    public function __construct(
        private OtherSourceExpenseServiceInterface $otherSourceExpenseService
    ) {}

    /**
     * Execute the action to delete an other source expense
     *
     * @param  int  $id
     * @return array ['success' => bool, 'message' => string]
     */
    public function execute(int $id): array
    {
        try {
            $otherSourceExpense = $this->otherSourceExpenseService->findById($id);

            if (! $otherSourceExpense) {
                return [
                    'success' => false,
                    'message' => 'Source non trouvée',
                ];
            }

            // Check if source has expenses
            if ($this->otherSourceExpenseService->hasExpenses($otherSourceExpense)) {
                return [
                    'success' => false,
                    'message' => 'Impossible de supprimer une source avec des dépenses associées',
                ];
            }

            $this->otherSourceExpenseService->delete($otherSourceExpense);

            return [
                'success' => true,
                'message' => 'Source supprimée avec succès',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage(),
            ];
        }
    }
}
