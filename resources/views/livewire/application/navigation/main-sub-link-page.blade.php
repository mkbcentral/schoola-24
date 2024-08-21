<div>
    <x-navigation.bread-crumb icon='bi bi-link' label="Simple menu">
        <x-navigation.bread-crumb-item label='Simple menu' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-8 mt-2">
                <livewire:application.navigation.list.list-sublink-page />
            </div>
            <div class="col-md-4 mt-2">
                <livewire:application.navigation.form.form-sub-link-page />
            </div>
        </div>
    </x-content.main-content-page>
</div>
