<?php

namespace App\Livewire\Application\Chart\Payment;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Models\Rate;
use Livewire\Component;

class MainPaymentChartPage extends Component
{
    protected $listeners = [
        'dateFilder' => 'getDateFilter',
        'monthFilder' => 'getMonthFilter',
    ];

    public ?string $date_filter;

    public ?string $month_filter = '';

    public bool $is_by_date = true;

    public array $dataSeries = [];

    public array $labels = [];

    public function getDateFilter(string $date)
    {
        $this->date_filter = $date;
        $this->is_by_date = true;
        $this->dataSeries = $this->getChartSeriesData($date);
        $this->month_filter = '';
        $this->dispatch('refreshChart', ['seriesData' => $this->dataSeries]);
    }

    public function getMonthFilter(string $month)
    {
        $this->month_filter = $month;
        $this->is_by_date = false;
        $this->date_filter = null;
        $this->dataSeries = $this->getChartSeriesData($month);
        $this->dispatch('refreshChart', ['seriesData' => $this->dataSeries]);
    }

    public function mount(string $date)
    {
        $this->date_filter = $date;
        $this->dataSeries = $this->getChartSeriesData($this->date_filter);
        $this->labels = $this->getChartLabels();
    }

    public function getChartLabels(): array
    {
        $lables = [];
        $categoryFees = FeeDataConfiguration::getListCategoryFee(100);
        foreach ($categoryFees as $categoryFee) {
            $lables[] = $categoryFee->name;
        }

        return $lables;
    }

    public function getChartSeriesData(string $filter): array
    {
        $seriesData = [];
        $categoryFees = FeeDataConfiguration::getListCategoryFee(100);
        $amount = 0;
        foreach ($categoryFees as $categoryFee) {
            if ($this->is_by_date == true) {
                if ($categoryFee->currency == 'CDF') {
                    $amount = floor($categoryFee->getAmountByDate($filter) / Rate::DEFAULT_RATE());
                } else {
                    $amount = $categoryFee->getAmountByDate($filter);
                }
            } else {
                if ($categoryFee->currency == 'CDF') {
                    $amount = floor($categoryFee->getAmountByMonth($filter) / Rate::DEFAULT_RATE());
                } else {
                    $amount = $categoryFee->getAmountByMonth($filter);
                }
            }

            $seriesData[] = $amount;
        }

        return $seriesData;
    }

    public function render()
    {
        return view('livewire.application.chart.payment.main-payment-chart-page');
    }
}
