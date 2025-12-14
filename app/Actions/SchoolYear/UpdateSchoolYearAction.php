<?php

namespace App\Actions\SchoolYear;

use App\DTOs\Configuration\SchoolYearDTO;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;

class UpdateSchoolYearAction
{
    /**
     * Mettre à jour une année scolaire
     */
    public function execute(int $id, SchoolYearDTO $dto): SchoolYear
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Données invalides: ' . json_encode($errors));
        }

        $schoolYear = SchoolYear::findOrFail($id);

        return DB::transaction(function () use ($schoolYear, $dto) {
            // Si on active cette année, désactiver les autres
            if ($dto->isActive && !$schoolYear->is_active) {
                SchoolYear::where('school_id', $dto->schoolId)
                    ->where('id', '!=', $schoolYear->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $schoolYear->update($dto->toArray());

            return $schoolYear->fresh();
        });
    }
}
