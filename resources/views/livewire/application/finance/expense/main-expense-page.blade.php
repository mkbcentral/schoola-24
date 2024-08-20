<div>
    <x-navigation.bread-crumb icon='bi bi-journal-bookmark-fill' label="Dépsense sur le frais">
        <x-navigation.bread-crumb-item label='Dépsense sur le frais' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-8 mt-2">
                <livewire:application.finance.expense.list.list-expense-fee-page />
            </div>
            <div class="col-md-4 mt-2">
                <livewire:application.finance.expense.form.form-expense-page />
            </div>
        </div>
    </x-content.main-content-page>
</div>
