<?php

namespace App\Livewire\Application\Fee\Scolar\List;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Utils\AppMessage;
use App\Models\CategoryFee;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListCategoryScolarFeePage extends Component
{
    use WithPagination;
    public int $per_page = 5, $option_filter = 0;
    #[Url(as: 'q')]
    public $q = '';

    protected $listeners = ["regCatFeeScolarDataRefreshed" => '$refresh'];
    public function edit(?CategoryFee $categoryFee)
    {
        $this->dispatch('categoryScolarFeeData', $categoryFee);
    }

    public function delete(?CategoryFee $categoryFee)
    {
        try {

            if ($categoryFee->scolarFees->isEmpty()) {
                $categoryFee->delete();
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
        return view('livewire.application.fee.scolar.list.list-category-scolar-fee-page', [
            'categoryFees' => FeeDataConfiguration::getListCategoryFee($this->per_page)
        ]);
    }
}
