<div>
    <x-navigation.bread-crumb icon='bi bi-bar-chart-fill' label="Dashboard">
        <x-navigation.bread-crumb-item label='Dashboard' />
    </x-navigation.bread-crumb>

    @if (Auth::user()->role->name == 'SCHOOL_MANAGER')
        <livewire:application.dashboard.payment.dash-other-info-page />
        <div class="row">
            <div class="col-md-5">
                <livewire:application.dashboard.payment.dash-payment-date-or-month-page />
            </div>
            <div class="col-md-7">
                <livewire:application.dashboard.expense.dash-date-cost-expense-page />
                <livewire:application.dashboard.expense.dash-date-other-expense-page />
                <livewire:application.dashboard.registration.dash-student-counter-page />
            </div>
        </div>
    @endif

    @if (Auth::user()->role->name == 'SCHOOL_SECRETARY' || Auth::user()->role->name == 'SCHOOL_GUARD')
        <livewire:application.dashboard.registration.student-info-card-page />
        <livewire:application.dashboard.registration.dash-student-counter-page />
    @endif

    @if (Auth::user()->role->name == 'SCHOOL_FINANCE' || Auth::user()->role->name == 'SCHOOL_BOSS')
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
        <livewire:application.dashboard.dash-synthese-page />
    @endif

    <livewire:application.dashboard.payment.dash-payment-page />
    <livewire:application.dashboard.expense.dash-exepense-page />
    <livewire:application.dashboard.expense.dash-other-expense-page />
</div>
