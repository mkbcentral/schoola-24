<?php

namespace App\Domain\Contract\Fee;

interface ICategoryRegistrationFee
{
    /**
     * Get list category registration fee
     */
    public static function getListCategoryRegistrationFee(string $q, int $per_page = 5): mixed;
}
