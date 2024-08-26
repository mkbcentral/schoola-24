<?php

namespace App\Livewire\Application\Fee\Registration\List;

use App\Domain\Features\Fee\CategoryRegistrationFeeFeature;
use App\Domain\Utils\AppMessage;
use App\Models\CategoryRegistrationFee;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListCategoryRegistrationFeePage extends Component
{
    use WithPagination;
    public int $per_page = 10, $option_filter = 0;
    #[Url(as: 'q')]
    public $q = '';

    protected $listeners = ["regCatFeeDataRefreshed" => '$refresh'];
    public function edit(?CategoryRegistrationFee $categoryRegistrationFee)
    {
        $this->dispatch('categoryRegistrationFeeData', $categoryRegistrationFee);
    }

    public function delete(?CategoryRegistrationFee $categoryRegistrationFee)
    {
        try {

            if ($categoryRegistrationFee->RegistrationFees->isEmpty()) {
                $categoryRegistrationFee->delete();
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
        return view('livewire.application.fee.registration.list.list-category-registration-fee-page', [
            'categoryRegistrationFees' =>
            CategoryRegistrationFeeFeature::getListCategoryRegistrationFee($this->q, $this->per_page)
        ]);
    }
}
