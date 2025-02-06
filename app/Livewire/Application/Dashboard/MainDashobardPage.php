<?php

namespace App\Livewire\Application\Dashboard;

use App\Models\Registration;
use Livewire\Component;

class MainDashobardPage extends Component
{
    public string $date_filter;
    public string $month_filter;

    public function updatedDateFilter(string $val): void
    {
        $this->dispatch('dateSelected', $val);
    }
    public function updatedMonthFilter(string $val): void
    {
        $this->dispatch('monthFilder', $val);
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
