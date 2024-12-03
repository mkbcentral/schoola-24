<div>
    <x-navigation.bread-crumb icon='bi bi-card-heading' label="Situation salaire">
        <x-navigation.bread-crumb-item label='Salaire' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-12 mt-2">
                    <livewire:application.finance.salary.list.list-salary-page />
            </div>
        </div>
    </x-content.main-content-page>
</div>


