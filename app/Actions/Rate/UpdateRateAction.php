<?php

namespace App\Actions\Rate;

use App\DTOs\Configuration\RateDTO;
use App\Models\Rate;
use Illuminate\Support\Facades\DB;

class UpdateRateAction
{
    /**
     * Mettre à jour un taux
     */
    public function execute(int $id, RateDTO $dto): Rate
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Données invalides: ' . json_encode($errors));
        }

        $rate = Rate::findOrFail($id);

        return DB::transaction(function () use ($rate, $dto) {
            // Si on change le statut is_changed à false, mettre à jour les autres
            if (!$dto->isChanged && $rate->is_changed) {
                Rate::where('school_id', $dto->schoolId)
                    ->where('id', '!=', $rate->id)
                    ->where('is_changed', false)
                    ->update(['is_changed' => true]);
            }

            $rate->update($dto->toArray());

            return $rate->fresh();
        });
    }
}
