<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class RegisterExistingStudentFormValidator extends Form
{
    // Élève sélectionné
    #[Rule('required', message: 'Veuillez sélectionner un élève')]
    #[Rule('exists:students,id', message: 'Cet élève n\'existe pas')]
    public ?int $studentId = null;

    // Cascade: Section > Option > Classe
    #[Rule('nullable')]
    public ?int $sectionId = null;

    #[Rule('nullable')]
    public ?int $optionId = null;

    #[Rule('required', message: 'Veuillez sélectionner une classe')]
    #[Rule('exists:class_rooms,id', message: 'Cette classe n\'existe pas')]
    public ?int $classRoomId = null;

    #[Rule('nullable')]
    #[Rule('exists:registration_fees,id', message: 'Ces frais n\'existent pas')]
    public ?int $registrationFeeId = null;

    #[Rule('required', message: 'La date d\'inscription est requise')]
    #[Rule('date', message: 'Format de date invalide')]
    public ?string $registrationDate = null;

    /**
     * Valider le formulaire complet
     */
    public function validateRegistration(): void
    {
        $this->validate([
            'studentId' => 'required|exists:students,id',
            'classRoomId' => 'required|exists:class_rooms,id',
            'registrationDate' => 'required|date',
        ]);
    }

    /**
     * Obtenir les données pour CreateRegistrationDTO
     */
    public function getRegistrationData(): array
    {
        return [
            'student_id' => $this->studentId,
            'class_room_id' => $this->classRoomId,
            'registration_fee_id' => $this->registrationFeeId,
            'rate_id' => \App\Models\Rate::DEFAULT_RATE_ID(),
            'is_old' => true,
            'created_at' => $this->registrationDate,
        ];
    }
}
