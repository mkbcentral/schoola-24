<?php

namespace App\Actions\Section;

use App\Models\Section;
use Illuminate\Support\Facades\DB;

class DeleteSectionAction
{
    /**
     * Supprimer une section
     */
    public function execute(int $id): bool
    {
        $section = Section::findOrFail($id);

        return DB::transaction(function () use ($section) {
            // Vérifier si la section a des options
            if ($section->options()->exists()) {
                throw new \RuntimeException(
                    'Impossible de supprimer cette section car elle contient des options'
                );
            }

            return $section->delete();
        });
    }
}
