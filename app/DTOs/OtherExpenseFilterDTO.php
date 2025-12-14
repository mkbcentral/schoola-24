<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

class OtherExpenseFilterDTO
{
    public function __construct(
        public ?string $date = null,
        public ?string $month = null,
        public ?int $otherSourceExpenseId = null,
        public ?int $categoryExpenseId = null,
        public ?string $currency = null,
        public ?string $period = null, // today, this_week, last_week, 2_weeks_ago, 3_weeks_ago, this_month, last_month, 3_months, 6_months, 9_months, this_year
        public ?Carbon $startDate = null,
        public ?Carbon $endDate = null,
        public int $perPage = 10,
        public string $sortBy = 'created_at',
        public string $sortDirection = 'desc',
    ) {
        $this->applyPeriodFilter();
    }

    /**
     * Créer un DTO depuis un tableau
     */
    public static function fromArray(array $data): self
    {
        return new self(
            date: !empty($data['date']) ? $data['date'] : null,
            month: !empty($data['month']) ? $data['month'] : null,
            otherSourceExpenseId: $data['other_source_expense_id'] ?? $data['otherSourceExpenseId'] ?? null,
            categoryExpenseId: $data['category_expense_id'] ?? $data['categoryExpenseId'] ?? null,
            currency: !empty($data['currency']) ? $data['currency'] : null,
            period: !empty($data['period']) ? $data['period'] : null,
            startDate: isset($data['start_date']) || isset($data['startDate']) ? Carbon::parse($data['start_date'] ?? $data['startDate']) : null,
            endDate: isset($data['end_date']) || isset($data['endDate']) ? Carbon::parse($data['end_date'] ?? $data['endDate']) : null,
            perPage: $data['per_page'] ?? $data['perPage'] ?? 10,
            sortBy: $data['sort_by'] ?? $data['sortBy'] ?? 'created_at',
            sortDirection: $data['sort_direction'] ?? $data['sortDirection'] ?? 'desc',
        );
    }

    /**
     * Appliquer les filtres de période prédéfinis
     */
    private function applyPeriodFilter(): void
    {
        if (!$this->period) {
            return;
        }

        $now = Carbon::now();

        match ($this->period) {
            'today' => $this->setDateRange($now->copy(), $now->copy()),
            'yesterday' => $this->setDateRange($now->copy()->subDay(), $now->copy()->subDay()),
            'this_week' => $this->setDateRange($now->copy()->startOfWeek(), $now->copy()->endOfWeek()),
            'last_week' => $this->setDateRange(
                $now->copy()->subWeek()->startOfWeek(),
                $now->copy()->subWeek()->endOfWeek()
            ),
            '2_weeks_ago' => $this->setDateRange(
                $now->copy()->subWeeks(2)->startOfWeek(),
                $now->copy()->subWeeks(2)->endOfWeek()
            ),
            '3_weeks_ago' => $this->setDateRange(
                $now->copy()->subWeeks(3)->startOfWeek(),
                $now->copy()->subWeeks(3)->endOfWeek()
            ),
            'this_month' => $this->setDateRange($now->copy()->startOfMonth(), $now->copy()->endOfMonth()),
            'last_month' => $this->setDateRange(
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth()
            ),
            'this_quarter' => $this->setDateRange($now->copy()->startOfQuarter(), $now->copy()->endOfQuarter()),
            'last_quarter' => $this->setDateRange(
                $now->copy()->subQuarter()->startOfQuarter(),
                $now->copy()->subQuarter()->endOfQuarter()
            ),
            '3_months' => $this->setDateRange($now->copy()->subMonths(3), $now->copy()),
            '6_months' => $this->setDateRange($now->copy()->subMonths(6), $now->copy()),
            '9_months' => $this->setDateRange($now->copy()->subMonths(9), $now->copy()),
            'this_year' => $this->setDateRange($now->copy()->startOfYear(), $now->copy()->endOfYear()),
            'last_year' => $this->setDateRange(
                $now->copy()->subYear()->startOfYear(),
                $now->copy()->subYear()->endOfYear()
            ),
            default => null,
        };
    }

    /**
     * Définir la plage de dates
     */
    private function setDateRange(Carbon $start, Carbon $end): void
    {
        $this->startDate = $start;
        $this->endDate = $end;
    }

    /**
     * Vérifier si un filtre de date est actif
     */
    public function hasDateFilter(): bool
    {
        return $this->date !== null || $this->startDate !== null || $this->endDate !== null;
    }

    /**
     * Vérifier si un filtre est actif
     */
    public function hasAnyFilter(): bool
    {
        return $this->date !== null
            || $this->month !== null
            || $this->otherSourceExpenseId !== null
            || $this->categoryExpenseId !== null
            || $this->currency !== null
            || $this->period !== null
            || $this->startDate !== null
            || $this->endDate !== null;
    }

    /**
     * Convertir en tableau pour la compatibilité
     */
    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'month' => $this->month,
            'otherSourceExpenseId' => $this->otherSourceExpenseId,
            'categoryExpenseId' => $this->categoryExpenseId,
            'currency' => $this->currency,
            'period' => $this->period,
            'startDate' => $this->startDate?->format('Y-m-d'),
            'endDate' => $this->endDate?->format('Y-m-d'),
            'perPage' => $this->perPage,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
        ];
    }
}
