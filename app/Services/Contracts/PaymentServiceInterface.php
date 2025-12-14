<?php

namespace App\Services\Contracts;

use App\DTOs\Payment\PaymentFilterDTO;
use App\DTOs\Payment\PaymentResultDTO;
use App\Models\Payment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface pour le service de gestion des paiements
 */
interface PaymentServiceInterface
{
    /**
     * Créer un nouveau paiement
     *
     * @param array $data Données du paiement
     * @return Payment
     */
    public function create(array $data): Payment;

    /**
     * Retourner un paiement par son ID
     *
     * @param int $id ID du paiement
     * @return Payment|null
     */
    public function find(int $id): ?Payment;

    /**
     * Mettre à jour un paiement
     *
     * @param int $id ID du paiement
     * @param array $data Nouvelles données
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Supprimer un paiement
     *
     * @param int $id ID du paiement
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Récupérer la liste des paiements avec filtres multiples
     * Retourne : liste paginée, nombre total, totaux par devise
     *
     * @param PaymentFilterDTO $filters Filtres à appliquer
     * @param int $perPage Nombre d'éléments par page
     * @param int $page Numéro de la page
     * @return PaymentResultDTO
     */
    public function getFilteredPayments(PaymentFilterDTO $filters, int $perPage = 15, int $page = 1): PaymentResultDTO;

    /**
     * Invalider le cache des paiements
     *
     * @return void
     */
    public function clearCache(): void;
}
