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
        return
            Payment::create([
                'payment_number' => rand(100, 1000),
                'month' => $input['month'],
                'registration_id' => $input['registration_id'],
                'scolar_fee_id' => $input['scolar_fee_id'],
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
        int|null    $categfeeIdoryFeeId,
        int|null    $feeId,
        int|null    $sectionId,
        int|null    $optionId,
        int|null    $classRoomId,
        bool|null   $isPaid,
    ): int {
        return 0;
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

        $total = 0;
        $payments = Payment::query()
            ->filter($filters)
            ->get();
        foreach ($payments as $payment) {
            $total += $payment->scolarFee->amount;
        }

        return $total;
    }

    public static function getSinglePaymentForStudentWithMonth(
        int $registrationId,
        int $categoryFeeId,
        string $month
    ): ?Payment {
        return Payment::query()
            ->notFilter()
            ->where('payments.month', $month)
            ->where('category_fees.id', $categoryFeeId)
            ->where('registrations.id', $registrationId)
            ->where('payments.is_paid', true)
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
