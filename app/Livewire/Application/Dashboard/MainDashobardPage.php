<?php

namespace App\Livewire\Application\Dashboard;

use Livewire\Component;

class MainDashobardPage extends Component
{

    public string $date_filter;
    public string $month_filter;

    public function updatedDateFilter(string $val): void
    {
        $this->dispatch('dateFilder', $val);
        $this->date_filter = $val;
    }
    public function updatedMonthFilter(string $val): void
    {
        $this->dispatch('monthFilder', $val);
        $this->month_filter = $val;
    }
    public function mount()
    {
        $this->date_filter = date('Y-m-d');
    }
    public function render()
    {
        return view('livewire.application.dashboard.main-dashobard-page');
    }
}
