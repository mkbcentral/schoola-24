<?php

namespace App\Livewire\Application\Registration\Form;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Domain\Helpers\RegistrationHelper;
use App\Domain\Utils\AppMessage;
use App\Models\Rate;
use App\Models\Registration;
use App\Models\RegistrationFee;
use App\Models\ResponsibleStudent;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\Student;
use Exception;
use Livewire\Component;

class OldStudentRegistrationForm extends Component
{
    public ?ResponsibleStudent $responsibleStudent = null;
    public $selectedStudent = null;
    public $selectedOption = 0;
    public $student_id = 0;
    public bool $isOldSelected = true;

    public $option_id = 0;
    public $class_room_id = 0;
    public $registration_fee_id = 0;
    public $created_at = '';
    public $lastClassRoom = '';

    //updated student_id
    public function updatedStudentId($value)
    {
        $this->selectedStudent = Student::find($value);
        if ($this->selectedStudent) {
            $this->lastClassRoom = $this->selectedStudent->getLastClassRoomName();
        }
    }

    public function updatedOptionId($val)
    {
        if ($val) {
            $this->selectedOption = $val;
            if ($val > 0) {
                $registrationFee = RegistrationFee::query()
                    ->join(
                        'category_registration_fees',
                        'registration_fees.category_registration_fee_id',
                        '=',
                        'category_registration_fees.id',
                    )
                    ->join('options', 'registration_fees.option_id', '=', 'options.id')
                    ->join('sections', 'options.section_id', '=', 'sections.id')
                    ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())
                    ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
                    ->where('options.id', $val)
                    ->where('category_registration_fees.is_old', false)
                    ->select('registration_fees.*')
                    ->first();
                $this->registration_fee_id = $registrationFee->id;
            }
        }
    }

    public function mount()
    {
        $this->created_at = date('Y-m-d');
    }

    public function addNewRegistration()
    {
        $fields = $this->validate(
            [
                'student_id' => 'required|exists:students,id',
                'option_id' => 'required|exists:options,id',
                'class_room_id' => 'required|exists:class_rooms,id',
                'registration_fee_id' => 'required|exists:registration_fees,id',
                'created_at' => 'required|date',
            ]
        );
        // Vérifier s'il existe déjà une inscription pour cet élève dans l'année scolaire courante
        $existingRegistration = Registration::where('student_id', $this->selectedStudent->id)
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->first();

        if ($existingRegistration) {
            $this->dispatch('error', ['message' => 'Cet élève est déjà inscrit pour cette année scolaire.']);
            return;
        }
        try {
            $registration = RegistrationFeature::create([
                'code' => RegistrationHelper::gerenateRegistrationCode($this->class_room_id, rand(100, 1000)),
                'registration_number' => rand(100, 1000),
                'student_id' => $this->selectedStudent->id,
                'registration_fee_id' => $this->registration_fee_id,
                'class_room_id' => $this->class_room_id,
                'school_year_id' => SchoolYear::DEFAULT_SCHOOL_YEAR_ID(),
                'rate_id' => Rate::DEFAULT_RATE_ID(),
                'is_old' => $this->isOldSelected,
                'created_at' => $this->created_at,
            ]);
            $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            $this->reset([
                'selectedStudent',
                'student_id',
                'option_id',
                'class_room_id',
                'registration_fee_id',
                'created_at',
                'selectedOption',
            ]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => AppMessage::EXECPTION_ERROR]);
        }
    }
    public function render()
    {
        return view('livewire.application.registration.form.old-student-registration-form');
    }
}
