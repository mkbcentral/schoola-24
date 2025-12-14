<?php

namespace App\Services\Contracts;

use App\DTOs\OtherExpenseDTO;
use App\DTOs\OtherExpenseFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OtherExpenseServiceInterface
{
    /**
     * Créer une nouvelle dépense
     *
     * @param OtherExpenseDTO $expenseDTO
     * @return OtherExpenseDTO
     * @throws \Exception
     */
    public function create(OtherExpenseDTO $expenseDTO): OtherExpenseDTO;

    /**
     * Mettre à jour une dépense existante
     *
     * @param int $id
     * @param OtherExpenseDTO $expenseDTO
     * @return OtherExpenseDTO
     * @throws \Exception
     */
    public function update(int $id, OtherExpenseDTO $expenseDTO): OtherExpenseDTO;

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
     * @return OtherExpenseDTO|null
     */
    public function findById(int $id): ?OtherExpenseDTO;

    /**
     * Récupérer toutes les dépenses avec pagination
     *
     * @param OtherExpenseFilterDTO $filters
     * @return LengthAwarePaginator
     */
    public function getAll(OtherExpenseFilterDTO $filters): LengthAwarePaginator;

    /**
     * Calculer le montant total des dépenses selon les filtres
     *
     * @param OtherExpenseFilterDTO $filters
     * @return float
     */
    public function getTotalAmount(OtherExpenseFilterDTO $filters): float;

    /**
     * Calculer le montant total des dépenses par devise
     *
     * @param OtherExpenseFilterDTO $filters
     * @return array ['USD' => 0.0, 'CDF' => 0.0]
     */
    public function getTotalAmountByCurrency(OtherExpenseFilterDTO $filters): array;

    /**
     * Récupérer les dépenses groupées par mois
     *
     * @param OtherExpenseFilterDTO $filters
     * @return Collection
     */
    public function getByMonth(OtherExpenseFilterDTO $filters): Collection;

    /**
     * Récupérer les dépenses groupées par catégorie
     *
     * @param OtherExpenseFilterDTO $filters
     * @return Collection
     */
    public function getByCategory(OtherExpenseFilterDTO $filters): Collection;

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
     * @param OtherExpenseFilterDTO $filters
     * @return array
     */
    public function getStatistics(OtherExpenseFilterDTO $filters): array;

    /**
     * Exporter les dépenses selon les filtres
     *
     * @param OtherExpenseFilterDTO $filters
     * @param string $format ('csv', 'excel', 'pdf')
     * @return mixed
     */
    public function export(OtherExpenseFilterDTO $filters, string $format = 'excel'): mixed;
}
