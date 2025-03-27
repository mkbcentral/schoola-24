<div>
    <x-navigation.bread-crumb icon='bi bi-bar-chart-fill' label="Paramètres">
        <x-navigation.bread-crumb-item label='Paramètres' />
    </x-navigation.bread-crumb>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group settings-nav mb-4">
                <a href="#school" class="list-group-item list-group-item-action " data-bs-toggle="list">
                    <i class="bi bi-gear-fill me-2"></i> Paramètres école
                </a>
                <a href="#appearance" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="bi bi-palette me-2"></i> Apparence
                </a>
                <a href="#school-year" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="bi bi-puzzle me-2"></i> Année scolaire
                </a>

            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- General Settings -->
                <div class="tab-pane fade " id="school">
                    <livewire:application.setting.page.setting-school-page />
                </div>
                <!-- Appearance Settings -->
                <div class="tab-pane fade" id="appearance">
                    <livewire:application.setting.page.setting-theme-page />
                </div>
                <!-- SChool yer Settings -->
                <div class="tab-pane fade show active" id="school-year">
                    <livewire:application.setting.page.setting-school-year-page />
                </div>


            </div>
        </div>
    </div>
</div>
