<?php

namespace App\Livewire\Application\Dashboard\Registration;

use App\Domain\Features\Registration\RegistrationFeature;
use Livewire\Component;

class DashRegistrationCountPage extends Component
{

    protected $listeners = [
        "dateFilder" => "getDateFilter",
        "monthFilder" => "getMonthFilter",
    ];
    public string $date_filter;
    public string $month_filter;

    public bool $is_by_date = true;

    public function getDateFilter(string $date)
    {
        $this->date_filter = $date;
        $this->is_by_date = true;
    }
    public function getMonthFilter(string $month)
    {
        $this->month_filter = $month;
        $this->is_by_date = false;
    }

    public function mount(string $date)
    {
        $this->date_filter = $date;
    }

    public function render()
    {
        return view('livewire.application.dashboard.registration.dash-registration-count-page', [
            'counter_new' => $this->is_by_date == true ?
                RegistrationFeature::getTotalCountByDate(
                    $this->date_filter,
                    null,
                    null,
                    null,
                    false
                ) : RegistrationFeature::getTotalCountByMonth(
                    $this->month_filter,
                    null,
                    null,
                    null,
                    false
                ),
            'counter_old' => $this->is_by_date == true ?
                RegistrationFeature::getTotalCountByDate(
                    $this->date_filter,
                    null,
                    null,
                    null,
                    true
                ) : RegistrationFeature::getTotalCountByMonth(
                    $this->month_filter,
                    null,
                    null,
                    null,
                    true
                ),
        ]);
    }
}
