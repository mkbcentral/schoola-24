<?php

namespace App\Domain\Contract\Payment;

use App\Models\Payment;



interface  IPayment
{

    /**
     * Créer un nouveau paymeny
     * @param array $input
     * @return \App\Models\Payment
     */
    public static function create(array $input): ?Payment;
    /**
     * Summary of getList
     * @param mixed $date
     * @param mixed $month
     * @param mixed $q
     * @param mixed $categoryFeeId
     * @param mixed $feeId
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param mixed $classRoomId
     * @param mixed $isPaid
     * @param int $perPage
     * @return mixed
     */
    public static function getList(
        ?string $date,
        ?string $month,
        ?string $q,
        ?int $categoryFeeId,
        ?int $feeId,
        ?int $sectionId,
        ?int $optionId,
        ?int $classRoomId,
        ?bool $isPaid,
        int $perPage
    ): mixed;

    /**
     * Retouter le montant total de payments
     * @param mixed $date
     * @param mixed $month
     * @param mixed $categoryFeeId
     * @param mixed $feeId
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param mixed $classRoomId
     * @param mixed $isPaid
     * @param mixed $currency
     * @return float
     */
    public static function getTotal(
        ?string $date,
        ?string $month,
        ?int $categoryFeeId,
        ?int $feeId,
        ?int $sectionId,
        ?int $optionId,
        ?int $classRoomId,
        ?bool $isPaid,
        ?string $currency
    ): float;


    /**
     * Retouter le nombre total de payments
     * @param mixed $date
     * @param mixed $month
     * @param mixed $categfeeIdoryFeeId
     * @param mixed $feeId
     * @param mixed $sectionId
     * @param mixed $optionId
     * @param mixed $classRoomId
     * @param mixed $isPaid
     * @return int
     */
    public static function getCount(
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
