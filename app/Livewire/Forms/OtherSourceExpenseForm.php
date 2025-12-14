<?php

namespace App\Livewire\Forms;

use App\Models\OtherSourceExpense;
use App\Models\School;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OtherSourceExpenseForm extends Form
{
    public ?int $id = null;

    #[Validate('required|string|min:3|max:255', message: 'Le nom est obligatoire (3-255 caractères)')]
    public string $name = '';

    public function create(): OtherSourceExpense
    {
        $inputs = $this->all();
        $inputs['school_id'] = School::DEFAULT_SCHOOL_ID();

        return OtherSourceExpense::create($inputs);
    }

    public function update(OtherSourceExpense $otherSourceExpense): bool
    {
        return $otherSourceExpense->update($this->all());
    }

    /**
     * Convert form to array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    /**
     * Reset form
     */
    public function reset(...$properties): void
    {
        $this->id = null;
        $this->name = '';
    }
}
