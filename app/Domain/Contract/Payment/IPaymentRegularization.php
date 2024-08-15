<?php

namespace App\Domain\Contract\Payment;

interface IPaymentRegularization
{

    /**
     * Récupérer la liste des regularisation des payment
     * @param mixed $date
     * @param mixed $month
     * @param mixed $q
     * @param mixed $categoryFeeId
     * @param mixed $optionId
     * @param mixed $classRoomId
     * @param mixed $sortBy
     * @param mixed $sortAsc
     * @param mixed $per_page
     * @return mixed
     */
    public static function getList(
        ?string $date,
        ?string $month,
        ?string $q,
        ?int $categoryFeeId,
        ?int $optionId,
        ?int $classRoomId,
        ?string $sortBy,
        ?bool   $sortAsc,
        ?int $per_page
    ): mixed;

    /**
     * Recuperer le montant total de paiement
     * @param mixed $date
     * @param mixed $month
     * @param mixed $q
     * @param mixed $categoryFeeId
     * @param mixed $optionId
     * @param mixed $classRoomId
     * @return int|float
     */
    public static function getAmountTotal(
        ?string $date,
        ?string $month,
        ?string $q,
        ?int $categoryFeeId,
        ?int $optionId,
        ?int $classRoomId,
    ): int|float;
}
