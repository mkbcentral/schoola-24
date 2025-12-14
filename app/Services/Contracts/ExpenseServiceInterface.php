<?php

namespace App\Services\Contracts;

use App\DTOs\ExpenseDTO;
use App\DTOs\ExpenseFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ExpenseServiceInterface
{
    /**
     * Créer une nouvelle dépense
     *
     * @param ExpenseDTO $expenseDTO
     * @return ExpenseDTO
     * @throws \Exception
     */
    public function create(ExpenseDTO $expenseDTO): ExpenseDTO;

    /**
     * Mettre à jour une dépense existante
     *
     * @param int $id
     * @param ExpenseDTO $expenseDTO
     * @return ExpenseDTO
     * @throws \Exception
     */
    public function update(int $id, ExpenseDTO $expenseDTO): ExpenseDTO;

    /**
     * Supprimer une dépense
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function delete(int $id): bool;

    /**
     * Récupérer une dépense par son ID
     *
     * @param int $id
     * @return ExpenseDTO|null
     */
    public function findById(int $id): ?ExpenseDTO;

    /**
     * Récupérer toutes les dépenses avec pagination
     *
     * @param ExpenseFilterDTO $filters
     * @return LengthAwarePaginator
     */
    public function getAll(ExpenseFilterDTO $filters): LengthAwarePaginator;

    /**
     * Calculer le montant total des dépenses selon les filtres
     *
     * @param ExpenseFilterDTO $filters
     * @return float
     */
    public function getTotalAmount(ExpenseFilterDTO $filters): float;

    /**
     * Calculer le montant total des dépenses par devise
     *
     * @param ExpenseFilterDTO $filters
     * @return array ['USD' => 0.0, 'CDF' => 0.0]
     */
    public function getTotalAmountByCurrency(ExpenseFilterDTO $filters): array;

    /**
     * Récupérer les dépenses groupées par mois
     *
     * @param ExpenseFilterDTO $filters
     * @return Collection
     */
    public function getByMonth(ExpenseFilterDTO $filters): Collection;

    /**
     * Récupérer les dépenses groupées par catégorie
     *
     * @param ExpenseFilterDTO $filters
     * @return Collection
     */
    public function getByCategory(ExpenseFilterDTO $filters): Collection;

    /**
     * Récupérer les dépenses pour une période spécifique
     *
     * @param string $period (today, this_week, this_month, etc.)
     * @return Collection
     */
    public function getByPeriod(string $period): Collection;

    /**
     * Vérifier si une dépense existe
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool;

    /**
     * Récupérer les statistiques des dépenses
     *
     * @param ExpenseFilterDTO $filters
     * @return array
     */
    public function getStatistics(ExpenseFilterDTO $filters): array;

    /**
     * Exporter les dépenses selon les filtres
     *
     * @param ExpenseFilterDTO $filters
     * @param string $format ('csv', 'excel', 'pdf')
     * @return mixed
     */
    public function export(ExpenseFilterDTO $filters, string $format = 'excel'): mixed;
}
