<div>
    <x-navigation.bread-crumb icon='bi bi-link' label="Multi menu">
        <x-navigation.bread-crumb-item label='Multi menu' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-8 mt-2">
                <livewire:application.navigation.list.list-multi-link-page />
            </div>
            <div class="col-md-4 mt-2">
                <livewire:application.navigation.form.form-multi-link-page />
            </div>
        </div>
    </x-content.main-content-page>
</div>
