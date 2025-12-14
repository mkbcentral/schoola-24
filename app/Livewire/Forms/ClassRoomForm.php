<?php

namespace App\Livewire\Forms;

use App\Models\ClassRoom;
use App\Models\School;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ClassRoomForm extends Form
{
    public ?ClassRoom $classRoom = null;

    #[Validate]
    public $name = '';

    #[Validate]
    public $option_id = '';

    /**
     * Règles de validation
     */
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'option_id' => [
                'required',
                'exists:options,id',
            ],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'name.required' => 'Le nom de la classe est obligatoire.',
            'option_id.required' => 'L\'option est obligatoire.',
            'option_id.exists' => 'L\'option sélectionnée n\'existe pas.',
        ];
    }

    /**
     * Initialiser le formulaire avec une classe existante
     */
    public function setClassRoom(ClassRoom $classRoom)
    {
        $this->classRoom = $classRoom;
        $this->name = $classRoom->name;
        $this->option_id = $classRoom->option_id;
    }

    /**
     * Créer une nouvelle classe
     */
    public function store()
    {
        $this->validate();

        ClassRoom::create([
            'name' => $this->name,
            'option_id' => $this->option_id,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
        ]);

        $this->reset();
    }

    /**
     * Mettre à jour une classe existante
     */
    public function update()
    {
        $this->validate();

        $this->classRoom->update([
            'name' => $this->name,
            'option_id' => $this->option_id,
        ]);
    }
}
