<?php

namespace App\Livewire\Forms;

use App\Domain\Features\Student\ResponsibleStudentFeature;
use App\Models\ResponsibleStudent;
use App\Models\School;
use Livewire\Attributes\Rule;
use Livewire\Form;

class ResponsibleStudentForm extends Form
{

    #[Rule('required', message: 'Nom responsable obligatoire', onUpdate: false)]
    public $name = '';

    #[Rule('required', message: 'N° Tél obligation', onUpdate: false)]
    #[Rule('min:9', message: 'Minimum 10 caractères', onUpdate: false)]
    public $phone = '';

    #[Rule('nullable',  onUpdate: false)]
    #[Rule('min:9', message: 'Minimum 10 caractères', onUpdate: false)]
    public $other_phone = '';

    #[Rule('nullable', onUpdate: false)]
    #[Rule('min:6', message: 'Minimum 6 caractères', onUpdate: false)]
    #[Rule('email', message: 'Format email invalide', onUpdate: false)]
    public $email = '';

    public function create(array $input = [])
    {
        $input['school_id'] = School::DEFAULT_SCHOOL_ID();
        ResponsibleStudentFeature::create($input);
    }

    public function update(ResponsibleStudent $responsibleStudent, array $input = [])
    {
        ResponsibleStudentFeature::update($responsibleStudent, $input);
    }
}
