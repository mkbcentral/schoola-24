<?php

namespace App\Actions\Option;

use App\DTOs\Configuration\OptionDTO;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class CreateOptionAction
{
    /**
     * Créer une nouvelle option
     */
    public function execute(OptionDTO $dto): Option
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Données invalides: ' . json_encode($errors));
        }

        return DB::transaction(function () use ($dto) {
            return Option::create($dto->toArray());
        });
    }
}
