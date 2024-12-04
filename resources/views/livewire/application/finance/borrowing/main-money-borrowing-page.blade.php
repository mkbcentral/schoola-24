<div>
    <x-navigation.bread-crumb icon='bi bi-wallet' label="Gestionnaire d'emprunts">
        <x-navigation.bread-crumb-item label='Emprunts' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <livewire:application.finance.borrowing.list.list-borrowing-page />
    </x-content.main-content-page>
</div>
