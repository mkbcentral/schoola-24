<div>
    <x-navigation.bread-crumb icon='bi bi-columns-gap' label="Options">
        <x-navigation.bread-crumb-item label='Options' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card p-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex justify-content-between">
                        <x-others.list-title title='Liste options' icon='bi bi-card-checklist' />
                        <x-widget.loading-circular-md wire:loading />
                    </div>
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="bg-app">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nom</th>
                                <th>Section</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($options->isEmpty())
                                <tr>
                                    <td colspan="4"><x-errors.data-empty /></td>
                                </tr>
                            @else
                                @foreach ($options as $index => $option)
                                    <tr wire:key='{{ $option->id }}'>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $option->name }}</td>
                                        <td>{{ $option?->section?->name }}</td>
                                        <td class="text-center">
                                            <x-form.app-button type='button' icon="bi bi-pencil-fill"
                                                class="btn-sm btn-primary" wire:click='edit({{ $option }})' />
                                            <x-form.app-button type='button' icon="bi bi-trash-fill"
                                                class="btn-secondary btn-sm" wire:confirm='Etês-vous sûr de supprimer ?'
                                                wire:click='delete({{ $option }})' />
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <span>{{ $options->links('livewire::bootstrap') }}</span>
                </div>
                <div class="col-md-6">
                    <livewire:application.config.form.form-option-page />
                </div>
            </div>
        </div>

    </x-content.main-content-page>
</div>
