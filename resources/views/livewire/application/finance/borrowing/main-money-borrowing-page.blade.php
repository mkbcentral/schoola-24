<div>
    <x-navigation.bread-crumb icon='bi bi-wallet' label="Gestionnaire d'emprunts">
        <x-navigation.bread-crumb-item label='Emprunts' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-8 mt-2">
                <livewire:application.finance.borrowing.list.list-borrowing-page />
            </div>
            <div class="col-md-4 mt-2">
                <livewire:application.finance.borrowing.form.form-borrowing-page />
            </div>
        </div>
    </x-content.main-content-page>
</div>
