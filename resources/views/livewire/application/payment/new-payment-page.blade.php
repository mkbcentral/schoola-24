<div>
    <x-navigation.bread-crumb icon='bi bi-arrow-left-right' label="Paiement frais">
        <x-navigation.bread-crumb-item label='Paiements des frais' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-5 mt-2">
                @livewire('application.payment.list.list-student-for-payment-page')
            </div>
            <div class="col-md-7 mt-2">
                @livewire('application.payment.list.list-payment-by-date-page')
            </div>
        </div>
    </x-content.main-content-page>
</div>
