<?php

namespace App\Livewire\Forms;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Domain\Features\Student\StudentFeature;
use App\Domain\Helpers\RegistrationHelper;
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

    #[Rule('required', message: 'Date de naissance obligatoire', onUpdate: false)]
    #[Rule('date', message: 'Champs date invalide', onUpdate: false)]
    public $date_of_birth = '';

    #[Rule('required', message: 'Lieu de naissance obligatoire', onUpdate: false)]
    public $place_of_birth = '';

    #[Rule('required', message: 'Categorie frais obligatoire', onUpdate: false)]
    public $registration_fee_id = 0;

    #[Rule('required', message: 'Classe obligatoire', onUpdate: false)]
    public $class_room_id = 0;

    #[Rule('required', message: 'Option obligatoire', onUpdate: false)]
    public $is_old = false;

    #[Rule('required', message: 'Date de creation !', onUpdate: false)]
    #[Rule('date', message: 'Format date invalide', onUpdate: false)]
    public $created_at = '';

    public function create(ResponsibleStudent $responsibleStudent, $gender): ?Registration
    {
        // Vérifier si l'élève existe déjà pour ce responsable, ce nom et cette date de naissance
        $student = Student::firstWhere([
            'responsible_student_id' => $responsibleStudent->id,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
        ]);

        if ($student) {
            return null;
        }

        // Création de l'élève
        $student = StudentFeature::create([
            'responsible_student_id' => $responsibleStudent->id,
            'name' => $this->name,
            'gender' => $gender,
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth,
        ]);

        // Création de l'inscription
        $registration = RegistrationFeature::create([
            'code' => RegistrationHelper::gerenateRegistrationCode($this->class_room_id, rand(100, 1000)),
            'registration_number' => rand(100, 1000),
            'student_id' => $student->id,
            'registration_fee_id' => $this->registration_fee_id,
            'class_room_id' => $this->class_room_id,
            'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
            'rate_id' => Rate::DEFAULT_RATE_ID(),
            'is_old' => $this->is_old,
            'created_at' => $this->created_at,
        ]);
        // Génération et mise à jour du QR code
        $registration->update([
            'qr_code' => StudentFeature::generateStudentQRCode($registration),
        ]);

        return $registration;
    }

    public function update(Student $student, $gender, Registration $registration)
    {
        $student_inout = [
            'name' => $this->name,
            'gender' => $gender,
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth,
        ];
        // Met à jour les infos de l'élève
        $student->update($student_inout);
        // Met à jour l'inscription
        $registration->update([
            'class_room_id' => $this->class_room_id,
            'created_at' => $this->created_at,
        ]);
    }
}
