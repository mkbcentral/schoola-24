<div>
    <x-navigation.bread-crumb icon='bi bi-cash-coin' label="Autres recettes">
        <x-navigation.bread-crumb-item label='Taux' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <livewire:application.finance.recipe.list.list-other-recipe-page />
    </x-content.main-content-page>
</div>
