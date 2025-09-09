<div>
    <div class="d-flex justify-content-center">
        <x-widget.loading-circular-md wire:loading />
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 gap-2">
                <x-form.app-button wire:click='newPayment' data-bs-toggle="modal"
                    data-bs-target="#form-payment-regularization" textButton='Nouveau paiement' icon="bi bi-plus-circle"
                    class="btn-primary mb-2 mb-md-0" />
                <h3 class="text-primary text-uppercase text-end m-0">
                    Total: {{ app_format_number($total, 1) }} {{ $currency }}
                </h3>
            </div>
            <div class="row g-2 align-items-end mb-2">
                <div class="col-12 col-md-auto">
                    <x-form.label value="{{ __('Date') }}" class="me-2" />
                    <x-form.input type='date' wire:model.live='date_filter' :error="'date_filter'" />
                </div>
                <div class="col-12 col-md-auto">
                    <x-form.label value="{{ __('Mois') }}" class="me-2" />
                    <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" />
                </div>
                <div class="col-12 col-md-auto">
                    <x-form.label value="{{ __('Type frais') }}" class="me-2" />
                    <select id="my-select" class="form-select form-control" wire:model.live='category_fee_filter'>
                        <option value="0">Choisir...</option>
                        @foreach ($categoryFees as $cat)
                            <option class="text-uppercase" value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="col-12 col-md-auto">
                    <x-form.label value="{{ __('Option') }}" class="me-2" />
                    <x-widget.data.list-option wire:model.live='option_filter' />
                </div>
                <div class="col-12 col-md-auto">
                    <x-form.label value="{{ __('Classe') }}" class="me-2" />
                    <x-widget.data.list-class-room-by-option optionId='{{ $selectedOption }}'
                        wire:model.live='class_room_filter' />
                </div>
                <div class="col-12 col-md">
                    <x-form.search-input wire:model.live='q' />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover mt-2">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">N°</th>
                            <th wire:click="sortData('payment_regularizations.created_at')" class="cursor-hand">
                                <span>DATE PAIMENT</span>
                                <x-form.sort-icon sortField="payment_regularizations.created_at" :sortAsc="$sortAsc"
                                    :sortBy="$sortBy" />
                            </th>
                            <th wire:click="sortData('payment_regularizations.name')" class="cursor-hand">
                                <span> NOM COMPLET</span>
                                <x-form.sort-icon sortField="payment_regularizations.name" :sortAsc="$sortAsc"
                                    :sortBy="$sortBy" />
                            </th>
                            <th>FRAIS</th>
                            <th class="text-end">MOIS</th>
                            <th class="text-end" wire:click="sortData('payment_regularizations.amount')"
                                class="cursor-hand">
                                <span> MONTANT</span>
                                <x-form.sort-icon sortField="payment_regularizations.amount" :sortAsc="$sortAsc"
                                    :sortBy="$sortBy" />
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
                            @foreach ($paymentsRegularizations as $index => $paymentRegularization)
                                <tr wire:key='{{ $paymentRegularization->id }}'>
                                    <td class="text-center">
                                        {{ $index + 1 }}
                                    </td>
                                    <td>{{ $paymentRegularization->created_at->format('d/m/Y') }}</td>
                                    <td>{{ $paymentRegularization->name }}/{{ $paymentRegularization->classRoom->getOriginalClassRoomName() }}
                                    </td>
                                    <td>{{ $paymentRegularization->categoryFee->name }}</td>
                                    <td class="text-end">
                                        {{ format_fr_month_name($paymentRegularization->month) }}
                                    </td>
                                    <td class="text-end">{{ app_format_number($paymentRegularization->amount, 1) }}
                                        {{ $paymentRegularization->categoryFee->currency }}</td>
                                    <td class="text-center">
                                        @can('manage-payment')
                                            <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                                class="btn-outline-secondary btn-sm">
                                                <x-others.dropdown-link iconLink='bi bi-pencil-fill' data-bs-toggle="modal"
                                                    data-bs-target="#form-payment-regularization" labelText='Editer'
                                                    href="#" wire:click='edit({{ $paymentRegularization }})'
                                                    class="text-primary" />
                                                <x-others.dropdown-link iconLink='bi bi-printer-fill' labelText='Imprimer'
                                                    class="text-primary" target='_blanck'
                                                    href="{{ route('print.reguralization.receipt', $paymentRegularization) }}" />
                                                <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                                    class="text-primary" href="#"
                                                    wire:confirm="Voulez-vous vraiment supprimer ?"
                                                    wire:click='delete({{ $paymentRegularization }})' />
                                            </x-others.dropdown>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <span>{{ $paymentsRegularizations->links('livewire::bootstrap') }}</span>
                @if ($paymentsRegularizations->count() > 9)
                    <x-others.table-page-number wire:model.live='per_page' />
                @endif
            </div>
        </div>
    </div>
    <livewire:application.payment.reguralization.form.form-regularization-page />
</div>
