<?php

namespace App\Livewire\Forms;

use App\Enums\SchoolAppEnum;
use App\Enums\SchoolEnum;
use App\Models\School;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SchoolForm extends Form
{
    public ?School $school = null;

    #[Validate]
    public $name = '';

    #[Validate]
    public $type = '';

    #[Validate]
    public $phone = '';

    #[Validate]
    public $email = '';

    #[Validate]
    public $address = '';

    #[Validate]
    public $app_status = '';

    #[Validate]
    public $school_status = '';

    /**
     * Règles de validation dynamiques
     */
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                ValidationRule::unique('schools', 'name')->ignore($this->school),
            ],
            'type' => [
                'required',
                'string',
                'max:100',
            ],
            'phone' => [
                'required',
                'string',
                'min:9',
                'max:20',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                ValidationRule::unique('schools', 'email')->ignore($this->school),
            ],
            'address' => [
                'nullable',
                'string',
                'max:500',
            ],
            'app_status' => [
                'required',
                ValidationRule::in(SchoolAppEnum::getValues()),
            ],
            'school_status' => [
                'required',
                ValidationRule::in(SchoolEnum::getValues()),
            ],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'name.required' => 'Le nom de l\'école est obligatoire.',
            'name.unique' => 'Cette école existe déjà.',
            'type.required' => 'Le type d\'école est obligatoire.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.min' => 'Le numéro doit contenir au moins 9 caractères.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'app_status.required' => 'Le statut de l\'application est obligatoire.',
            'app_status.in' => 'Le statut de l\'application sélectionné n\'est pas valide.',
            'school_status.required' => 'Le statut de l\'école est obligatoire.',
            'school_status.in' => 'Le statut de l\'école sélectionné n\'est pas valide.',
        ];
    }

    /**
     * Initialiser le formulaire avec une école existante
     */
    public function setSchool(School $school)
    {
        $this->school = $school;
        $this->name = $school->name;
        $this->type = $school->type;
        $this->phone = $school->phone;
        $this->email = $school->email;
        $this->address = $school->address ?? '';
        $this->app_status = $school->app_status;
        $this->school_status = $school->school_status;
    }

    /**
     * Créer une nouvelle école
     */
    public function store()
    {
        $this->validate();

        School::create([
            'name' => $this->name,
            'type' => $this->type,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'app_status' => $this->app_status,
            'school_status' => $this->school_status,
        ]);

        $this->reset();
    }

    /**
     * Mettre à jour une école existante
     */
    public function update()
    {
        $this->validate();

        $this->school->update([
            'name' => $this->name,
            'type' => $this->type,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'app_status' => $this->app_status,
            'school_status' => $this->school_status,
        ]);
    }
}
