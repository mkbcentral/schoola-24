<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user = null;

    #[Validate]
    public $name = '';

    #[Validate]
    public $username = '';

    #[Validate]
    public $phone = '';

    #[Validate]
    public $role_id = '';

    #[Validate]
    public $email = '';

    public $is_active = true;

    /**
     * Règles de validation dynamiques
     */
    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:100',
                ValidationRule::unique('users', 'username')->ignore($this->user),
            ],
            'phone' => [
                'required',
                'string',
                'min:9',
                'max:20',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                ValidationRule::unique('users', 'email')->ignore($this->user),
            ],
            'role_id' => [
                'required',
                'exists:roles,id',
            ],
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    protected function messages()
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 3 caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            
            'username.required' => 'Le pseudo est obligatoire.',
            'username.min' => 'Le pseudo doit contenir au moins 3 caractères.',
            'username.unique' => 'Ce pseudo est déjà utilisé.',
            
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.min' => 'Le numéro doit contenir au moins 9 caractères.',
            
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            
            'role_id.required' => 'Le rôle est obligatoire.',
            'role_id.exists' => 'Le rôle sélectionné n\'existe pas.',
        ];
    }

    /**
     * Initialiser le formulaire avec un utilisateur existant
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->is_active = $user->is_active;
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function store()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'username' => $this->username,
            'phone' => $this->phone,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'is_active' => $this->is_active,
            'password' => bcrypt('password'), // Mot de passe par défaut
        ]);

        $this->reset();
    }

    /**
     * Mettre à jour un utilisateur existant
     */
    public function update()
    {
        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'username' => $this->username,
            'phone' => $this->phone,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'is_active' => $this->is_active,
        ]);
    }
}
