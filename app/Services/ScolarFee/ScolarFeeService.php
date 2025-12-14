<?php

namespace App\Services\ScolarFee;

use App\Models\ScolarFee;

class ScolarFeeService
{
    /**
     * Récupérer un frais scolaire par catégorie et classe
     *
     * @param int $categoryFeeId
     * @param int $classRoomId
     * @return ScolarFee|null
     */
    public function getScolarFee(int $categoryFeeId, int $classRoomId): ?ScolarFee
    {
        return ScolarFee::query()
            ->where('category_fee_id', $categoryFeeId)
            ->where('class_room_id', $classRoomId)
            ->first();
    }
}
