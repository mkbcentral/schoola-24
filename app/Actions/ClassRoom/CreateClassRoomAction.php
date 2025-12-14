<?php

namespace App\Actions\ClassRoom;

use App\DTOs\Configuration\ClassRoomDTO;
use App\Models\ClassRoom;
use Illuminate\Support\Facades\DB;

class CreateClassRoomAction
{
    /**
     * Créer une nouvelle classe
     */
    public function execute(ClassRoomDTO $dto): ClassRoom
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Données invalides: ' . json_encode($errors));
        }

        return DB::transaction(function () use ($dto) {
            return ClassRoom::create($dto->toArray());
        });
    }
}
