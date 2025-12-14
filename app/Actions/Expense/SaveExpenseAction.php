<?php

namespace App\Actions\Expense;

use App\DTOs\ExpenseDTO;
use App\DTOs\OtherExpenseDTO;
use App\Models\SchoolYear;
use App\Services\Contracts\ExpenseServiceInterface;
use App\Services\Contracts\OtherExpenseServiceInterface;

class SaveExpenseAction
{
    public function __construct(
        private readonly ExpenseServiceInterface $expenseService,
        private readonly OtherExpenseServiceInterface $otherExpenseService
    ) {}

    /**
     * Exécuter l'action de sauvegarde
     *
     * @param string $expenseType 'fee' ou 'other'
     * @param array $data Données du formulaire
     * @param bool $isEditing Mode édition ou création
     * @return array ['success' => bool, 'message' => string]
     */
    public function execute(string $expenseType, array $data, bool $isEditing = false): array
    {
        try {
            // Ajouter l'année scolaire par défaut
            $data['schoolYearId'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

            if ($expenseType === 'fee') {
                return $this->saveExpenseFee($data, $isEditing);
            }

            return $this->saveOtherExpense($data, $isEditing);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Sauvegarder une dépense sur frais
     */
    private function saveExpenseFee(array $data, bool $isEditing): array
    {
        $dto = ExpenseDTO::fromArray($data);

        if ($isEditing) {
            $this->expenseService->update($data['id'], $dto);
            $message = 'Dépense sur frais modifiée avec succès';
        } else {
            $this->expenseService->create($dto);
            $message = 'Dépense sur frais créée avec succès';
        }

        return [
            'success' => true,
            'message' => $message,
        ];
    }

    /**
     * Sauvegarder une autre dépense
     */
    private function saveOtherExpense(array $data, bool $isEditing): array
    {
        $dto = OtherExpenseDTO::fromArray($data);

        if ($isEditing) {
            $this->otherExpenseService->update($data['id'], $dto);
            $message = 'Autre dépense modifiée avec succès';
        } else {
            $this->otherExpenseService->create($dto);
            $message = 'Autre dépense créée avec succès';
        }

        return [
            'success' => true,
            'message' => $message,
        ];
    }
}
