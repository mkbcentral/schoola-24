<?php

namespace App\Domain\Contract\Student;

use App\Models\Payment;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Type\Decimal;

interface  IPayment
{
    //Créer un nouveau payment
    public static function create(array $input): Payment;
    //Recuperer un  payment
    public static function get(int $id): Payment;
    //Mettre à jour un  payment
    public static function update(Payment $payment, array $input): bool;
    //Retirer un  payment
    public static function delete(Payment $payment): bool;
    //Marquer un  payment comme payé
    public static function makeIsPaid(Payment $payment): bool;
    //Recuperer la liste des paiments par date
    public static function getListByDate(
        string $date,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
    ): Collection;
    //Recuperer la liste des paiments par mois
    public static function getListByMonth(
        string $month,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
    ): Collection;
    //Recuperer le nombre total des paiments par date
    public static function getTotalCountByDate(
        string $date,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
    ): int;
    //Recuperer le nombre total des paiments par mois
    public static function getTotalCountByMonth(
        string $month,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
    ): int;
    //Recuperer le montant total des paiments par date
    public static function getTotalAmountByDate(
        string $date,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
    ): Decimal;
    //Recuperer le montant total des paiments par mois
    public static function getTotalAmountByMonth(
        string $month,
        ?int $sectionId,
        ?int $optionId,
        int $classRoomId,
    ): Decimal;
}
