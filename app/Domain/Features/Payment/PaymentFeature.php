<?php

namespace App\Domain\Features\Payment;

use App\Domain\Contract\Payment\IPayment;
use App\Models\Payment;
use App\Models\Rate;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class PaymentFeature implements IPayment
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepository
    ) {}
    /**
     * {@inheritDoc}
     */
    public function create(array $input): ?Payment
    {
        // Génère un numéro de paiement unique basé sur le timestamp et l'ID utilisateur
        $paymentNumber = uniqid('PAY-') . '-' . (Auth::id() ?? '0');

        $data = [
            'payment_number' => $paymentNumber,
            'month' => $input['month'] ?? null,
            'registration_id' => $input['registration_id'] ?? null,
            'scolar_fee_id' => $input['scolar_fee_id'] ?? null,
            'rate_id' => Rate::DEFAULT_RATE_ID(),
            'user_id' => Auth::id(),
        ];

        return $this->paymentRepository->create($data);
    }

    /**
     * {@inheritDoc}
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

        return $this->paymentRepository->getAllWithFilters($filters, $perPage);
    }

    /**
     * {@inheritDoc}
     */
    public function getCount(
        ?string $date,
        ?string $month,
        ?int $categoryFeeId,
        ?int $feeId,
        ?int $sectionId,
        ?int $optionId,
        ?int $classRoomId,
        ?bool $isPaid,
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

        $stats = $this->paymentRepository->getPaymentStatistics($filters);
        return $stats['total_payments'] ?? 0;
    }

    /**
     * {@inheritDoc}
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
    ): float {
        $filters = [
            'date' => $date,
            'month' => $month,
            'categoryFeeId' => $categoryFeeId,
        ];

        // Utilise la méthode du repository qui gère le cache
        $stats = $this->paymentRepository->getPaymentStatistics($filters);
        return $stats['total_amount'] ?? 0.0;
    }

    public function getSinglePaymentForStudentWithMonth(
        int $registrationId,
        int $categoryFeeId,
        int $schoolYearId,
        string $month
    ): ?Payment {
        // Utiliser Payment directement pour requêtes spécifiques non couvertes par le repository
        return Payment::query()
            ->join('scolar_fees', 'payments.scolar_fee_id', 'scolar_fees.id')
            ->join('category_fees', 'scolar_fees.category_fee_id', 'category_fees.id')
            ->join('registrations', 'payments.registration_id', 'registrations.id')
            ->where('payments.month', $month)
            ->where('category_fees.id', $categoryFeeId)
            ->where('registrations.id', $registrationId)
            ->where('payments.is_paid', true)
            ->where('registrations.school_year_id', $schoolYearId)
            ->with(['rate', 'scolarFee', 'registration'])
            ->select('payments.*')
            ->first();
    }

    public function getSinglePaymentForStudentWithTranche(
        int $registrationId,
        int $categoryFeeId,
        int $scolarFeeId
    ): ?Payment {
        // Utiliser Payment directement pour requêtes spécifiques non couvertes par le repository
        return Payment::query()
            ->join('scolar_fees', 'payments.scolar_fee_id', 'scolar_fees.id')
            ->join('category_fees', 'scolar_fees.category_fee_id', 'category_fees.id')
            ->join('registrations', 'payments.registration_id', 'registrations.id')
            ->where('scolar_fees.id', $scolarFeeId)
            ->where('category_fees.id', $categoryFeeId)
            ->where('registrations.id', $registrationId)
            ->where('payments.is_paid', true)
            ->with(['rate', 'scolarFee', 'registration'])
            ->select('payments.*')
            ->first();
    }
}
