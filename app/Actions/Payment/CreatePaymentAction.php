<?php

namespace App\Actions\Payment;

use App\Domain\Helpers\DateFormatHelper;
use App\Services\Student\StudentDebtTrackerService;

class CreatePaymentAction
{
    public function __construct(
        private StudentDebtTrackerService $debtTrackerService
    ) {}

    /**
     * Créer un nouveau paiement
     *
     * @param int $registrationId
     * @param int $categoryFeeId
     * @param string $month
     * @param array $attributes
     * @return array
     */
    public function execute(int $registrationId, int $categoryFeeId, string $month, array $attributes): array
    {
        // Convertir le numéro du mois en label (ex: '10' => 'OCTOBRE')
        $monthLabel = DateFormatHelper::getMonthLabelFromNumber($month);

        return $this->debtTrackerService->payForMonth(
            $registrationId,
            $categoryFeeId,
            $monthLabel,
            $attributes
        );
    }
}
