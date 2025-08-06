<div>
    <x-navigation.bread-crumb icon='bi bi-houses-fill' label="Classes">
        <x-navigation.bread-crumb-item label='Année scolaire' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card p-4">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Année scolaire</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schoolYears as $index => $schoolYear)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $schoolYear->name }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $schoolYear->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $schoolYear->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-sm" wire:click="edit({{ $schoolYear->id }})">
                                            <i class="bi bi-pencil-square"></i> Modifier
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                            wire:confirm="Êtes-vous sûr de supprimer ?"
                                            wire:click="delete( {{ $schoolYear->id }})">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucune année scolaire trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 text-uppercase">
                                <i
                                    class="bi {{ $selectedSchoolYear ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                                {{ $selectedSchoolYear ? "MODIFIER L'ANNÉE SCOLAIRE" : 'AJOUTER UNE ANNÉE SCOLAIRE' }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="handlerSubmit">
                                <div>
                                    <x-form.label value="{{ __('Année scolaire') }}" />
                                    <x-form.input type='text' wire:model.blur='name' :error="'name'" />
                                    <x-errors.validation-error value='name' />
                                </div>
                                <div class="mt-3">
                                    <x-form.label value="{{ __('Active ?') }}" />
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active"
                                            wire:model='is_active'>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('Activer cette année scolaire') }}
                                        </label>
                                    </div>
                                    <x-errors.validation-error value='is_active' />
                                </div>
                                <div class="mt-4 text-end">
                                    <x-form.app-button type='submit'
                                        textButton="{{ $selectedSchoolYear == null ? 'Sauvegarder' : 'Modifier' }}"
                                        icon="{{ $selectedSchoolYear == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                                        class="btn-primary" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </x-content.main-content-page>
</div>
