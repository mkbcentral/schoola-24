<?php

if (!function_exists('format_number')) {
    /**
     * Format a number with thousands separators
     *
     * @param float|int|null $number
     * @param int $decimals
     * @param string $decPoint
     * @param string $thousandsSep
     * @return string
     */
    function format_number($number, int $decimals = 2, string $decPoint = ',', string $thousandsSep = ' '): string
    {
        if ($number === null) {
            return '0';
        }

        return number_format((float) $number, $decimals, $decPoint, $thousandsSep);
    }
}

if (!function_exists('app_format_number')) {
    /**
     * Format a number with thousands separators (alias for format_number)
     *
     * @param float|int|null $number
     * @param int $decimals
     * @param string $decPoint
     * @param string $thousandsSep
     * @return string
     */
    function app_format_number($number, int $decimals = 2, string $decPoint = ',', string $thousandsSep = ' '): string
    {
        if ($number === null) {
            return '0';
        }

        return number_format((float) $number, $decimals, $decPoint, $thousandsSep);
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format a number as currency
     *
     * @param float|int|null $amount
     * @param string $currency
     * @return string
     */
    function format_currency($amount, string $currency = 'USD'): string
    {
        $formatted = format_number($amount);

        return $formatted . ' ' . $currency;
    }
}
