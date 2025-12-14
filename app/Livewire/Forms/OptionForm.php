<?php

namespace App\Livewire\Forms;

use App\Models\Option;
use App\Models\School;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class OptionForm extends Form
{
    public ?Option $option = null;

    #[Validate]
    public $name = '';

    #[Validate]
    public $section_id = '';

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
            'section_id' => [
                'required',
                'exists:sections,id',
            ],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'name.required' => 'Le nom de l\'option est obligatoire.',
            'section_id.required' => 'La section est obligatoire.',
            'section_id.exists' => 'La section sélectionnée n\'existe pas.',
        ];
    }

    /**
     * Initialiser le formulaire avec une option existante
     */
    public function setOption(Option $option)
    {
        $this->option = $option;
        $this->name = $option->name;
        $this->section_id = $option->section_id;
    }

    /**
     * Créer une nouvelle option
     */
    public function store()
    {
        $this->validate();

        Option::create([
            'name' => $this->name,
            'section_id' => $this->section_id,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
        ]);

        $this->reset();
    }

    /**
     * Mettre à jour une option existante
     */
    public function update()
    {
        $this->validate();

        $this->option->update([
            'name' => $this->name,
            'section_id' => $this->section_id,
        ]);
    }
}
