<?php

namespace App\Repositories\Contracts;

use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PaymentRepositoryInterface
{
    /**
     * Récupérer tous les paiements avec filtres
     */
    public function getAllWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Récupérer un paiement par son ID avec ses relations
     */
    public function findById(int $id): ?Payment;

    /**
     * Créer un nouveau paiement
     */
    public function create(array $data): Payment;

    /**
     * Mettre à jour un paiement
     */
    public function update(int $id, array $data): bool;

    /**
     * Supprimer un paiement
     */
    public function delete(int $id): bool;

    /**
     * Récupérer les montants totaux par catégorie pour un mois ou une date
     */
    public function getTotalAmountByCategory(?string $month, ?string $date): Collection;

    /**
     * Récupérer les reçus annuels par catégorie
     */
    public function getYearlyReceiptsByCategory(int $categoryId): Collection;

    /**
     * Récupérer les paiements par mois et catégorie
     */
    public function getPaymentsByMonthAndCategory(int $categoryId): Collection;

    /**
     * Récupérer les paiements non payés
     */
    public function getUnpaidPayments(int $perPage = 15): LengthAwarePaginator;

    /**
     * Récupérer les paiements d'un élève
     */
    public function getStudentPayments(int $studentId, int $schoolYearId): Collection;

    /**
     * Calculer le total des paiements pour une période
     */
    public function getTotalForPeriod(?string $startDate, ?string $endDate, ?int $categoryId = null): float;

    /**
     * Récupérer les statistiques de paiement
     */
    public function getPaymentStatistics(array $filters = []): array;
}
