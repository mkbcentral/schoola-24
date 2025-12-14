<?php

namespace App\Actions\ClassRoom;

use App\DTOs\Configuration\ClassRoomDTO;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\DB;

class UpdateClassRoomAction
{
    /**
     * Mettre à jour une classe
     */
    public function execute(int $id, ClassRoomDTO $dto): ClassRoom
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Données invalides: ' . json_encode($errors));
        }

        $classRoom = ClassRoom::findOrFail($id);

        return DB::transaction(function () use ($classRoom, $dto) {
            $classRoom->update($dto->toArray());
            return $classRoom->fresh();
        });
    }
}
