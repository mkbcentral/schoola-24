<div>
    <div class="d-flex justify-content-end">
        <x-form.app-button
            wire:click="initForm"
            data-bs-toggle="modal" data-bs-target="#form-rate"
            textButton='Nouveau' icon="bi bi-plus-circle" class="app-btn" />
    </div>
    <table class="table table-bordered table-sm mt-2">
        <thead class="bg-app">
        <tr>
            <th class="text-center">#</th>
            <th>Taux</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if ($rates->isEmpty())
            <tr>
                <td colspan="4"><x-errors.data-empty /></td>
            </tr>
        @else
            @foreach ($rates as $index => $rate)
                <tr wire:key='{{ $rate->id }}'>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $rate->amount }}</td>
                    <td
                        class="text-uppercase badge {{ $rate->is_changed ? 'text-bg-warning' : 'text-bg-success' }}">
                        {{ $rate->is_changed ? 'Changé' : "En cours d'utilisation" }}
                    </td>
                    <td class="text-center">

                        @can('manage-payment')
                            <x-others.dropdown
                                wire:ignore.self icon="bi bi-three-dots-vertical"
                                class="btn-secondary btn-sm">
                                <x-others.dropdown-link
                                    iconLink='bi bi-pencil-fill' labelText='Editer' href="#"
                                    data-bs-toggle="modal" data-bs-target="#form-rate"
                                    wire:click='edit({{ $rate }})' />
                                <x-others.dropdown-link
                                    iconLink="{{ $rate->is_changed ? 'bi bi-x-lg' : 'bi bi-check2-all' }}"
                                    labelText="{{ $rate->is_changed ? 'Activer' : 'Déactiver' }}" href="#"
                                    wire:click='edit({{ $rate }})' />
                                @if($rate->payments->isEmpty())
                                    <x-others.dropdown-link
                                        iconLink='bi bi-trash-fill' labelText='Supprimer' href="#"
                                        wire:confirm='Etês-vous sûr de supprimer ?'
                                        wire:click='delete({{ $rate }})' />
                                @endif
                            </x-others.dropdown>
                        @endcan
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <x-modal.build-modal-fixed
        idModal='form-rate' size='md'
        headerLabel=" {{ $rateSelected == null ? 'CREATION TAUX' : 'MODIFICATION TAUX' }}"
        headerLabelIcon="{{ $rateSelected == null ? 'bi bi-plus-circle' : 'bi bi-pencil-fill' }}">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <form wire:submit='handlerSubmit'>
            <div>
                <x-form.label value="{{ __('Taux') }}" class="text-secondary" />
                <x-form.input type='text' wire:model='amount' :error="'amount'" />
                <x-errors.validation-error value='amount' />
            </div>
            <div class="d-flex justify-content-between mt-4">
               <div>
                   @if ($rateSelected != null)
                       <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler"
                                          icon="bi bi-x" class="btn-danger" />
                   @endif
               </div>
                <x-form.app-button type='submit'
                                   textButton="Sauvegarder'"
                                   icon="bi bi-arrow-left-righ"
                                   class="app-btn" />

            </div>
        </form>
    </x-modal.build-modal-fixed>
</div>
