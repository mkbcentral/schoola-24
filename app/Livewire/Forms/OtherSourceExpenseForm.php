<?php

namespace App\Livewire\Forms;

use App\Models\OtherSourceExpense;
use App\Models\School;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OtherSourceExpenseForm extends Form
{
    #[Validate('required', message: 'Autre source obligatoire')]
    public  $name = '';
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
}
