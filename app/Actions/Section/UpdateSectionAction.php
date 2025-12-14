<?php

namespace App\Actions\Section;

use App\DTOs\Configuration\SectionDTO;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class UpdateSectionAction
{
    /**
     * Mettre à jour une section
     */
    public function execute(int $id, SectionDTO $dto): Section
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Données invalides: ' . json_encode($errors));
        }

        $section = Section::findOrFail($id);

        return DB::transaction(function () use ($section, $dto) {
            $section->update($dto->toArray());
            return $section->fresh();
        });
    }
}
