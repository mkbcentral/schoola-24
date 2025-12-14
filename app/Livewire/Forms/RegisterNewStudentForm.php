<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class RegisterNewStudentForm extends Form
{
    // Étape 1: Informations de l'élève
    #[Rule('required|string|max:255', message: 'Le nom complet est obligatoire')]
    public $name = '';

    #[Rule('required|in:M,F', message: 'Le genre est obligatoire')]
    public $gender = null;

    #[Rule('required|string|max:255', message: 'Le lieu de naissance est obligatoire')]
    public $placeOfBirth = '';

    #[Rule('required|date|before:today', message: 'La date de naissance est obligatoire et doit être antérieure à aujourd\'hui')]
    public $dateOfBirth = null;

    #[Rule('nullable|exists:responsible_students,id', message: 'Responsable invalide')]
    public $responsibleStudentId = null;

    // Étape 2: Informations d'inscription
    #[Rule('required|exists:class_rooms,id', message: 'La classe est obligatoire')]
    public $classRoomId = null;

    #[Rule('nullable|exists:registration_fees,id', message: 'Frais d\'inscription invalide')]
    public $registrationFeeId = null;

    #[Rule('nullable|date', message: 'Date invalide')]
    public $registrationDate = null;

    // Propriétés auxiliaires pour la cascade
    public $sectionId = null;
    public $optionId = null;
    public $currentStep = 1;

    /**
     * Valider l'étape 1 uniquement
     */
    public function validateStep1(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:M,F',
            'placeOfBirth' => 'required|string|max:255',
            'dateOfBirth' => 'required|date|before:today',
        ]);
    }

    /**
     * Valider l'étape 2 uniquement
     */
    public function validateStep2(): void
    {
        $this->validate([
            'classRoomId' => 'required|exists:class_rooms,id',
        ]);
    }

    /**
     * Réinitialiser le formulaire
     */
    public function reset(...$properties): void
    {
        $this->name = '';
        $this->gender = null;
        $this->placeOfBirth = '';
        $this->dateOfBirth = null;
        $this->responsibleStudentId = null;
        $this->classRoomId = null;
        $this->registrationFeeId = null;
        $this->registrationDate = null;
        $this->sectionId = null;
        $this->optionId = null;
        $this->currentStep = 1;

        parent::reset(...$properties);
    }

    /**
     * Obtenir les données de l'élève
     */
    public function getStudentData(): array
    {
        return [
            'name' => $this->name,
            'gender' => $this->gender,
            'place_of_birth' => $this->placeOfBirth,
            'date_of_birth' => $this->dateOfBirth,
            'responsible_student_id' => $this->responsibleStudentId,
        ];
    }

    /**
     * Obtenir les données de l'inscription
     */
    public function getRegistrationData(): array
    {
        return [
            'class_room_id' => $this->classRoomId,
            'registration_fee_id' => $this->registrationFeeId,
            'registration_date' => $this->registrationDate,
        ];
    }
}
