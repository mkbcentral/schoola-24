<?php

namespace App\Domain\Helpers;

class DateFormatHelper
{
    /**
     * Ge list fr months
     */
    public static function getFrMonths(): array
    {
        return [
            ['name' => 'JANVIER', 'number' => '01'],
            ['name' => 'FEVRIER', 'number' => '02'],
            ['name' => 'MARS', 'number' => '03'],
            ['name' => 'AVRIL', 'number' => '04'],
            ['name' => 'MAI', 'number' => '05'],
            ['name' => 'JUIN', 'number' => '06'],
            ['name' => 'JUILLET', 'number' => '07'],
            ['name' => 'AOUT', 'number' => '08'],
            ['name' => 'SEPTEMBRE', 'number' => '09'],
            ['name' => 'OCTOBRE', 'number' => '10'],
            ['name' => 'NOVEMBRE', 'number' => '11'],
            ['name' => 'DECEMBRE', 'number' => '12'],
        ];
    }

    public static function getSchoolFrMonths(): array
    {
        return [
            ['name' => 'JUILLET', 'number' => '07'],
            ['name' => 'AOUT', 'number' => '08'],
            ['name' => 'SEPTEMBRE', 'number' => '09'],
            ['name' => 'OCTOBRE', 'number' => '10'],
            ['name' => 'NOVEMBRE', 'number' => '11'],
            ['name' => 'DECENTRE', 'number' => '12'],
            ['name' => 'JANVIER', 'number' => '01'],
            ['name' => 'FEVRIER', 'number' => '02'],
            ['name' => 'MARS', 'number' => '03'],
            ['name' => 'AVRIL', 'number' => '04'],
            ['name' => 'MAI', 'number' => '05'],
            ['name' => 'JUIN', 'number' => '06'],
        ];
    }

    public static function getMonthLabelFromNumber($monthNumber): string
    {
        $months = [
            '09' => 'SEPTEMBRE',
            '10' => 'OCTOBRE',
            '11' => 'NOVEMBRE',
            '12' => 'DECEMBRE',
            '01' => 'JANVIER',
            '02' => 'FEVRIER',
            '03' => 'MARS',
            '04' => 'AVRIL',
            '05' => 'MAI',
            '06' => 'JUIN',
        ];
        $monthNumber = str_pad($monthNumber, 2, '0', STR_PAD_LEFT);

        return $months[$monthNumber] ?? $monthNumber;
    }

    public static function getMonthsNumber(): array
    {
        return [
            'SEPTEMBRE' => 9,
            'OCTOBRE' => 10,
            'NOVEMBRE' => 11,
            'DECEMBRE' => 12,
            'JANVIER' => 1,
            'FEVRIER' => 2,
            'MARS' => 3,
            'AVRIL' => 4,
            'MAI' => 5,
            'JUIN' => 6,
        ];
    }
}
