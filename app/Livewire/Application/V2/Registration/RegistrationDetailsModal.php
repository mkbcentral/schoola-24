<?php

namespace App\Livewire\Application\V2\Registration;

use App\Services\Registration\RegistrationService;
use Livewire\Component;

class RegistrationDetailsModal extends Component
{
    public $showModal = false;
    public $registrationId = null;
    public $registration = null;

    protected $listeners = [
        'openRegistrationDetails' => 'openModal',
    ];

    private RegistrationService $registrationService;

    public function boot(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function openModal($registrationId)
    {
        $this->registrationId = $registrationId;
        $this->registration = $this->registrationService->findById($registrationId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['registrationId', 'registration']);
    }

    public function render()
    {
        return view('livewire.application.v2.registration.registration-details-modal');
    }
}
