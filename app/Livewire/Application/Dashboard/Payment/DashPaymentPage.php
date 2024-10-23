<?php

namespace App\Livewire\Application\Dashboard\Payment;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Payment\PaymentFeature;
use App\Models\CategoryFee;
use App\Models\School;
use App\Models\SchoolYear;
use Livewire\Component;

class DashPaymentPage extends Component
{
    protected $listeners = [
        "dateFilder" => "getDateFilter",
        "monthFilder" => "getMonthFilter",
    ];
    public string $date_filter;
    public string $month_filter = '';

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

        return view('livewire.application.dashboard.payment.dash-payment-page', [
            'categoryFees' =>  FeeDataConfiguration::getListCategoryFee(100),
            'total' => $this->is_by_date == true ?
                PaymentFeature::getTotal(
                    $this->date_filter,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    true,
                    null,
                    'CDF'
                ) : PaymentFeature::getTotal(
                    null,
                    $this->month_filter,
                    null,
                    null,
                    null,
                    null,
                    null,
                    true,
                    false,
                    'CDF'
                )
        ]);
    }
}
