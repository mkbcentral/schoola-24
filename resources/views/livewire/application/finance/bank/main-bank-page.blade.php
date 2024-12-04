<div>
    <x-navigation.bread-crumb icon='bi bi-bank2' label="Dépots banque">
        <x-navigation.bread-crumb-item label='Dépot banque' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <livewire:application.finance.bank.list.list-bank-deposit-page/>
    </x-content.main-content-page>
</div>
