<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class ChangeClassForm extends Form
{
    public $registrationId = null;
    public $currentClassRoomId = null;

    #[Rule('required|exists:class_rooms,id', message: 'La nouvelle classe est obligatoire')]
    public $newClassRoomId = null;

    // Propriétés auxiliaires pour la cascade
    public $sectionId = null;
    public $optionId = null;

    /**
     * Valider que la nouvelle classe est différente
     */
    public function validateDifferentClass(): void
    {
        $this->validate();

        if ($this->newClassRoomId == $this->currentClassRoomId) {
            throw new \Exception('La nouvelle classe doit être différente de la classe actuelle.');
        }
    }

    /**
     * Définir l'inscription pour le changement
     */
    public function setRegistration($registrationId, $currentClassRoomId): void
    {
        $this->registrationId = $registrationId;
        $this->currentClassRoomId = $currentClassRoomId;
    }

    /**
     * Réinitialiser le formulaire
     */
    public function reset(...$properties): void
    {
        $this->registrationId = null;
        $this->currentClassRoomId = null;
        $this->newClassRoomId = null;
        $this->sectionId = null;
        $this->optionId = null;

        parent::reset(...$properties);
    }
}
