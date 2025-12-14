<?php

namespace App\Domain\Features\Payment;

use App\Domain\Contract\Payment\IPaymentRegularization;
use App\Models\PaymentRegularization;

class PaymentRegularizationFeature implements IPaymentRegularization
{
    /**
     * {@inheritDoc}
     */
    public static function getList(
        ?string $date,
        ?string $month,
        ?string $q,
        ?int $categoryFeeId,
        ?int $optionId,
        ?int $classRoomId,
        ?string $sortBy,
        ?bool $sortAsc,
        ?int $per_page
    ): mixed {
        $filters = [
            'date' => $date,
            'month' => $month,
            'q' => $q,
            'categoryFeeId' => $categoryFeeId,
            'optionId' => $optionId,
            'classRoomId' => $classRoomId,
        ];

        return PaymentRegularization::query()
            ->filter($filters)
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->paginate($per_page);
    }

    /**
     * {@inheritDoc}
     */
    public static function getAmountTotal(
        ?string $date,
        ?string $month,
        ?string $q,
        ?int $categoryFeeId,
        ?int $optionId,
        ?int $classRoomId
    ): int|float {
        $filters = [
            'date' => $date,
            'month' => $month,
            'q' => $q,
            'categoryFeeId' => $categoryFeeId,
            'optionId' => $optionId,
            'classRoomId' => $classRoomId,
        ];
        $total = 0;
        $paymentRegularizations = PaymentRegularization::query()
            ->filter($filters)
            ->get();

        foreach ($paymentRegularizations as $paymentRegularization) {
            $total += $paymentRegularization->amount;
        }

        return $total;
    }
}
