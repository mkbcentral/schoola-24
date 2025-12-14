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

    public int $per_page = 10;

    public int $option_filter = 0;

    public int $class_room_filter = 0;

    #[Url(as: 'q')]
    public $q = '';

    public int $idCategorySelected = 0;

    protected $listeners = [
        'regFeeDataRefreshed' => '$refresh',
        'selectedCategoryFee' => 'getSelectedCategoryFee',
    ];

    public function newFee(): void
    {
        $this->dispatch('dataFormResed');
    }

    public function getSelectedCategoryFee(int $index): void
    {
        $this->idCategorySelected = $index;
        $this->resetPage();
        $this->reset('q');
    }

    public function edit(?ScolarFee $scolarFee): void
    {
        $this->dispatch('scolrFeeData', $scolarFee);
    }

    public function delete(?ScolarFee $scolarFee): void
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

    public function makeIsChange(?ScolarFee $scolarFee): void
    {
        try {
            if (! $scolarFee->is_changed) {
                $scolarFee->is_changed = true;
            } else {
                $scolarFee->is_changed = false;
            }
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
            $scolarFee->update();
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function refreshData(): void
    {
        $this->class_room_filter = 0;
        $this->option_filter = 0;
    }

    public function mount(int $idCategoryFee): void
    {
        $this->idCategorySelected = $idCategoryFee;
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.fee.scolar.list.list-scolar-fee-page', [
            'scolrFees' => FeeDataConfiguration::getListScalarFee(
                $this->idCategorySelected,
                $this->option_filter,
                $this->class_room_filter,
                $this->per_page
            ),
        ]);
    }
}
