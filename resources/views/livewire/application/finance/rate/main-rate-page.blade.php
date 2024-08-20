<div>
    <x-navigation.bread-crumb icon='bi bi-cash-coin' label="Gestion taux">
        <x-navigation.bread-crumb-item label='Taux' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <livewire:application.finance.rate.list.list-rate-page />
    </x-content.main-content-page>
</div>
