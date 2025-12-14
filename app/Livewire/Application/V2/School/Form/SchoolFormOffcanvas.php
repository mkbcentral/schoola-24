<?php

namespace App\Livewire\Application\V2\School\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SchoolForm;
use App\Models\School;
use App\Services\SchoolManagementService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class SchoolFormOffcanvas extends Component
{
    use WithFileUploads;

    public SchoolForm $form;
    public ?School $school = null;
    public bool $isOpen = false;
    public $logo;
    public $currentLogo = null;

    // Service injecté via boot
    protected SchoolManagementService $schoolManagementService;

    /**
     * Injection de dépendances via boot
     */
    public function boot(SchoolManagementService $schoolManagementService): void
    {
        $this->schoolManagementService = $schoolManagementService;
    }

    #[On('open-create-school')]
    public function openCreate()
    {
        $this->reset(['school']);
        $this->form->reset();
        $this->isOpen = true;
        $this->dispatch('show-school-offcanvas');
    }

    #[On('edit-school')]
    public function openEdit(int $id)
    {
        $this->school = School::find($id);

        if ($this->school) {
            $this->form->setSchool($this->school);
            $this->currentLogo = $this->school->logo;
            $this->isOpen = true;
            $this->dispatch('show-school-offcanvas');
        } else {
            $this->dispatch('error-message', message: 'École introuvable');
        }
    }

    #[On('edit-school-logo')]
    public function openLogoEdit(int $id)
    {
        $this->school = School::find($id);

        if ($this->school) {
            $this->currentLogo = $this->school->logo;
            $this->dispatch('show-logo-modal');
        } else {
            $this->dispatch('error-message', message: 'École introuvable');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->school) {
                // Update existing school
                $this->form->update();
                $message = AppMessage::DATA_UPDATED_SUCCESS;
            } else {
                // Create new school
                $this->form->store();
                $message = AppMessage::DATA_SAVED_SUCCESS;
            }

            $this->dispatch('school-saved');
            $this->dispatch('hide-school-offcanvas');
            $this->closeOffcanvas();
            $this->dispatch('success-message', message: $message);
        } catch (\Exception $ex) {
            $this->dispatch('error-message', message: $ex->getMessage());
        }
    }

    public function updatedLogo()
    {
        $this->validate([
            'logo' => 'image|max:2048', // 2MB Max
        ], [
            'logo.image' => 'Le fichier doit être une image.',
            'logo.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);
    }

    public function saveLogo()
    {
        if (!$this->logo) {
            $this->dispatch('error-message', message: 'Veuillez sélectionner une image');
            return;
        }

        try {
            // Delete old logo if exists
            if ($this->school && $this->school->logo) {
                Storage::disk('public')->delete($this->school->logo);
            }

            // Store new logo
            $logoPath = $this->logo->store('schools/logos', 'public');

            // Update school logo
            if ($this->school) {
                $this->school->update(['logo' => $logoPath]);
                $this->currentLogo = $logoPath;
                $this->dispatch('success-message', message: 'Logo mis à jour avec succès');
                $this->dispatch('school-saved');
            }

            $this->reset('logo');
            $this->dispatch('hide-logo-modal');
        } catch (\Exception $ex) {
            $this->dispatch('error-message', message: 'Erreur lors de la mise à jour: ' . $ex->getMessage());
        }
    }

    public function removeLogo()
    {
        try {
            if ($this->school && $this->school->logo) {
                Storage::disk('public')->delete($this->school->logo);
                $this->school->update(['logo' => null]);
                $this->currentLogo = null;
                $this->dispatch('success-message', message: 'Logo supprimé avec succès');
                $this->dispatch('school-saved');
            }
        } catch (\Exception $ex) {
            $this->dispatch('error-message', message: 'Erreur lors de la suppression: ' . $ex->getMessage());
        }
    }

    public function closeOffcanvas()
    {
        $this->isOpen = false;
        $this->reset(['school', 'logo', 'currentLogo']);
        $this->form->reset();
        $this->dispatch('hide-school-offcanvas');
    }

    public function render()
    {
        return view('livewire.application.v2.school.form.school-form-offcanvas');
    }
}
