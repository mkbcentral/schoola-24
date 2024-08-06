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
            ]);;
    }

    /**
     * @inheritDoc
     */
    public static function delete(Payment $payment): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public static function get(int $id): Payment|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public static function getCount(
        string|null $date,
        string|null $month,
        int|null $categfeeIdoryFeeId,
        int|null $feeId,
        int|null $sectionId,
        int|null $optionId,
        int $classRoomId
    ): int {
        return 0;
    }

    /**
     * @inheritDoc
     */
    public static function getList(
        string|null $date,
        string|null $month,
        int|null $categoryFeeId,
        int|null $feeId,
        int|null $sectionId,
        int|null $optionId,
        int|null $classRoomId,
        int $perPage
    ): mixed {
        $filters = [
            'date' => $date,
            'month' => $month,
            'categoryFeeId' => $categoryFeeId,
            'feeId' => $feeId,
            'sectionId' => $sectionId,
            'optionId' => $optionId,
            'classRoomId' => $classRoomId,
        ];
        return Payment::query()
            ->filter($filters)
            ->paginate($perPage);
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
        int $classRoomId,
        string|null $currency,
    ): float {
        $filters = [
            'date' => $date,
            'month' => $month,
            'categoryFeeId' => $categoryFeeId,
            'feeId' => $feeId,
            'sectionId' => $sectionId,
            'optionId' => $optionId,
            'classRoomId' => $classRoomId,
        ];

        $total = 0;
        $payments = Payment::query()
            ->filter($filters)
            ->get();
        foreach ($payments as $payment) {
            if ($payment->scolarFee->currency == "USD" && $currency == "USD") {
                $total += $payment->scolarFee->amount * $payment->rate->amount;
            } else {
                $total += $payment->scolarFee->amount;
            }
        }

        return $total;
    }

    /**
     * @inheritDoc
     */
    public static function makeIsPaid(Payment $payment): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public static function update(Payment $payment, array $input): bool
    {
        return false;
    }
}
