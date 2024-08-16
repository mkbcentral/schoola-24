<div>
    <x-navigation.bread-crumb icon='bi bi-wallet-fill' label="Epargnes">
        <x-navigation.bread-crumb-item label='Epargnes' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-8 mt-2">
                <livewire:application.finance.saving.list.list-saving-money-page />
            </div>
            <div class="col-md-4 mt-2">
                <livewire:application.finance.saving.form.form-saving-money-page />
            </div>
        </div>
    </x-content.main-content-page>
</div>
