<?php

namespace App\Actions\SchoolYear;

use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;

class DeleteSchoolYearAction
{
    /**
     * Supprimer une année scolaire
     */
    public function execute(int $id): bool
    {
        $schoolYear = SchoolYear::findOrFail($id);

        return DB::transaction(function () use ($schoolYear) {
            // Vérifier si l'année a des inscriptions
            if ($schoolYear->registrations()->exists()) {
                throw new \RuntimeException(
                    'Impossible de supprimer cette année scolaire car elle contient des inscriptions'
                );
            }

            return $schoolYear->delete();
        });
    }
}
