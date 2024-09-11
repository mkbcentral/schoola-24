<?php

namespace App\Livewire\Application\Widgets\Student;

use App\Domain\Helpers\DateFormatHelper;
use App\Models\Registration;
use Livewire\Component;

class StudentPaymentsInfoWidget extends Component
{
    public Registration $registration;

    public function mount(Registration $registration)
    {
        $this->registration = $registration;
    }
    public function render()
    {
        return view('livewire.application.widgets.student.student-payments-info-widget', [
            'months' => DateFormatHelper::getScoolFrMonths()
        ]);
    }
}
