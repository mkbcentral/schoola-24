<?php

namespace App\Livewire\Forms;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Domain\Features\Student\StudentFeature;
use App\Domain\Helpers\RegistrationHelper;
use App\Events\RegistrationCreatedEvent;
use App\Models\Rate;
use App\Models\Registration;
use App\Models\ResponsibleStudent;
use App\Models\SchoolYear;
use App\Models\Student;
use Livewire\Attributes\Rule;
use Livewire\Form;

class RegistrationForm extends Form
{
    #[Rule('required', message: "Nom de l'lève obligation", onUpdate: false)]
    public $name = '';
    #[Rule('required', message: "Date de naissance obligatoire", onUpdate: false)]
    #[Rule('date', message: "Champs date invalide", onUpdate: false)]
    public $date_of_birth = '';
    #[Rule('required', message: "Lieu de naissance obligatoire", onUpdate: false)]
    public $place_of_birth = '';
    #[Rule('required', message: "Categorie frais obligatoire", onUpdate: false)]
    public $registration_fee_id = '';
    #[Rule('required', message: "Classe obligatoire", onUpdate: false)]
    public $class_room_id = '';
    #[Rule('required', message: "Option obligatoire", onUpdate: false)]
    public $option_id = '';
    #[Rule('required', message: "Selectionner le type SVP !", onUpdate: false)]
    public $is_old = false;

    public function create(ResponsibleStudent $responsibleStudent, $gender): Registration
    {
        $registration = null;
        $student =  StudentFeature::create([
            'responsible_student_id' => $responsibleStudent->id,
            'name' => $this->name,
            'gender' => $gender,
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth,
        ]);
        if ($student) {
            $registration = RegistrationFeature::create([
                'code' => RegistrationHelper::gerenateRegistrationCode($this->class_room_id, rand(100, 1000)),
                'registration_number' => rand(100, 1000),
                'student_id' => $student->id,
                'registration_fee_id' => $this->registration_fee_id,
                'class_room_id' => $this->class_room_id,
                'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
                'rate_id' => Rate::DEFAULT_RATE_ID(),
                'is_old' => $this->is_old,
            ]);
        }
        return $registration;
    }
    public function update(Student $student, $gender)
    {
        $student_inout = [
            'name' => $this->name,
            'gender' => $gender,
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth
        ];
        StudentFeature::update($student, $student_inout);
        $input_registration = [
            'registration_fee_id' => $this->registration_fee_id,
            'class_room_id' => $this->class_room_id,
            'is_old' => $this->is_old,
        ];
        RegistrationFeature::update($student->registration, $input_registration);
    }
}
