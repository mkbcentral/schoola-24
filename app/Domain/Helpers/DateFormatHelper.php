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


    public static function getScoolFrMonths(): array
    {
        return [
            //['name' => 'AOUT', 'number' => '08'],
            ['name' => 'SEPTEMBRE', 'number' => '09'],
            ['name' => 'OCTOBRE', 'number' => '10'],
            ['name' => 'NOVEMBRE', 'number' => '11'],
            ['name' => 'DECEMBRE', 'number' => '12'],
            ['name' => 'JANVIER', 'number' => '01'],
            ['name' => 'FEVRIER', 'number' => '02'],
            ['name' => 'MARS', 'number' => '03'],
            ['name' => 'AVRIL', 'number' => '04'],
            ['name' => 'MAI', 'number' => '05'],
            ['name' => 'JUIN', 'number' => '06'],
        ];
    }

    /**
     * Get list date for month
     * @param mixed $month
     * @param mixed $year
     * @return array
     */
    public static function getListDateForMonth($month, $year): array
    {
        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates = array();
        for ($i = 1; $i <= $days; $i++) {
            $date = Carbon::createFromDate($year, $month, $i)->format('Y-m-d');
            $dates[] = $date;
        }
        return $dates;
    }
}
