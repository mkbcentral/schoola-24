<?php
function format_fr_month_name($key): string
{
    $month = "";
    switch ($key) {
        case '01':
            $month = "JANVIER";
            break;
        case '02':
            $month = "FEVRIER";
            break;
        case '03':
            $month = "MARS";
            break;
        case '04':
            $month = "AVRIL";
            break;
        case '05':
            $month = "MAI";
            break;
        case '06':
            $month = "JUIN";
            break;
        case '07':
            $month = "JUILLET";
            break;
        case '08':
            $month = "AOUT";
            break;
        case '09':
            $month = "SEPTEMBRE";
            break;
        case '10':
            $month = "OCTOBRE";
            break;
        case '11':
            $month = "NOVEMBRE";
            break;
        case '12':
            $month = "DECEMBRE";

        default:
            # code...
            break;
    }
    return $month;
}
