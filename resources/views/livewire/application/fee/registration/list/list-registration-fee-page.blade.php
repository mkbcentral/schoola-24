<div>
    <x-navigation.bread-crumb icon='bi bi-wallet2' label="GESTION FRAIS INSCRIPTION">
        <x-navigation.bread-crumb-item label='Frais inscription' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="card  card-indigo p-4">
            <div class="row">
                <div class="col-md-8">
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-uppercase"><i class="fa fa-list" aria-hidden="true"></i>
                                Liste des frais d'inscription
                            </h5>
                        </div>
                        <div class="mt-2">
                            <div class="d-flex justify-content-center pb-2">
                                <x-widget.loading-circular-md wire:loading />
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <x-form.search-input wire:model.live='q' />
                                <div class="d-flex align-items-center ">
                                    <x-form.label value="{{ __('Option') }}" class=" me-2" />
                                    <x-widget.data.list-option type='text' wire:model.live='option_filter'
                                        :error="'option_filter'" />
                                </div>
                            </div>
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="bg-app">
                                    <tr class="cursor-hand">
                                        <th class="text-center">#</th>
                                        <th>NOM FRAIS</th>
                                        <th class="">OPTION</th>
                                        <th class="text-end">MONTANT</th>
                                        <th class="text-end">DEVISE</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($registrationFees->isEmpty())
                                        <tr>
                                            <td colspan="6"><x-errors.data-empty /></td>
                                        </tr>
                                    @else
                                        @foreach ($registrationFees as $index => $registrationFee)
                                            <tr wire:key='{{ $registrationFee->id }}'>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $registrationFee->name }}</td>
                                                <td class="">{{ $registrationFee?->option?->name }}</td>
                                                <td class="text-end">{{ $registrationFee->amount }}</td>
                                                <td class="text-end">{{ $registrationFee->currency }}</td>
                                                <td class="text-center">
                                                    <x-form.app-button type='button' icon="bi bi-pencil-fill"
                                                        class="btn-sm app-btn"
                                                        wire:click='edit({{ $registrationFee }})' />
                                                    <x-form.app-button
                                                        wire:confirm="Est-vous sur de réaliser l'opération"
                                                        type='button' icon="bi bi-trash-fill"
                                                        class="btn-secondary btn-sm"
                                                        wire:click='delete({{ $registrationFee }})' />
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>{{ $registrationFees->links('livewire::bootstrap') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <livewire:application.fee.registration.form.form-registration-fee-page>
                </div>
            </div>
        </div>
    </x-content.main-content-page>
</div>
