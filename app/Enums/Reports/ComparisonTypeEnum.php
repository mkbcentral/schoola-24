<?php

namespace App\Enums\Reports;

enum ComparisonTypeEnum: string
{
    case MONTH_TO_MONTH = 'month_to_month';
    case YEAR_TO_YEAR = 'year_to_year';
    case QUARTER_TO_QUARTER = 'quarter_to_quarter';
    case CUSTOM_PERIOD = 'custom_period';

    public function label(): string
    {
        return match ($this) {
            self::MONTH_TO_MONTH => 'Mois à Mois',
            self::YEAR_TO_YEAR => 'Année à Année',
            self::QUARTER_TO_QUARTER => 'Trimestre à Trimestre',
            self::CUSTOM_PERIOD => 'Période Personnalisée',
        };
    }
}
