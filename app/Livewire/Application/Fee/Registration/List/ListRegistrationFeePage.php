<?php

namespace App\Livewire\Application\Fee\Registration\List;

use App\Domain\Features\Fee\RegistrationFeeFeature;
use App\Domain\Utils\AppMessage;
use App\Models\RegistrationFee;
use Exception;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListRegistrationFeePage extends Component
{
    use WithPagination;

    public int $per_page = 10;

    public int $option_filter = 0;

    #[Url(as: 'q')]
    public $q = '';

    protected $listeners = ['regFeeDataRefreshed' => '$refresh'];

    public function edit(?RegistrationFee $Registrationfee)
    {
        $this->dispatch('RegistrationFeeData', $Registrationfee);
    }

    public function delete(?RegistrationFee $Registrationfee)
    {
        try {
            if ($Registrationfee->registrations->isEmpty()) {
                $Registrationfee->delete();
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
        return view('livewire.application.fee.registration.list.list-registration-fee-page', [
            'registrationFees' => RegistrationFeeFeature::getListRegistrationFee(
                $this->q,
                $this->option_filter,
                $this->per_page
            ),
        ]);
    }
}
