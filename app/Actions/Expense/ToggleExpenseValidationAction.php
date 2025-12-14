<?php

namespace App\Actions\Expense;

use App\Models\ExpenseFee;
use App\Models\OtherExpense;

class ToggleExpenseValidationAction
{
    /**
     * Basculer l'état de validation d'une dépense
     */
    public function execute(string $expenseType, int $id): array
    {
        try {
            $expense = $expenseType === 'fee'
                ? ExpenseFee::findOrFail($id)
                : OtherExpense::findOrFail($id);

            // Inverser l'état de validation
            $expense->is_validated = !$expense->is_validated;
            $expense->save();

            $status = $expense->is_validated ? 'validée' : 'invalidée';

            return [
                'success' => true,
                'message' => "Dépense {$status} avec succès",
                'is_validated' => $expense->is_validated,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors du changement de statut: ' . $e->getMessage(),
                'is_validated' => null,
            ];
        }
    }
}
