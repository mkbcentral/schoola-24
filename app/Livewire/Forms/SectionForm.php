<?php

namespace App\Livewire\Forms;

use App\Models\School;
use App\Models\Section;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SectionForm extends Form
{
    public ?Section $section = null;

    #[Validate]
    public $name = '';

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
                ValidationRule::unique('sections', 'name')->ignore($this->section),
            ],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'name.required' => 'Le nom de la section est obligatoire.',
            'name.unique' => 'Cette section existe déjà.',
        ];
    }

    /**
     * Initialiser le formulaire avec une section existante
     */
    public function setSection(Section $section)
    {
        $this->section = $section;
        $this->name = $section->name;
    }

    /**
     * Créer une nouvelle section
     */
    public function store()
    {
        $this->validate();

        Section::create([
            'name' => $this->name,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
        ]);

        $this->reset();
    }

    /**
     * Mettre à jour une section existante
     */
    public function update()
    {
        $this->validate();

        $this->section->update([
            'name' => $this->name,
        ]);
    }
}
