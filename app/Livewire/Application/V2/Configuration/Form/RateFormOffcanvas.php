<?php

namespace App\Livewire\Application\V2\Configuration\Form;

use App\Domain\Utils\AppMessage;
use App\Livewire\Forms\RateForm;
use App\Models\Rate;
use App\Services\Configuration\RateService;
use Livewire\Attributes\On;
use Livewire\Component;

class RateFormOffcanvas extends Component
{
    public RateForm $form;
    public ?Rate $rate = null;
    public bool $isOpen = false;

    // Service injecté via boot
    protected RateService $rateService;

    /**
     * Injection de dépendances via boot
     */
    public function boot(RateService $rateService): void
    {
        $this->rateService = $rateService;
    }

    #[On('openRateModal')]
    public function openCreate(): void
    {
        $this->reset(['rate']);
        $this->form->reset();
        $this->isOpen = true;
        $this->dispatch('show-rate-offcanvas');
    }

    #[On('openRateEditModal')]
    public function openEdit(int $id): void
    {
        $this->rate = Rate::find($id);

        if ($this->rate) {
            $this->form->setRate($this->rate);
            $this->isOpen = true;
            $this->dispatch('show-rate-offcanvas');
        } else {
            $this->dispatch('rateSaved', [
                'type' => 'error',
                'message' => 'Taux introuvable',
            ]);
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
            if ($this->rate) {
                // Update existing rate
                $this->form->update();
                $message = AppMessage::DATA_UPDATED_SUCCESS;
            } else {
                // Create new rate
                $this->form->store();
                $message = AppMessage::DATA_SAVED_SUCCESS;
            }

            $this->dispatch('rateSaved', [
                'type' => 'success',
                'message' => $message,
            ]);
            $this->dispatch('close-offcanvas', offcanvasId: 'rateFormOffcanvas');
            $this->closeOffcanvas();
        } catch (\Exception $ex) {
            $this->dispatch('rateSaved', [
                'type' => 'error',
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function closeOffcanvas(): void
    {
        $this->isOpen = false;
        $this->reset(['rate']);
        $this->form->reset();
        $this->dispatch('hide-rate-offcanvas');
    }

    public function render()
    {
        return view('livewire.application.v2.configuration.form.rate-form-offcanvas');
    }
}
