<?php
function app_format_number(int|float $value, $interval): string
{
    return number_format($value, $interval, ',', ' ');
}
