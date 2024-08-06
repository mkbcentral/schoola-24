<?php

namespace App\Livewire\Application\Fee\Scolar\List;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Utils\AppMessage;
use App\Models\ScolarFee;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListScolarFeePage extends Component
{
    use WithPagination;
    public int $per_page = 5, $option_filter = 0, $class_room_filter = 0;

    #[Url(as: 'q')]
    public $q = '';
    public int $idCategorySelected = 0;

    protected $listeners = [
        "regFeeDataRefreshed" => '$refresh',
        "selectedCategoryFee" => 'getSelectedCategoryFee'
    ];

    public function newFee()
    {
        $this->dispatch('dataFormResed');
    }

    public function getSelectedCategoryFee(int $index)
    {
        $this->idCategorySelected = $index;
    }

    public function edit(?ScolarFee $scolarFee)
    {
        $this->dispatch('scolrFeeData', $scolarFee);
    }

    public function delete(?ScolarFee $scolarFee)
    {
        try {
            if ($scolarFee->payments->isEmpty()) {
                $scolarFee->delete();
                $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
            } else {
                $this->dispatch('error', ['message' => AppMessage::ACTION_FAILLED]);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function refreshData()
    {
        $this->class_room_filter = 0;
        $this->option_filter = 0;
    }

    public function mount(int $idCategoryFee)
    {
        $this->idCategorySelected = $idCategoryFee;
    }

    public function render()
    {
        return view('livewire.application.fee.scolar.list.list-scolar-fee-page', [
            'scolrFees' => FeeDataConfiguration::getListScalarFee(
                $this->idCategorySelected,
                $this->option_filter,
                $this->class_room_filter,
                $this->per_page
            )
        ]);
    }
}
