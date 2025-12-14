<?php

namespace App\Livewire\Forms;

use App\Models\Salary;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SalaryForm extends Form
{
    #[Validate('required', message: 'Mois obligatoire')]
    public $month;

    #[Validate('required', message: 'Date de création obligatoire')]
    #[Validate('date', message: 'Format date invalide')]
    public $created_at;

    public function create(): Salary
    {
        $inputs = $this->all();
        $inputs['school_id'] = School::DEFAULT_SCHOOL_ID();
        $inputs['school_year_id'] = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        return Salary::create($inputs);
    }

    public function update(Salary $salary): bool
    {
        return $salary->update($this->all());
    }
}
