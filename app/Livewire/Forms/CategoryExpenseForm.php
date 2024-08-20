<?php

namespace App\Livewire\Forms;

use App\Models\CategoryExpense;
use App\Models\School;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryExpenseForm extends Form
{
    #[Validate('required', message: 'Category obligatoire')]
    public  $name = '';
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
}
