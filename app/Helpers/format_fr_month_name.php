<?php

if (!function_exists('format_fr_month_name')) {
    /**
     * Format a month number or date to French month name
     *
     * @param mixed $month Month number (1-12) or date string
     * @return string French month name
     */
    function format_fr_month_name($month): string
    {
        $monthNames = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre',
        ];

        // Si c'est une date, extraire le mois
        if (is_string($month) && strpos($month, '-') !== false) {
            $month = (int) date('n', strtotime($month));
        }

        $month = (int) $month;

        return $monthNames[$month] ?? 'Mois inconnu';
    }
}
