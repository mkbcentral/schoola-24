<div>
    <x-navigation.bread-crumb icon='bi bi-tags-fill' label="Gestionnaire categorie frais insc">
        <x-navigation.bread-crumb-item label='Categorie frais insc.' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <x-others.list-title title='Liste des categories' icon='bi bi-card-checklist' />
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-center pb-2">
                                    <x-widget.loading-circular-md wire:loading />
                                </div>
                                <div class="d-flex">
                                    <x-form.search-input wire:model.live='q' />
                                </div>
                                <table class="table table-bordered table-hover table-sm mt-2">
                                    <thead class="bg-app">
                                        <tr class="cursor-hand">
                                            <th class="text-center">#</th>
                                            <th>
                                                Categorie
                                            </th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($categoryRegistrationFees->isEmpty())
                                            <tr>
                                                <td colspan="5"><x-errors.data-empty /></td>
                                            </tr>
                                        @else
                                            @foreach ($categoryRegistrationFees as $index => $categoryRegistrationFee)
                                                <tr wire:key='{{ $categoryRegistrationFee->id }}'>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $categoryRegistrationFee->name }}</td>
                                                    <td class="text-center">
                                                        <x-form.app-button type='button' icon="bi bi-pencil-fill"
                                                            class="btn-sm btn-primary"
                                                            wire:click='edit({{ $categoryRegistrationFee }})' />
                                                        <x-form.app-button
                                                            wire:confirm="Est-vous sur de réaliser l'opération"
                                                            type='button' icon="bi bi-trash-fill"
                                                            class="btn-secondary btn-sm"
                                                            wire:click='delete({{ $categoryRegistrationFee }})' />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>{{ $categoryRegistrationFees->links('livewire::bootstrap') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <livewire:application.fee.registration.form.form-category-registration-fee-page />
                    </div>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>
