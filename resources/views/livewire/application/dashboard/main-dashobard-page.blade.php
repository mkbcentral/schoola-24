<div>
    <x-navigation.bread-crumb icon='bi bi-bar-chart-fill' label="Dashboard">
        <x-navigation.bread-crumb-item label='Dashboard' />
    </x-navigation.bread-crumb>

    <div class="row">
        @if (Auth::user()->role->name == 'SCHOOL_SECRETARY')
            <livewire:application.dashboard.registration.student-info-card-page />
        @else
            <div class="col-md-5">
                <livewire:application.dashboard.payment.dash-other-info-page />
                <livewire:application.dashboard.payment.dash-payment-date-or-month-page />
            </div>
        @endif

        @if (Auth::user()->role->name == 'SCHOOL_FINANCE' || Auth::user()->role->name == 'SCHOOL_BOSS')
            <div class="col-md-7">
                <livewire:application.dashboard.expense.dash-date-cost-expense-page />
                <livewire:application.dashboard.expense.dash-date-other-expense-page />
            </div>
        @endif

    </div>
    @if (Auth::user()->role->name == 'SCHOOL_FINANCE' || Auth::user()->role->name == 'SCHOOL_BOSS')
        <div class="row">
            <livewire:application.dashboard.dash-synthese-page />
        </div>
    @endif

    <livewire:application.dashboard.payment.dash-payment-page />
    <livewire:application.dashboard.expense.dash-exepense-page />
    <livewire:application.dashboard.expense.dash-other-expense-page />
</div>
