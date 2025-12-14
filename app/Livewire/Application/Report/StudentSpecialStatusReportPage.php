<?php

namespace App\Livewire\Application\Report;

use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\Registration;
use Livewire\Component;

class StudentSpecialStatusReportPage extends Component
{
    public $optionId = null;

    public $classRoomId = null;

    public $allOptions = [];

    public $allClassRooms = [];

    public $derogations = [];

    public $exempted = [];

    public function mount()
    {
        $this->allOptions = Option::all();
        $this->updateClassRooms();
        $this->loadLists();
    }

    public function updatedOptionId()
    {
        $this->classRoomId = null;
        $this->updateClassRooms();
        $this->loadLists();
    }

    public function updatedClassRoomId()
    {
        $this->loadLists();
    }

    protected function updateClassRooms()
    {
        if ($this->optionId) {
            $this->allClassRooms = ClassRoom::where('option_id', $this->optionId)->get();
        } else {
            $this->allClassRooms = ClassRoom::all();
        }
    }

    public function loadLists()
    {
        $queryDerog = Registration::with(['student', 'classRoom.option', 'derogations'])
            ->where('is_fee_exempted', false)
            ->where('is_under_derogation', true);
        $queryExempt = Registration::with(['student', 'classRoom.option', 'derogations'])
            ->where('is_fee_exempted', true);
        if ($this->optionId) {
            $queryDerog->whereHas('classRoom.option', function ($q) {
                $q->where('options.id', $this->optionId);
            });
            $queryExempt->whereHas('classRoom.option', function ($q) {
                $q->where('options.id', $this->optionId);
            });
        }
        if ($this->classRoomId) {
            $queryDerog->where('class_room_id', $this->classRoomId);
            $queryExempt->where('class_room_id', $this->classRoomId);
        }
        $this->derogations = $queryDerog->get();
        $this->exempted = $queryExempt->get();
    }

    public function render()
    {
        return view('livewire.reports.student-special-status-report-page');
    }
}
