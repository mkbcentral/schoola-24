<?php

namespace App\DTOs;

class StudentDebtDTO
{
    public function __construct(
        public readonly int $registrationId,
        public readonly int $studentId,
        public readonly string $studentName,
        public readonly string $studentCode,
        public readonly string $registrationNumber,
        public readonly string $sectionName,
        public readonly string $optionName,
        public readonly string $classRoomName,
        public readonly string $registrationMonth,
        public readonly int $registrationMonthNumber,
        public readonly string $registrationDate,
        public readonly int $totalMonthsExpected,
        public readonly int $totalMonthsPaid,
        public readonly int $monthsUnpaid,
        public readonly array $unpaidMonths,
        public readonly array $paidMonths,
        public readonly float $totalAmountDue,
        public readonly float $totalAmountPaid,
        public readonly float $totalDebtAmount,
        public readonly ?string $responsibleName = null,
        public readonly ?string $responsiblePhone = null,
    ) {}

    /**
     * Créer un DTO à partir d'un array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            registrationId: $data['registration_id'],
            studentId: $data['student_id'],
            studentName: $data['student_name'],
            studentCode: $data['student_code'] ?? '',
            registrationNumber: $data['registration_number'] ?? '',
            sectionName: $data['section_name'],
            optionName: $data['option_name'],
            classRoomName: $data['class_room_name'],
            registrationMonth: $data['registration_month'],
            registrationMonthNumber: $data['registration_month_number'],
            registrationDate: $data['registration_date'],
            totalMonthsExpected: $data['total_months_expected'],
            totalMonthsPaid: $data['total_months_paid'],
            monthsUnpaid: $data['months_unpaid'],
            unpaidMonths: $data['unpaid_months'],
            paidMonths: $data['paid_months'] ?? [],
            totalAmountDue: $data['total_amount_due'],
            totalAmountPaid: $data['total_amount_paid'],
            totalDebtAmount: $data['total_debt_amount'],
            responsibleName: $data['responsible_name'] ?? null,
            responsiblePhone: $data['responsible_phone'] ?? null,
        );
    }

    /**
     * Convertir le DTO en array
     */
    public function toArray(): array
    {
        return [
            'registration_id' => $this->registrationId,
            'student_id' => $this->studentId,
            'student_name' => $this->studentName,
            'student_code' => $this->studentCode,
            'registration_number' => $this->registrationNumber,
            'section_name' => $this->sectionName,
            'option_name' => $this->optionName,
            'class_room_name' => $this->classRoomName,
            'registration_month' => $this->registrationMonth,
            'registration_month_number' => $this->registrationMonthNumber,
            'registration_date' => $this->registrationDate,
            'total_months_expected' => $this->totalMonthsExpected,
            'total_months_paid' => $this->totalMonthsPaid,
            'months_unpaid' => $this->monthsUnpaid,
            'unpaid_months' => $this->unpaidMonths,
            'paid_months' => $this->paidMonths,
            'total_amount_due' => $this->totalAmountDue,
            'total_amount_paid' => $this->totalAmountPaid,
            'total_debt_amount' => $this->totalDebtAmount,
            'responsible_name' => $this->responsibleName,
            'responsible_phone' => $this->responsiblePhone,
        ];
    }

    /**
     * Vérifier si l'élève a une dette de 2 mois ou plus
     */
    public function hasSignificantDebt(): bool
    {
        return $this->monthsUnpaid >= 2;
    }

    /**
     * Obtenir le pourcentage de paiement
     */
    public function getPaymentPercentage(): float
    {
        if ($this->totalMonthsExpected === 0) {
            return 0;
        }

        return round(($this->totalMonthsPaid / $this->totalMonthsExpected) * 100, 2);
    }

    /**
     * Obtenir une liste formatée des mois impayés
     */
    public function getFormattedUnpaidMonths(): string
    {
        return implode(', ', $this->unpaidMonths);
    }
}
