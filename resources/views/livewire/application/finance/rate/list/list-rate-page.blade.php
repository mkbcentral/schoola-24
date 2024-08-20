<div>
    <div class="row">
        <div class="col-md-8 mt-2">
            <table class="table table-bordered table-hover table-sm">
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
                                <td>{{ $rate->amount }}</td>
                                <td
                                    class="text-uppercase badge {{ $rate->is_changed == true ? 'text-bg-warning' : 'text-bg-success' }}">
                                    {{ $rate->is_changed == true ? 'Changé' : "En cours d'utilisation" }}
                                </td>
                                <td class="text-center">
                                    <x-form.app-button type='button'
                                        icon="{{ $rate->is_changed == true ? 'bi bi-x-lg' : 'bi bi-check2-all' }}"
                                        class="btn-sm {{ $rate->is_changed == true ? 'btn-warning' : 'btn-secondary' }} "
                                        wire:click='changeStatus({{ $rate }})'
                                        wire:confirm="Etês-vous sûr de réaliser l'opération" />
                                    @if ($rate->is_changed == false)
                                        <x-form.app-button type='button' icon="bi bi-pencil-fill"
                                            class="btn-sm app-btn" wire:click='edit({{ $rate }})' />
                                    @elseif($rate->payments->isEmpty())
                                        <x-form.app-button type='button' icon="bi bi-trash-fill"
                                            class="btn-danger btn-sm" wire:confirm='Etês-vous sûr de supprimer ?'
                                            wire:click='delete({{ $rate }})' />
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-md-4 mt-2">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="{{ $rateSelected == null ? 'bi bi-plus-circle' : 'bi bi-pencil-fill' }}"></i>
                        {{ $rateSelected == null ? 'CREATION TAUX' : 'EDITION TAUX' }}

                    </h4>
                </div>
                <div class="card-body">
                    <form wire:submit='handlerSubmit'>
                        <div>
                            <x-form.label value="{{ __('Taux') }}" class="text-secondary" />
                            <x-form.input type='text' wire:model.blur='amount' :error="'amount'" />
                            <x-errors.validation-error value='amount' />
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            @if ($rateSelected != null)
                                <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler"
                                    icon="bi bi-x" class="btn-danger" />
                            @endif
                            <x-form.app-button type='submit' textButton="Sauvegarder"
                                icon="{{ $rateSelected == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                                class="app-btn ml-2" />

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
