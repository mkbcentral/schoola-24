<?php

namespace App\Actions\Rate;

use App\Models\Rate;
use Illuminate\Support\Facades\DB;

class DeleteRateAction
{
    /**
     * Supprimer un taux
     */
    public function execute(int $id): bool
    {
        $rate = Rate::findOrFail($id);

        return DB::transaction(function () use ($rate) {
            // Vérifier si le taux a des inscriptions
            if ($rate->registrations()->exists()) {
                throw new \RuntimeException(
                    'Impossible de supprimer ce taux car il est utilisé par des inscriptions'
                );
            }

            // Vérifier si le taux a des paiements
            if ($rate->payments()->exists()) {
                throw new \RuntimeException(
                    'Impossible de supprimer ce taux car il est utilisé par des paiements'
                );
            }

            return $rate->delete();
        });
    }
}
