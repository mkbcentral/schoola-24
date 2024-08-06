<?php

namespace App\Domain\Contract\Fee;

interface IRegistrationFee
{
    /**
     * Get list registration fee
     * @return mixed
     */
    public static function getListRegistrationFee(string $q, int $option_filter, int $per_page = 5): mixed;
}
