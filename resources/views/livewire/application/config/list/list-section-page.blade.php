<div>
    <x-navigation.bread-crumb icon='bi bi-diagram-2' label="Sections">
        <x-navigation.bread-crumb-item label='Sections' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card p-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex justify-content-between align-items-center">
                        <x-others.list-title title='Liste des sections' icon='bi bi-card-checklist' />
                        <x-widget.loading-circular-md wire:loading />
                    </div>
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="bg-app">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Section</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($sections->isEmpty())
                                <tr>
                                    <td colspan="3"><x-errors.data-empty /></td>
                                </tr>
                            @else
                                @foreach ($sections as $index => $section)
                                    <tr wire:key='{{ $section->id }}'>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $section->name }}</td>
                                        <td class="text-center">
                                            <x-form.app-button type='button' icon="bi bi-pencil-fill"
                                                class="btn-sm btn-primary" wire:click='edit({{ $section }})' />
                                            <x-form.app-button type='button'
                                                wire:confirm='Etês-vous sûre de supprimer ?' icon="bi bi-trash-fill"
                                                class="btn-secondary btn-sm" wire:click='delete({{ $section }})' />
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>

                    </table>
                </div>
                <div class="col-md-6">
                    <livewire:application.config.form.form-section-page />
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>
