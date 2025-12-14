<?php

namespace App\Livewire\Application\Admin\School;

use App\Actions\School\CreateSchoolAction;
use App\DTOs\School\CreateSchoolDTO;
use App\Models\Role;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateSchoolPage extends Component
{
    use WithFileUploads;

    // Données de l'école
    public $name = '';
    public $type = '';
    public $email = '';
    public $phone = '';
    public $logo;

    // Données de l'administrateur
    public $admin_name = '';
    public $admin_username = '';
    public $admin_email = '';
    public $admin_phone = '';

    public $tempPassword = null;
    public $showSuccessMessage = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|string',
        'email' => 'required|email|unique:schools,email',
        'phone' => 'required|string',
        'logo' => 'nullable|image|max:2048',
        'admin_name' => 'required|string|max:255',
        'admin_username' => 'required|string|unique:users,username',
        'admin_email' => 'required|email|unique:users,email',
        'admin_phone' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'Le nom de l\'école est requis.',
        'email.required' => 'L\'email de l\'école est requis.',
        'email.email' => 'L\'email de l\'école doit être valide.',
        'email.unique' => 'Cet email est déjà utilisé par une autre école.',
        'admin_name.required' => 'Le nom de l\'administrateur est requis.',
        'admin_username.required' => 'Le nom d\'utilisateur est requis.',
        'admin_username.unique' => 'Ce nom d\'utilisateur est déjà utilisé.',
        'admin_email.required' => 'L\'email de l\'administrateur est requis.',
        'admin_email.email' => 'L\'email de l\'administrateur doit être valide.',
        'admin_email.unique' => 'Cet email est déjà utilisé.',
    ];

    public function save()
    {
        $this->authorize('create', \App\Models\School::class);

        $this->validate();

        try {
            // Traiter le logo si présent
            $logoPath = null;
            if ($this->logo) {
                $logoPath = $this->logo->store('schools/logos', 'public');
            }

            // Créer le DTO
            $dto = CreateSchoolDTO::fromArray([
                'name' => $this->name,
                'type' => $this->type,
                'email' => $this->email,
                'phone' => $this->phone,
                'logo' => $logoPath,
                'admin_name' => $this->admin_name,
                'admin_username' => $this->admin_username,
                'admin_email' => $this->admin_email,
                'admin_phone' => $this->admin_phone,
            ]);

            // Exécuter l'action
            $action = app(CreateSchoolAction::class);
            $result = $action->execute($dto);

            $this->tempPassword = $result['temp_password'];
            $this->showSuccessMessage = true;

            $this->dispatch('added', [
                'message' => 'École créée avec succès. Mot de passe temporaire : ' . $this->tempPassword
            ]);

            // Redirection après 3 secondes
            $this->dispatch('redirect-after-success');

        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function cancel()
    {
        return redirect()->route('admin.schools.index');
    }

    public function render()
    {
        return view('livewire.application.admin.school.create-school-page');
    }
}
