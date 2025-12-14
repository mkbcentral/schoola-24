<?php

namespace App\Livewire\Forms;

use App\Models\CategorySalary;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Attributes\Rule;
use Livewire\Form;

class CategorySalaryForm extends Form
{
    #[Rule('required', message: 'Nom role obligation', onUpdate: false)]
    public $name = '';

    public function create(array $input)
    {
        $input['school_id'] = School::DEFAULT_SCHOOL_ID();
        $input['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        CategorySalary::create($input);
    }

    public function update(CategorySalary $categorySalary, array $input)
    {
        $categorySalary->update($input);
    }
}
