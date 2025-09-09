<?php

namespace App\Domain\Features\Payment;

use App\Domain\Contract\Payment\IPayment;
use App\Models\Payment;
use App\Models\Rate;
use Illuminate\Support\Facades\Auth;

class PaymentFeature implements IPayment
{
    /**
     * @inheritDoc
     */
    public static function create(array $input): Payment|null
    {
        // Génère un numéro de paiement unique basé sur le timestamp et l'ID utilisateur
        $paymentNumber = uniqid('PAY-') . '-' . (Auth::id() ?? '0');
        return Payment::create([
            'payment_number' => $paymentNumber,
            'month' => $input['month'] ?? null,
            'registration_id' => $input['registration_id'] ?? null,
            'scolar_fee_id' => $input['scolar_fee_id'] ?? null,
            'rate_id' => Rate::DEFAULT_RATE_ID(),
            'user_id' => Auth::id()
        ]);
    }
    /**
     * @inheritDoc
     */
    public static function getList(
        string|null $date,
        string|null $month,
        string|null $q,
        int|null    $categoryFeeId,
        int|null    $feeId,
        int|null    $sectionId,
        int|null    $optionId,
        int|null    $classRoomId,
        bool|null   $isPaid,
        int|null   $userId,
        int         $perPage
    ): mixed {
        $filters = [
            'date' => $date,
            'month' => $month,
            'key_to_search' => $q,
            'categoryFeeId' => $categoryFeeId,
            'feeId' => $feeId,
            'sectionId' => $sectionId,
            'optionId' => $optionId,
            'classRoomId' => $classRoomId,
            'isPaid' => $isPaid,
            'isAccessory' => null,
            'userId' => $userId,
        ];
        return Payment::query()
            ->filter($filters)
            ->paginate($perPage);
    }
    /**
     * @inheritDoc
     */
    public static function getCount(
        string|null $date,
        string|null $month,
        int|null    $categoryFeeId,
        int|null    $feeId,
        int|null    $sectionId,
        int|null    $optionId,
        int|null    $classRoomId,
        bool|null   $isPaid,
    ): int {
        $filters = [
            'date' => $date,
            'month' => $month,
            'categoryFeeId' => $categoryFeeId,
            'feeId' => $feeId,
            'sectionId' => $sectionId,
            'optionId' => $optionId,
            'classRoomId' => $classRoomId,
            'isPaid' => $isPaid,
            'isAccessory' => null,
            'userId' => null,
            'key_to_search' => null,
        ];
        return Payment::query()->filter($filters)->count();
    }
    /**
     * @inheritDoc
     */
    public static function getTotal(
        string|null $date,
        string|null $month,
        int|null $categoryFeeId,
        int|null $feeId,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        bool|null $isPaid,
        bool|null $isAccessory,
        int|null   $userId,
        string|null $currency
    ): float {
        $filters = [
            'date' => $date,
            'month' => $month,
            'key_to_search' => '',
            'categoryFeeId' => $categoryFeeId,
            'feeId' => $feeId,
            'sectionId' => $sectionId,
            'optionId' => $optionId,
            'classRoomId' => $classRoomId,
            'isPaid' => $isPaid,
            'isAccessory' => $isAccessory,
            'userId' => $userId,
        ];
        // Utilise la somme SQL pour plus de performance
        return (float) Payment::query()
            ->filter($filters)

            ->sum('scolar_fees.amount');
    }

    public static function getSinglePaymentForStudentWithMonth(
        int $registrationId,
        int $categoryFeeId,
        int $schoolYearId,
        string $month
    ): ?Payment {
        return Payment::query()
            ->notFilter()
            ->where('payments.month', $month)
            ->where('category_fees.id', $categoryFeeId)
            ->where('registrations.id', $registrationId)
            ->where('payments.is_paid', true)
            ->where(
                'registrations.school_year_id',
                $schoolYearId
            )
            ->with([
                'rate',
                'scolarFee',
                'registration'
            ])
            ->first();
    }

    public static function getSinglePaymentForStudentWithTranche(
        int $registrationId,
        int $categoryFeeId,
        int $scolarFeeId
    ): ?Payment {
        return Payment::query()
            ->notFilter()
            ->where('scolar_fees.id', $scolarFeeId)
            ->where('category_fees.id', $categoryFeeId)
            ->where('registrations.id', $registrationId)
            ->with([
                'rate',
                'scolarFee',
                'registration'
            ])
            ->where('payments.is_paid', true)
            ->first();
    }
}
