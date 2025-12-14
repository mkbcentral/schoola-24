<?php

namespace App\Livewire\Application\V2\Configuration\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\OptionForm;
use App\Models\Option;
use App\Models\Section;
use App\Services\Configuration\OptionService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class OptionFormOffcanvas extends Component
{
    public OptionForm $form;
    public ?Option $option = null;
    public bool $isOpen = false;
    public Collection $sections;

    // Service injecté via boot
    protected OptionService $optionService;

    protected $listeners = [
        'sectionSaved' => 'refreshSections',
    ];

    /**
     * Injection de dépendances via boot
     */
    public function boot(OptionService $optionService): void
    {
        $this->optionService = $optionService;
    }

    public function mount()
    {
        $schoolId = Auth::user()->school->id;
        $this->sections = Section::where('school_id', $schoolId)->get();
    }

    #[On('openOptionModal')]
    public function openCreate(): void
    {
        $this->reset(['option']);
        $this->form->reset();
        $this->isOpen = true;
        $this->dispatch('show-option-offcanvas');
    }

    #[On('openOptionEditModal')]
    public function openEdit(int $id): void
    {
        $this->option = Option::find($id);

        if ($this->option) {
            $this->form->setOption($this->option);
            $this->isOpen = true;
            $this->dispatch('show-option-offcanvas');
        } else {
            $this->dispatch('optionSaved', [
                'type' => 'error',
                'message' => 'Option introuvable',
            ]);
        }
    }

    public function refreshSections(): void
    {
        $schoolId = Auth::user()->school->id;
        $this->sections = Section::where('school_id', $schoolId)->get();
    }

    public function save(): void
    {
        $this->validate();

        try {
            if ($this->option) {
                // Update existing option
                $this->form->update();
                $message = AppMessage::DATA_UPDATED_SUCCESS;
            } else {
                // Create new option
                $this->form->store();
                $message = AppMessage::DATA_SAVED_SUCCESS;
            }

            $this->dispatch('optionSaved', [
                'type' => 'success',
                'message' => $message,
            ]);
            $this->dispatch('close-offcanvas');
            $this->closeOffcanvas();
        } catch (\Exception $ex) {
            $this->dispatch('optionSaved', [
                'type' => 'error',
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function closeOffcanvas(): void
    {
        $this->isOpen = false;
        $this->reset(['option']);
        $this->form->reset();
        $this->dispatch('hide-option-offcanvas');
    }

    public function render()
    {
        return view('livewire.application.v2.configuration.form.option-form-offcanvas');
    }
}
