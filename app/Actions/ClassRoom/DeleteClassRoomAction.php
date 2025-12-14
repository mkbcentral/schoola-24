<?php

namespace App\Actions\ClassRoom;

use App\Models\ClassRoom;
use Illuminate\Support\Facades\DB;

class DeleteClassRoomAction
{
    /**
     * Supprimer une classe
     */
    public function execute(int $id): bool
    {
        $classRoom = ClassRoom::findOrFail($id);

        return DB::transaction(function () use ($classRoom) {
            // Vérifier si la classe a des inscriptions
            if ($classRoom->registrations()->exists()) {
                throw new \RuntimeException(
                    'Impossible de supprimer cette classe car elle contient des inscriptions'
                );
            }

            return $classRoom->delete();
        });
    }
}
