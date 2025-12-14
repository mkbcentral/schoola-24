<?php

namespace App\Actions\Expense;

use App\Services\Expense\OtherSourceExpenseServiceInterface;

class UpdateOtherSourceExpenseAction
{
    public function __construct(
        private OtherSourceExpenseServiceInterface $otherSourceExpenseService
    ) {}

    /**
     * Execute the action to update an other source expense
     *
     * @param  int  $id
     * @param  array  $data  ['name' => string]
     * @return array ['success' => bool, 'message' => string]
     */
    public function execute(int $id, array $data): array
    {
        try {
            $otherSourceExpense = $this->otherSourceExpenseService->findById($id);

            if (! $otherSourceExpense) {
                return [
                    'success' => false,
                    'message' => 'Source non trouvée',
                ];
            }

            $this->otherSourceExpenseService->update($otherSourceExpense, $data);

            return [
                'success' => true,
                'message' => 'Source modifiée avec succès',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la modification: ' . $e->getMessage(),
            ];
        }
    }
}
