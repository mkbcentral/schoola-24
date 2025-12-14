<?php

namespace App\DTOs\Payment;

/**
 * Data Transfer Object pour les filtres de paiement
 */
class PaymentFilterDTO
{
    public function __construct(
        public readonly ?string $date = null,
        public readonly ?string $month = null,
        public readonly ?string $period = null, // Format: 'YYYY-MM-DD:YYYY-MM-DD' ou 'start_date:end_date'
        public readonly ?string $dateRange = null, // 'this_week', 'last_2_weeks', 'last_3_weeks', 'this_month', 'last_3_months', 'last_6_months', 'last_9_months'
        public readonly ?int $categoryFeeId = null,
        public readonly ?int $feeId = null,
        public readonly ?int $sectionId = null,
        public readonly ?int $optionId = null,
        public readonly ?int $classRoomId = null,
        public readonly ?bool $isPaid = null,
        public readonly ?int $userId = null,
        public readonly ?string $currency = null, // 'CDF', 'USD', etc.
        public readonly ?string $search = null, // Recherche par nom d'élève
    ) {}

    /**
     * Créer un DTO depuis un tableau de données
     *
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'] ?? null,
            month: $data['month'] ?? null,
            period: $data['period'] ?? null,
            dateRange: $data['dateRange'] ?? null,
            categoryFeeId: isset($data['categoryFeeId']) ? (int) $data['categoryFeeId'] : null,
            feeId: isset($data['feeId']) ? (int) $data['feeId'] : null,
            sectionId: isset($data['sectionId']) ? (int) $data['sectionId'] : null,
            optionId: isset($data['optionId']) ? (int) $data['optionId'] : null,
            classRoomId: isset($data['classRoomId']) ? (int) $data['classRoomId'] : null,
            isPaid: isset($data['isPaid']) ? (bool) $data['isPaid'] : null,
            userId: isset($data['userId']) ? (int) $data['userId'] : null,
            currency: $data['currency'] ?? null,
            search: $data['search'] ?? $data['key_to_search'] ?? null,
        );
    }

    /**
     * Convertir en tableau pour utilisation avec Query Builder
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'date' => $this->date,
            'month' => $this->month,
            'period' => $this->period,
            'dateRange' => $this->dateRange,
            'categoryFeeId' => $this->categoryFeeId,
            'feeId' => $this->feeId,
            'sectionId' => $this->sectionId,
            'optionId' => $this->optionId,
            'classRoomId' => $this->classRoomId,
            'isPaid' => $this->isPaid,
            'userId' => $this->userId,
            'currency' => $this->currency,
            'search' => $this->search,
        ], fn($value) => $value !== null);
    }

    /**
     * Générer une clé de cache unique basée sur les filtres
     *
     * @return string
     */
    public function toCacheKey(): string
    {
        return 'payments.filtered.' . md5(json_encode($this->toArray()));
    }

    /**
     * Vérifier si des filtres sont actifs
     *
     * @return bool
     */
    public function hasFilters(): bool
    {
        return ! empty($this->toArray());
    }
}
