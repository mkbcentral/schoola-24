<?php

namespace App\Livewire\Application\V2\Configuration\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SchoolYearForm;
use App\Models\SchoolYear;
use App\Services\Configuration\SchoolYearService;
use Livewire\Attributes\On;
use Livewire\Component;

class SchoolYearFormOffcanvas extends Component
{
    public SchoolYearForm $form;
    public ?SchoolYear $schoolYear = null;
    public bool $isOpen = false;

    // Service injecté via boot
    protected SchoolYearService $schoolYearService;

    /**
     * Injection de dépendances via boot
     */
    public function boot(SchoolYearService $schoolYearService): void
    {
        $this->schoolYearService = $schoolYearService;
    }

    #[On('openSchoolYearModal')]
    public function openCreate(): void
    {
        $this->reset(['schoolYear']);
        $this->form->reset();
        $this->isOpen = true;
        $this->dispatch('show-school-year-offcanvas');
    }

    #[On('openSchoolYearEditModal')]
    public function openEdit(int $id): void
    {
        $this->schoolYear = SchoolYear::find($id);

        if ($this->schoolYear) {
            $this->form->setSchoolYear($this->schoolYear);
            $this->isOpen = true;
            $this->dispatch('show-school-year-offcanvas');
        } else {
            $this->dispatch('schoolYearSaved', [
                'type' => 'error',
                'message' => 'Année scolaire introuvable',
            ]);
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
            if ($this->schoolYear) {
                // Update existing school year
                $this->form->update();
                $message = AppMessage::DATA_UPDATED_SUCCESS;
            } else {
                // Create new school year
                $this->form->store();
                $message = AppMessage::DATA_SAVED_SUCCESS;
            }

            $this->dispatch('schoolYearSaved', [
                'type' => 'success',
                'message' => $message,
            ]);
            $this->dispatch('close-offcanvas', offcanvasId: 'schoolYearFormOffcanvas');
            $this->closeOffcanvas();
        } catch (\Exception $ex) {
            $this->dispatch('schoolYearSaved', [
                'type' => 'error',
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function closeOffcanvas(): void
    {
        $this->isOpen = false;
        $this->reset(['schoolYear']);
        $this->form->reset();
        $this->dispatch('hide-school-year-offcanvas');
    }

    public function render()
    {
        return view('livewire.application.v2.configuration.form.school-year-form-offcanvas');
    }
}
