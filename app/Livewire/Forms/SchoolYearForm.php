<?php

namespace App\Livewire\Forms;

use App\Models\School;
use App\Models\SchoolYear;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SchoolYearForm extends Form
{
    public ?SchoolYear $schoolYear = null;

    #[Validate]
    public $name = '';

    #[Validate]
    public $start_date = '';

    #[Validate]
    public $end_date = '';

    public $is_active = false;

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
                ValidationRule::unique('school_years', 'name')->ignore($this->schoolYear),
            ],
            'start_date' => [
                'required',
                'date',
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
            ],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'name.required' => 'Le nom de l\'année scolaire est obligatoire.',
            'name.unique' => 'Cette année scolaire existe déjà.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.date' => 'Format de date invalide.',
            'end_date.required' => 'La date de fin est obligatoire.',
            'end_date.date' => 'Format de date invalide.',
            'end_date.after' => 'La date de fin doit être postérieure à la date de début.',
        ];
    }

    /**
     * Initialiser le formulaire avec une année scolaire existante
     */
    public function setSchoolYear(SchoolYear $schoolYear)
    {
        $this->schoolYear = $schoolYear;
        $this->name = $schoolYear->name;
        $this->start_date = $schoolYear->start_date?->format('Y-m-d') ?? '';
        $this->end_date = $schoolYear->end_date?->format('Y-m-d') ?? '';
        $this->is_active = $schoolYear->is_active;
    }

    /**
     * Créer une nouvelle année scolaire
     */
    public function store()
    {
        $this->validate();

        SchoolYear::create([
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
            'school_id' => School::DEFAULT_SCHOOL_ID(),
        ]);

        $this->reset();
    }

    /**
     * Mettre à jour une année scolaire existante
     */
    public function update()
    {
        $this->validate();

        $this->schoolYear->update([
            'name' => $this->name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_active' => $this->is_active,
        ]);
    }
}
