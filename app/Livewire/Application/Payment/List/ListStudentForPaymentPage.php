<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\Registration;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListStudentForPaymentPage extends Component
{
    use WithPagination;
    public int $per_page = 100;
    #[Url(as: 'q')]
    public $q = '';
    #[Url(as: 'sortBy')]
    public $sortBy = 'students.name';
    #[Url(as: 'sortAsc')]
    public $sortAsc = true;

    public function sortData($value): void
    {
        if ($value == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $value;
    }

    public function openPaymentForm(?Registration $registration)
    {
        $this->dispatch('registrationData', $registration);
        $this->dispatch('registrationPyaments', $registration);
    }

    public function refreshData(): void
    {
        $this->reset();
    }
    public function render()
    {
        return view('livewire.application.payment.list.list-student-for-payment-page', [
            'registrations' => RegistrationFeature::getList(
                null,
                null,
                null,
                null,
                null,
                null,
                $this->q,
                $this->sortBy,
                $this->sortAsc,
                null,
                $this->per_page
            ),
        ]);
    }
}
