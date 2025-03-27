<?php
function app_format_number(int|float|null $value, $interval): string
{
    return number_format($value ?? 0, $interval, ',', ' ');
}
