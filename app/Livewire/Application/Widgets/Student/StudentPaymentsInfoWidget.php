<?php

namespace App\Livewire\Application\Widgets\Student;

use App\Domain\Helpers\DateFormatHelper;
use App\Models\Registration;
use Livewire\Component;

class StudentPaymentsInfoWidget extends Component
{
    public Registration $registration;

    public function mount(Registration $registration): void
    {
        $this->registration = $registration;
    }
    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.widgets.student.student-payments-info-widget', [
            'months' => DateFormatHelper::getSchoolFrMonths()
        ]);
    }
}
