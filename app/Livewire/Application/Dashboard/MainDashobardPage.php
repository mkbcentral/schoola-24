<?php

namespace App\Livewire\Application\Dashboard;

use App\Domain\Helpers\SmsNotificationHelper;
use App\Domain\Utils\AppMessage;
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
    public function testSMS()
    {
        $phone = '+243898337969';
        $message = 'He, my freind, how are you?';
        SmsNotificationHelper::sendOrangeSMS($phone, $message);
        $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
    }
    public function render()
    {
        return view('livewire.application.dashboard.main-dashobard-page');
    }
}
