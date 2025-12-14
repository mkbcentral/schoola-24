<?php

namespace App\Actions\Section;

use App\DTOs\Configuration\SectionDTO;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class CreateSectionAction
{
    /**
     * Créer une nouvelle section
     */
    public function execute(SectionDTO $dto): Section
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Données invalides: ' . json_encode($errors));
        }

        return DB::transaction(function () use ($dto) {
            return Section::create($dto->toArray());
        });
    }
}
