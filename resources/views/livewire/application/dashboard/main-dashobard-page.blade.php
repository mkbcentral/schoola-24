<div>
    <x-navigation.bread-crumb icon='bi bi-bar-chart-fill' label="Dashboard">
        <x-navigation.bread-crumb-item label='Dashboard' />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="d-flex justify-content-between align-items-center">
            @can('view-school-access')
                <div>
                    <x-form.label value="{{ __('Date') }}" />
                    <x-form.input type='date' wire:model.live='date_filter' icon='bi bi-calendar-date-fill'
                        :error="'date_filter'" />
                </div>
                <x-widget.loading-circular-md wire:loading />
                <div>
                    <x-form.label value="{{ __('Mois') }}" />
                    <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" />
                </div>
            @endcan

        </div>
        <div class="row">
            @can('view-school-access')
                <div class="col-md-6">
                    <livewire:application.dashboard.registration.dash-registration-count-page :date="$date_filter">
                </div>
                <div class="col-md-6">
                    <livewire:application.dashboard.registration.dash-registration-by-class-room-page :date="$date_filter">
                </div>
            @endcan
        </div>
    </x-content.main-content-page>
</div>
