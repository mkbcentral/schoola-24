<div>
    <div class="d-flex justify-content-center">
        <x-widget.loading-circular-md wire:loading />
    </div>
    <div class="d-flex justify-content-between">
        <x-form.app-button wire:click='newPayment' data-bs-toggle="modal" data-bs-target="#form-payment-regularization"
            textButton='Nouveau paiement' icon="bi bi-plus-circle
            " class="btn-primary" />
        <h3 class="text-primary text-uppercase text-end">
            Total: {{ app_format_number($total, 1) }} {{ $currency }}
        </h3>
    </div>
    <div class="d-flex justify-content-between mt-2">
        <div class="d-flex">
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Date') }}" class="me-2" />
                <x-form.input type='date' wire:model.live='date_filter' :error="'date_filter'" />
            </div>
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Mois') }}" class="me-2" />
                <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" />
            </div>
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Type frais') }}" class="me-2" />
                <x-widget.data.list-cat-scolar-fee wire:model.live='category_fee_filter' />
            </div>
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Option') }}" class="me-2" />
                <x-widget.data.list-option wire:model.live='option_filter' />
            </div>
            <div class="d-flex align-items-center pe-2">
                <x-form.label value="{{ __('Classe') }}" class="me-2" />
                <x-widget.data.list-class-room-by-option optionId='{{ $selectedOption }}'
                    wire:model.live='class_room_filter' />
            </div>
        </div>
        <x-form.search-input wire:model.live='q' />
    </div>
    <table class="table table-bordered table-sm table-hover mt-2">
        <thead class="table-primary">
            <tr class="">
                <th class="text-center">N°</th>
                <th class="" wire:click="sortData('payment_regularizations.created_at')" class="cursor-hand">
                    <span>DATE PAIMENT</span>
                    <x-form.sort-icon sortField="payment_regularizations.created_at" :sortAsc="$sortAsc"
                        :sortBy="$sortBy" />
                </th>
                <th class="" wire:click="sortData('payment_regularizations.name')" class="cursor-hand">
                    <span> NOM COMPLET</span>
                    <x-form.sort-icon sortField="payment_regularizations.name" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                </th>
                <th>FRAIS</th>
                <th class="text-end">MOIS</th>
                <th class="text-end" wire:click="sortData('payment_regularizations.amount')" class="cursor-hand">
                    <span> MONTANT</span>
                    <x-form.sort-icon sortField="payment_regularizations.amount" :sortAsc="$sortAsc" :sortBy="$sortBy" />
                </th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @if ($paymentsRegularizations->isEmpty())
            <tr>
                <td colspan="7"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($paymentsRegularizations as $index => $paymentsRegularization)
                    <tr wire:key='{{ $paymentsRegularization->id }}' class=" ">
                        <td class="text-center">
                            {{ $index + 1 }}
                        </td>
                        <td>{{ $paymentsRegularization->created_at->format('d/m/Y') }}</td>
                        <td>{{ $paymentsRegularization->name }}/{{ $paymentsRegularization->classRoom->getOriginalClassRoomName() }}
                        </td>
                        <td>{{ $paymentsRegularization->categoryFee->name }}</td>
                        <td class="text-end">
                            {{ format_fr_month_name($paymentsRegularization->month) }}
                        </td>
                        <td class="text-end">{{ app_format_number($paymentsRegularization->amount, 1) }}
                            {{ $paymentsRegularization->categoryFee->currency }}</td>
                        <td class="text-center">
                            @can('manage-payment')
                                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                    class="btn-secondary btn-sm">
                                    <x-others.dropdown-link iconLink='bi bi-pencil-fill' data-bs-toggle="modal"
                                        data-bs-target="#form-payment-regularization" labelText='Editer' href="#"
                                        wire:click='edit({{ $paymentsRegularization }})' class="text-primary" />
                                    <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                        class="text-primary" href="#" wire:confirm="Voulez-vous vraiment supprimer ?"
                                        wire:click='delete({{ $paymentsRegularization }})' />
                                </x-others.dropdown>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
    <div class="d-flex justify-content-between align-items-center">
        <span>{{ $paymentsRegularizations->links('livewire::bootstrap') }}</span>
        @if ($paymentsRegularizations->count() > 9)
            <x-others.table-page-number wire:model.live='per_page' />
        @endif
    </div>
    <livewire:application.payment.reguralization.form.form-regularization-page />
</div>
