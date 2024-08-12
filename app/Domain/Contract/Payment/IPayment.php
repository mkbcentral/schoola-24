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
     * Récuprer le payment
     * @param int $id
     * @return \App\Models\Payment
     */
    public static function get(int $id): ?Payment;

    /**
     * Mettre à jour le payement
     * @param \App\Models\Payment $payment
     * @param array $input
     * @return bool
     */
    public static function update(Payment $payment, array $input): bool;
    /**
     * Retirer le payment
     * @param \App\Models\Payment $payment
     * @return bool
     */
    public static function delete(Payment $payment): bool;
    /**
     * Marquer le payment payé
     * @param \App\Models\Payment $payment
     * @return bool
     */
    public static function makeIsPaid(Payment $payment): bool;

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
