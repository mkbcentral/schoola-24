<?php

namespace App\Livewire\Application\Admin\School;

use App\Actions\School\UpdateSchoolAction;
use App\DTOs\School\UpdateSchoolDTO;
use App\Models\School;
use App\Repositories\SchoolRepository;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditSchoolPage extends Component
{
    use WithFileUploads;

    public School $school;

    public $name = '';
    public $type = '';
    public $email = '';
    public $phone = '';
    public $logo;
    public $is_active = true;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'email' => 'required|email|unique:schools,email,' . $this->school->id,
            'phone' => 'required|string',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ];
    }

    public function mount(int $schoolId)
    {
        $repository = app(SchoolRepository::class);
        $this->school = $repository->findById($schoolId);

        if (!$this->school) {
            abort(404, 'École introuvable');
        }

        $this->authorize('update', $this->school);

        // Remplir les champs
        $this->name = $this->school->name;
        $this->type = $this->school->type;
        $this->email = $this->school->email;
        $this->phone = $this->school->phone;
        $this->is_active = $this->school->app_status === 'active';
    }

    public function update()
    {
        $this->authorize('update', $this->school);

        $this->validate();

        try {
            // Traiter le logo si présent
            $logoPath = $this->school->logo;
            if ($this->logo) {
                $logoPath = $this->logo->store('schools/logos', 'public');
            }

            // Créer le DTO
            $dto = UpdateSchoolDTO::fromArray([
                'id' => $this->school->id,
                'name' => $this->name,
                'type' => $this->type,
                'email' => $this->email,
                'phone' => $this->phone,
                'logo' => $logoPath,
                'is_active' => $this->is_active,
            ]);

            // Exécuter l'action
            $action = app(UpdateSchoolAction::class);
            $action->execute($dto);

            $this->dispatch('added', ['message' => 'École mise à jour avec succès.']);

            return redirect()->route('admin.schools.index');

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
        return view('livewire.application.admin.school.edit-school-page');
    }
}
