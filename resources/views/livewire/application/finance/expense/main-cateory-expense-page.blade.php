<div>
    <x-navigation.bread-crumb icon='bi bi-journals' label="Catégories dépenses">
        <x-navigation.bread-crumb-item label='Categorie dépenses' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-8 mt-2">
                <livewire:application.finance.expense.list.list-category-expense-page />
            </div>
            <div class="col-md-4 mt-2">
                <livewire:application.finance.expense.form.form-category-expense-page />
            </div>
        </div>
    </x-content.main-content-page>
</div>
