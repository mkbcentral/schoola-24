<div class="container mt-4">
    <div class="mb-3">
        <div class="d-flex flex-wrap align-items-end justify-content-between">
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Mois') }}" class="me-2" />
                <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" />
            </div>
            <div class="d-flex align-items-center me-2">
                <label class="me-2">Filtrer par :</label>
                <select class="form-select form-select-sm me-2" style="width:auto;" wire:model.live="filter_type">
                    <option value="all">Tous</option>
                    <option value="period">Périodique</option>
                    <option value="date">Date</option>
                </select>
                <x-form.input type="date" class="form-control form-control-sm me-2" style="width:auto;"
                    wire:model.live="filter_start" placeholder="Début" />
                <x-form.input type="date" class="form-control form-control-sm" style="width:auto;"
                    wire:model.live="filter_end" placeholder="Fin" />
            </div>
            <x-form.app-button wire:click="initForm" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                textButton='Nouveau' icon="bi bi-plus-circle" class="btn-primary" />
        </div>
    </div>
    <div wire:loading.flex wire:target="filter_type,filter_start,filter_end,month_filter"
        class="justify-content-center align-items-center mb-2" style="min-height:40px">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Date/Type</th>
                <th>Description</th>
                <th class="text-end">Montant</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($otherRecipes as $index => $recipe)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        @if ($recipe->is_period)
                            <span class="badge bg-info">Périodique</span><br>
                            <span>{{ $recipe->start_date ? \Carbon\Carbon::parse($recipe->start_date)->format('d/m/Y') : '' }}
                                -
                                {{ $recipe->end_date ? \Carbon\Carbon::parse($recipe->end_date)->format('d/m/Y') : '' }}</span>
                        @else
                            <span class="badge bg-secondary">Date</span><br>
                            <span>{{ $recipe->created_at ? \Carbon\Carbon::parse($recipe->created_at)->format('d/m/Y') : '' }}</span>
                        @endif
                    </td>
                    <td>{{ $recipe->description }}</td>
                    <td class="text-end">{{ app_format_number($recipe->amount, 1) }}</td>
                    <td class="text-center">
                        <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                            class="btn-outline-secondary btn-sm">
                            <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer' href="#"
                                data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                wire:click='edit({{ $recipe }})' />
                            <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer' href="#"
                                wire:confirm='Etês-vous sûr de supprimer ?' wire:click='delete({{ $recipe }})' />
                        </x-others.dropdown>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div wire:ignore.self class="offcanvas offcanvas-end bg-dark" tabindex="-1" id="offcanvasRight"
        aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">
                {{ $otherRecipeSelsected == null ? 'CREATION ENTREE' : 'MODIFICATION ENTREE' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form wire:submit='handlerSubmit'>
                <div>
                    <x-form.label value="{{ __('Description') }}" class="text-secondary" />
                    <x-form.input type='text' wire:model='description' :error="'description'" />
                    <x-errors.validation-error value='description' />
                </div>
                <div class="mb-2">
                    <x-form.label value="{{ __('Montant') }}" class="text-secondary" />
                    <x-form.input type='text' wire:model='amount' :error="'amount'" />
                    <x-errors.validation-error value='amount' />
                </div>
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" id="is_period" wire:model.live="is_period">
                    <label class="form-check-label" for="is_period">{{ __('Activer mode périodique') }}</label>
                </div>
                <div wire:ignore.self>
                    @if ($is_period)
                        <x-form.label value="{{ __('Date début') }}" class="text-secondary" />
                        <x-form.input type='date' wire:model='start_date' :error="'start_date'" />
                        <x-errors.validation-error value='start_date' />
                        <x-form.label value="{{ __('Date fin') }}" class="text-secondary mt-2" />
                        <x-form.input type='date' wire:model='end_date' :error="'end_date'" />
                        <x-errors.validation-error value='end_date' />
                    @else
                        <x-form.label value="{{ __('Date opération') }}" class="text-secondary" />
                        <x-form.input type='date' wire:model='created_at' :error="'created_at'" />
                        <x-errors.validation-error value='created_at' />
                    @endif
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <div>
                        @if ($otherRecipeSelsected != null)
                            <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler"
                                icon="bi bi-x" class="btn-danger" data-bs-dismiss="offcanvas" aria-label="Close" />
                        @endif
                    </div>
                    <x-form.app-button type='submit' textButton="Sauvegarder" icon="bi bi-arrow-left-righ"
                        class="btn-primary" />

                </div>
            </form>
        </div>
    </div>
</div>
