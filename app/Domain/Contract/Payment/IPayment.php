<?php

namespace App\Domain\Contract\Payment;

use App\Models\Payment;

interface IPayment
{
    /**
     * Créer un nouveau paiement
     */
    public function create(array $input): ?Payment;

    /**
     * Récupérer une liste de paiements avec filtres
     *
     * @param  ?string  $date Date de paiement
     * @param  ?string  $month Mois de paiement
     * @param  ?string  $q Recherche par nom d'élève
     * @param  ?int  $categoryFeeId ID catégorie de frais
     * @param  ?int  $feeId ID du frais scolaire
     * @param  ?int  $sectionId ID de la section
     * @param  ?int  $optionId ID de l'option
     * @param  ?int  $classRoomId ID de la classe
     * @param  ?bool  $isPaid Statut de paiement
     * @param  ?int  $userId ID de l'utilisateur
     * @param  int  $perPage Nombre d'éléments par page
     */
    public function getList(
        ?string $date,
        ?string $month,
        ?string $q,
        ?int $categoryFeeId,
        ?int $feeId,
        ?int $sectionId,
        ?int $optionId,
        ?int $classRoomId,
        ?bool $isPaid,
        ?int $userId,
        int $perPage
    ): mixed;

    /**
     * Retourner le montant total de paiements avec filtres
     *
     * @param  ?string  $date Date de paiement
     * @param  ?string  $month Mois de paiement
     * @param  ?int  $categoryFeeId ID catégorie de frais
     * @param  ?int  $feeId ID du frais scolaire
     * @param  ?int  $sectionId ID de la section
     * @param  ?int  $optionId ID de l'option
     * @param  ?int  $classRoomId ID de la classe
     * @param  ?bool  $isPaid Statut de paiement
     * @param  ?bool  $isAccessory Est accessoire
     * @param  ?int  $userId ID de l'utilisateur
     * @param  ?string  $currency Devise (CDF/USD)
     */
    public function getTotal(
        ?string $date,
        ?string $month,
        ?int $categoryFeeId,
        ?int $feeId,
        ?int $sectionId,
        ?int $optionId,
        ?int $classRoomId,
        ?bool $isPaid,
        ?bool $isAccessory,
        ?int $userId,
        ?string $currency
    ): float;

    /**
     * Retourner le nombre total de paiements avec filtres
     *
     * @param  ?string  $date Date de paiement
     * @param  ?string  $month Mois de paiement
     * @param  ?int  $categfeeIdoryFeeId ID catégorie de frais
     * @param  ?int  $feeId ID du frais scolaire
     * @param  ?int  $sectionId ID de la section
     * @param  ?int  $optionId ID de l'option
     * @param  ?int  $classRoomId ID de la classe
     * @param  ?bool  $isPaid Statut de paiement
     */
    public function getCount(
        ?string $date,
        ?string $month,
        ?int $categfeeIdoryFeeId,
        ?int $feeId,
        ?int $sectionId,
        ?int $optionId,
        ?int $classRoomId,
        ?bool $isPaid,
    ): int;
}
