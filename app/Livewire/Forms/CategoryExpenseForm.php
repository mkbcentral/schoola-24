<?php

namespace App\Livewire\Forms;

use App\Models\CategoryExpense;
use App\Models\School;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryExpenseForm extends Form
{
    public ?int $id = null;

    #[Validate('required|string|min:3|max:255', message: 'Le nom est obligatoire (3-255 caractères)')]
    public string $name = '';

    public function create(): CategoryExpense
    {
        $inputs = $this->all();
        $inputs['school_id'] = School::DEFAULT_SCHOOL_ID();

        return CategoryExpense::create($inputs);
    }

    public function update(CategoryExpense $categoryExpense): bool
    {
        return $categoryExpense->update($this->all());
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
