<?php

namespace App\Domain\Helpers;

use Carbon\Carbon;;

class DateFormatHelper
{
    /**
     * Ge list fr months
     * @return array
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
            ['name' => 'SEPT', 'number' => '09'],
            ['name' => 'OCT', 'number' => '10'],
            ['name' => 'NOV', 'number' => '11'],
            ['name' => 'DEC', 'number' => '12'],
            ['name' => 'JANV', 'number' => '01'],
            ['name' => 'FEV', 'number' => '02'],
            ['name' => 'MARS', 'number' => '03'],
            ['name' => 'AVRIL', 'number' => '04'],
            ['name' => 'MAI', 'number' => '05'],
            ['name' => 'JUIN', 'number' => '06'],
        ];
    }
}
