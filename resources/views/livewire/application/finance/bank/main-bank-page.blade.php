<div>
    <x-navigation.bread-crumb icon='bi bi-bank2' label="Dépots banque">
        <x-navigation.bread-crumb-item label='Paiements des frais' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-8 mt-2">
                <livewire:application.finance.bank.list.list-bank-deposit-page />
            </div>
            <div class="col-md-4 mt-2">
                <livewire:application.finance.bank.form.form-bank-deposit-page />
            </div>
        </div>
    </x-content.main-content-page>
</div>
