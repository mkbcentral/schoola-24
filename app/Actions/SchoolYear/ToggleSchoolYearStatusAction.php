<?php

namespace App\Actions\SchoolYear;

use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;

class ToggleSchoolYearStatusAction
{
    /**
     * Basculer le statut actif d'une année scolaire
     */
    public function execute(int $id): SchoolYear
    {
        $schoolYear = SchoolYear::findOrFail($id);

        return DB::transaction(function () use ($schoolYear) {
            $newStatus = !$schoolYear->is_active;

            // Si on active cette année, désactiver les autres
            if ($newStatus) {
                SchoolYear::where('school_id', $schoolYear->school_id)
                    ->where('id', '!=', $schoolYear->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $schoolYear->update(['is_active' => $newStatus]);

            return $schoolYear->fresh();
        });
    }
}
