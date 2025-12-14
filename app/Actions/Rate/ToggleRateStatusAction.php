<?php

namespace App\Actions\Rate;

use App\Models\Rate;
use Illuminate\Support\Facades\DB;

class ToggleRateStatusAction
{
    /**
     * Basculer le statut is_changed d'un taux
     * Marque un taux comme taux par défaut ou modifié
     */
    public function execute(int $id): Rate
    {
        $rate = Rate::findOrFail($id);

        return DB::transaction(function () use ($rate) {
            $newStatus = !$rate->is_changed;

            // Si on marque comme taux par défaut (is_changed = false),
            // tous les autres taux de l'école doivent être marqués comme modifiés
            if (!$newStatus) {
                Rate::where('school_id', $rate->school_id)
                    ->where('id', '!=', $rate->id)
                    ->where('is_changed', false)
                    ->update(['is_changed' => true]);
            }

            $rate->update(['is_changed' => $newStatus]);

            return $rate->fresh();
        });
    }
}
