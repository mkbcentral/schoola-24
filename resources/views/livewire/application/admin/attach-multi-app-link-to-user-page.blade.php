<div>
    <x-navigation.bread-crumb icon='bi bi-person-gear' label="Attribuer un sous menu">
        <x-navigation.bread-crumb-item label='Attrebuer un sous menu' />
        <x-navigation.bread-crumb-item label='Liste des utilisateurs' isLinked=true link="admin.main" />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card">
            <div class="card-body">
                <h5>Nom de l'utilisateur: {{ $user->name }}</h5>
                <h5>Email: {{ $user->email }}</h5>
                <h5>Role: {{ $user->role->name }}</h5>
            </div>
        </div>
        <h4 class="mt-2">Liste des menus</h4>
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <div class="row mt-2">
            @foreach ($multiAppLinks as $multiAppLink)
                <div class="col-md-2 g-2">
                    <!-- radio -->
                    <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" wire:model.live='selectedLinks' value="{{ $multiAppLink->id }}"
                                id="{{ $multiAppLink->name }}">
                            <label for="{{ $multiAppLink->name }}">
                                {{ $multiAppLink->name }}
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="d-flex justify-content-end mt-4">
                <x-form.app-button type='button' wire:click='attachLinks' textButton="Attribuer"
                    icon="bi bi-floppy-fill" class="btn-primary" />
            </div>
        </div>
    </x-content.main-content-page>
</div>
