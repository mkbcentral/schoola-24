<?php

namespace App\Livewire\Application\Config\List;

use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Domain\Utils\AppMessage;
use App\Models\ClassRoom;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListClassRoomPage extends Component
{
    use WithPagination;
    protected $listeners = ['classRoomDataRefreshed' => '$refresh'];
    public $option_filer;
    #[Url(as: 'sortBy')]
    public $sortBy = 'class_rooms.name';
    #[Url(as: 'sortAsc')]
    public $sortAsc = true;

    public function sortData($value): void
    {
        if ($value == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $value;
    }


    public function edit(?ClassRoom $classRoom)
    {
        $this->dispatch('classRoomData', $classRoom);
    }


    public function delete(?ClassRoom $classRoom)
    {
        try {
            if ($classRoom->registrations->isEmpty()) {
                $classRoom->delete();
                $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
            } else {
                $this->dispatch('error', ['message' => AppMessage::ACTION_FAILLED]);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }



    public function render()
    {
        return view('livewire.application.config.list.list-class-room-page', [
            'classRooms' => SchoolDataFeature::getClassRoomList(
                $this->option_filer,
                $this->sortBy,
                $this->sortAsc,
                10
            )
        ]);
    }
}
