<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class RegisterExistingStudentForm extends Form
{
    #[Rule('required|exists:students,id', message: "L'élève est obligatoire")]
    public $studentId = null;

    #[Rule('required|exists:class_rooms,id', message: 'La classe est obligatoire')]
    public $classRoomId = null;

    #[Rule('nullable|exists:registration_fees,id', message: 'Frais d\'inscription invalide')]
    public $registrationFeeId = null;

    #[Rule('nullable|date', message: 'Date invalide')]
    public $registrationDate = null;

    public $sectionId = null;
    public $optionId = null;

    /**
     * Réinitialiser le formulaire
     */
    public function reset(...$properties): void
    {
        $this->studentId = null;
        $this->classRoomId = null;
        $this->registrationFeeId = null;
        $this->registrationDate = null;
        $this->sectionId = null;
        $this->optionId = null;

        parent::reset(...$properties);
    }

    /**
     * Obtenir les données pour la création
     */
    public function toArray(): array
    {
        return [
            'student_id' => $this->studentId,
            'class_room_id' => $this->classRoomId,
            'registration_fee_id' => $this->registrationFeeId,
            'registration_date' => $this->registrationDate,
        ];
    }
}
