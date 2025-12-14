<?php

namespace App\Livewire\Application\V2\Configuration\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\ClassRoomForm;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Services\Configuration\ClassRoomService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ClassRoomFormOffcanvas extends Component
{
    public ClassRoomForm $form;
    public ?ClassRoom $classRoom = null;
    public bool $isOpen = false;
    public Collection $options;

    // Service injecté via boot
    protected ClassRoomService $classRoomService;

    protected $listeners = [
        'optionSaved' => 'refreshOptions',
        'sectionSaved' => 'refreshOptions',
    ];

    /**
     * Injection de dépendances via boot
     */
    public function boot(ClassRoomService $classRoomService): void
    {
        $this->classRoomService = $classRoomService;
    }

    public function mount()
    {
        $schoolId = Auth::user()->school->id;
        $this->options = Option::with('section')
            ->whereHas('section', fn($q) => $q->where('school_id', $schoolId))
            ->get();
    }

    #[On('openClassRoomModal')]
    public function openCreate(): void
    {
        $this->reset(['classRoom']);
        $this->form->reset();
        $this->isOpen = true;
        $this->dispatch('show-classroom-offcanvas');
    }

    #[On('openClassRoomEditModal')]
    public function openEdit(int $id): void
    {
        $this->classRoom = ClassRoom::find($id);

        if ($this->classRoom) {
            $this->form->setClassRoom($this->classRoom);
            $this->isOpen = true;
            $this->dispatch('show-classroom-offcanvas');
        } else {
            $this->dispatch('classRoomSaved', [
                'type' => 'error',
                'message' => 'Classe introuvable',
            ]);
        }
    }

    public function refreshOptions(): void
    {
        $schoolId = Auth::user()->school->id;
        $this->options = Option::with('section')
            ->whereHas('section', fn($q) => $q->where('school_id', $schoolId))
            ->get();
    }

    public function save(): void
    {
        $this->validate();

        try {
            if ($this->classRoom) {
                // Update existing classroom
                $this->form->update();
                $message = AppMessage::DATA_UPDATED_SUCCESS;
            } else {
                // Create new classroom
                $this->form->store();
                $message = AppMessage::DATA_SAVED_SUCCESS;
            }

            $this->dispatch('classRoomSaved', [
                'type' => 'success',
                'message' => $message,
            ]);
            $this->dispatch('close-offcanvas');
            $this->closeOffcanvas();
        } catch (\Exception $ex) {
            $this->dispatch('classRoomSaved', [
                'type' => 'error',
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function closeOffcanvas(): void
    {
        $this->isOpen = false;
        $this->reset(['classRoom']);
        $this->form->reset();
        $this->dispatch('hide-classroom-offcanvas');
    }

    public function render()
    {
        return view('livewire.application.v2.configuration.form.class-room-form-offcanvas');
    }
}
