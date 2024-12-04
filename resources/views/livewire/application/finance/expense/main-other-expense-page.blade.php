<div>
    <x-navigation.bread-crumb icon='bi bi-folder-symlink' label="Autres dépenses">
        <x-navigation.bread-crumb-item label='Autres dépenses' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <livewire:application.finance.expense.list.list-other-expense-page />
    </x-content.main-content-page>
</div>
