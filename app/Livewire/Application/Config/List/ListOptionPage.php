<?php

namespace App\Livewire\Application\Config\List;

use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Domain\Utils\AppMessage;
use App\Models\Option;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class ListOptionPage extends Component
{
    protected $listeners = ['optionDataRefreshed' => '$refresh'];
    use WithPagination;
    public $per_page = 10;

    public function edit(?Option $option)
    {
        $this->dispatch('optionData', $option);
    }


    public function delete(?Option $option)
    {
        try {
            if ($option->classRooms->isEmpty()) {
                $option->delete();
                $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
                $this->resetPage();
            } else {
                $this->dispatch('error', ['message' => AppMessage::ACTION_FAILLED]);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }


    public function render()
    {
        return view('livewire.application.config.list.list-option-page', [
            'options' => SchoolDataFeature::getOptionList($this->per_page)
        ]);
    }
}
