<?php

namespace App\Actions\SchoolYear;

use App\DTOs\Configuration\SchoolYearDTO;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateSchoolYearAction
{
    /**
     * Créer une nouvelle année scolaire
     */
    public function execute(SchoolYearDTO $dto): SchoolYear
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Données invalides: ' . json_encode($errors));
        }

        return DB::transaction(function () use ($dto) {
            // Si c'est une année active, désactiver les autres
            if ($dto->isActive) {
                SchoolYear::where('school_id', $dto->schoolId)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $data = $dto->toArray();

            // Ajouter l'utilisateur connecté si non spécifié
            if (!isset($data['user_id'])) {
                $data['user_id'] = Auth::id();
            }

            return SchoolYear::create($data);
        });
    }
}
