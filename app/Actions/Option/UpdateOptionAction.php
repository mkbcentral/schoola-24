<?php

namespace App\Actions\Option;

use App\DTOs\Configuration\OptionDTO;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class UpdateOptionAction
{
    /**
     * Mettre à jour une option
     */
    public function execute(int $id, OptionDTO $dto): Option
    {
        // Valider les données
        $errors = $dto->validate();
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Données invalides: ' . json_encode($errors));
        }

        $option = Option::findOrFail($id);

        return DB::transaction(function () use ($option, $dto) {
            $option->update($dto->toArray());
            return $option->fresh();
        });
    }
}
