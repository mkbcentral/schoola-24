<div class="container mt-4">
    <div class="mb-3">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Mois') }}" class="me-2" />
                <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" />
            </div>
            <x-form.app-button wire:click="initForm" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                textButton='Nouveau' icon="bi bi-plus-circle" class="btn-primary" />
        </div>

    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Description</th>
                <th class="text-end">Montant</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($otherRecipes as $index => $recipe)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $recipe->created_at->format('d/m/Y') }}</td>
                    <td>{{ $recipe->description }}</td>
                    <td class="text-end">{{ app_format_number($recipe->amount, 1) }}</td>
                    <td class="text-center">
                        <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                            class="btn-secondary btn-sm">
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
                <div>
                    <x-form.label value="{{ __('Montant') }}" class="text-secondary" />
                    <x-form.input type='text' wire:model='amount' :error="'amount'" />
                    <x-errors.validation-error value='amount' />
                </div>
                <div>
                    <x-form.label value="{{ __('Date opération') }}" class="text-secondary" />
                    <x-form.input type='date' wire:model='created_at' :error="'created_at'" />
                    <x-errors.validation-error value='created_at' />
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
