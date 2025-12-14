<?php

namespace App\Actions\Rate;

use App\DTOs\Configuration\RateDTO;
use App\Models\Rate;
use Illuminate\Support\Facades\DB;

class CreateRateAction
{
    /**
     * Créer un nouveau taux
     */
    public function execute(RateDTO $dto): Rate
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Données invalides: ' . json_encode($errors));
        }

        return DB::transaction(function () use ($dto) {
            // Si c'est le taux par défaut (non changé), mettre à jour les autres
            if (!$dto->isChanged) {
                Rate::where('school_id', $dto->schoolId)
                    ->where('is_changed', false)
                    ->update(['is_changed' => true]);
            }

            return Rate::create($dto->toArray());
        });
    }
}
