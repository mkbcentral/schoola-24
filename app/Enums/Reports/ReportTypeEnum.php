<?php

namespace App\Enums\Reports;

enum ReportTypeEnum: string
{
    case COMPARISON = 'comparison';
    case FORECAST = 'forecast';
    case TREASURY = 'treasury';
    case PROFITABILITY = 'profitability';
    case CLASS_REPORT = 'class';
    case UNPAID = 'unpaid';
    case EXECUTIVE = 'executive';
    case EXPENSE_CATEGORY = 'expense_category';
    case COMPLIANCE = 'compliance';
    case SEASONALITY = 'seasonality';

    public function label(): string
    {
        return match ($this) {
            self::COMPARISON => 'Comparaison Périodique',
            self::FORECAST => 'Prévisions',
            self::TREASURY => 'Trésorerie',
            self::PROFITABILITY => 'Rentabilité',
            self::CLASS_REPORT => 'Par Classe',
            self::UNPAID => 'Impayés',
            self::EXECUTIVE => 'Tableau de Bord Exécutif',
            self::EXPENSE_CATEGORY => 'Catégories de Dépenses',
            self::COMPLIANCE => 'Conformité',
            self::SEASONALITY => 'Saisonnalité',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::COMPARISON => 'bi-bar-chart-line',
            self::FORECAST => 'bi-graph-up-arrow',
            self::TREASURY => 'bi-cash-stack',
            self::PROFITABILITY => 'bi-currency-dollar',
            self::CLASS_REPORT => 'bi-people',
            self::UNPAID => 'bi-exclamation-triangle',
            self::EXECUTIVE => 'bi-speedometer2',
            self::EXPENSE_CATEGORY => 'bi-pie-chart',
            self::COMPLIANCE => 'bi-shield-check',
            self::SEASONALITY => 'bi-calendar3',
        };
    }
}
