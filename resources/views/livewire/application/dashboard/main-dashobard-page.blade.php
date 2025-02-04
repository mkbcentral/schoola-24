<div>
    <x-navigation.bread-crumb icon='bi bi-bar-chart-fill' label="Dashboard">
        <x-navigation.bread-crumb-item label='Dashboard' />
    </x-navigation.bread-crumb>
    <livewire:application.dashboard.payment.dash-other-info-page />
    <div class="row">
        <div class="col-md-5">
            <livewire:application.dashboard.payment.dash-payment-date-or-month-page />
        </div>
        <div class="col-md-7">
            <livewire:application.dashboard.expense.dash-date-cost-expense-page />
            <livewire:application.dashboard.expense.dash-date-other-expense-page />
        </div>
    </div>
    <div class="row">
        <livewire:application.dashboard.dash-synthese-page />
    </div>
    <livewire:application.dashboard.payment.dash-payment-page />
    <livewire:application.dashboard.expense.dash-exepense-page />
    <livewire:application.dashboard.expense.dash-other-expense-page />
</div>
