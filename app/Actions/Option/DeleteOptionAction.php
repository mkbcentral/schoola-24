<?php

namespace App\Actions\Option;

use App\Models\Option;
use Illuminate\Support\Facades\DB;

class DeleteOptionAction
{
    /**
     * Supprimer une option
     */
    public function execute(int $id): bool
    {
        $option = Option::findOrFail($id);

        return DB::transaction(function () use ($option) {
            // Vérifier si l'option a des classes
            if ($option->classRooms()->exists()) {
                throw new \RuntimeException(
                    'Impossible de supprimer cette option car elle contient des classes'
                );
            }

            return $option->delete();
        });
    }
}
