<div>
    <x-navigation.bread-crumb icon='bi bi-arrow-left-right' label="Paiement frais">
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
        <x-navigation.bread-crumb-item label='Paiements des frais' />
    </x-navigation.bread-crumb>
    <div>
        <livewire:application.payment.list.list-student-for-payment-page />
        <label for="registration_id" class="form-label fw-bold">
            Liste des payments jounaliers
        </label>
        <livewire:application.payment.list.list-payment-by-date-page />
    </div>
</div>
