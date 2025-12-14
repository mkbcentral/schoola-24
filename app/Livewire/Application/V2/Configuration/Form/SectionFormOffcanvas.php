<?php

namespace App\Livewire\Application\V2\Configuration\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\SectionForm;
use App\Models\Section;
use App\Services\Configuration\SectionService;
use Livewire\Attributes\On;
use Livewire\Component;

class SectionFormOffcanvas extends Component
{
    public SectionForm $form;
    public ?Section $section = null;
    public bool $isOpen = false;

    // Service injecté via boot
    protected SectionService $sectionService;

    /**
     * Injection de dépendances via boot
     */
    public function boot(SectionService $sectionService): void
    {
        $this->sectionService = $sectionService;
    }

    #[On('openSectionModal')]
    public function openCreate(): void
    {
        $this->reset(['section']);
        $this->form->reset();
        $this->isOpen = true;
        $this->dispatch('show-section-offcanvas');
    }

    #[On('openSectionEditModal')]
    public function openEdit(int $id): void
    {
        $this->section = Section::find($id);

        if ($this->section) {
            $this->form->setSection($this->section);
            $this->isOpen = true;
            $this->dispatch('show-section-offcanvas');
        } else {
            $this->dispatch('sectionSaved', [
                'type' => 'error',
                'message' => 'Section introuvable',
            ]);
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
            if ($this->section) {
                // Update existing section
                $this->form->update();
                $message = AppMessage::DATA_UPDATED_SUCCESS;
            } else {
                // Create new section
                $this->form->store();
                $message = AppMessage::DATA_SAVED_SUCCESS;
            }

            $this->dispatch('sectionSaved', [
                'type' => 'success',
                'message' => $message,
            ]);
            $this->dispatch('close-offcanvas');
            $this->closeOffcanvas();
        } catch (\Exception $ex) {
            $this->dispatch('sectionSaved', [
                'type' => 'error',
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function closeOffcanvas(): void
    {
        $this->isOpen = false;
        $this->reset(['section']);
        $this->form->reset();
        $this->dispatch('hide-section-offcanvas');
    }

    public function render()
    {
        return view('livewire.application.v2.configuration.form.section-form-offcanvas');
    }
}
