<?php

namespace App\Actions\Expense;

use App\Models\OtherSourceExpense;
use App\Services\Expense\OtherSourceExpenseServiceInterface;

class CreateOtherSourceExpenseAction
{
    public function __construct(
        private OtherSourceExpenseServiceInterface $otherSourceExpenseService
    ) {}

    /**
     * Execute the action to create a new other source expense
     *
     * @param  array  $data  ['name' => string]
     * @return array ['success' => bool, 'message' => string, 'otherSourceExpense' => OtherSourceExpense|null]
     */
    public function execute(array $data): array
    {
        try {
            $otherSourceExpense = $this->otherSourceExpenseService->create($data);

            return [
                'success' => true,
                'message' => 'Source créée avec succès',
                'otherSourceExpense' => $otherSourceExpense,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage(),
                'otherSourceExpense' => null,
            ];
        }
    }
}
