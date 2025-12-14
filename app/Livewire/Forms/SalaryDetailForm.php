<?php

namespace App\Livewire\Forms;

use App\Models\Salary;
use App\Models\SalaryDetail;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SalaryDetailForm extends Form
{
    #[Validate('required', message: 'Description obligatoire')]
    public $description;

    #[Validate('required', message: 'Devise obligatoire')]
    public $currency;

    #[Validate('required', message: 'Montant obligatoire')]
    #[Validate('numeric', message: 'Format numrique invalide')]
    public $amount;

    #[Validate('required', message: 'Categorie obligatoire')]
    #[Validate('numeric', message: 'Format numrique invalide')]
    public $category_salary_id;

    public function create(Salary $salary): SalaryDetail
    {
        $inputs = $this->all();
        $inputs['salary_id'] = $salary->id;

        return SalaryDetail::create($inputs);
    }

    public function update(SalaryDetail $salaryDetail): bool
    {
        return $salaryDetail->update($this->all());
    }
}
