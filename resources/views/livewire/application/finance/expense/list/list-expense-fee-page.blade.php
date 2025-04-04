<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        <div class="d-flex justify-content-between">
            <h2 class="fw-bold text-primary">
                Total: {{ app_format_number($total_usd, 1) }} $ | {{ app_format_number($total_cdf, 1) }}
                Fc
            </h2>
            @can('view-payment')
                <x-form.app-button wire:click='newExpenseFee' data-bs-toggle="modal" data-bs-target="#form-expense-fee"
                    textButton='Nouveau' icon="bi bi-plus-circle" class="btn-primary btn-sm" />
            @endcan
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex mt-2">
                <div class="d-flex align-items-center me-2">
                    <x-form.label value="{{ __('Mois') }}" class="fw-bold me-2" />
                    <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" />
                </div>
                <div class="d-flex align-items-center  me-2">
                    <x-form.label value="{{ __('Date') }}" class="fw-bold me-2" />
                    <x-form.input type='date' wire:model.live='date_filter' :error="'date_filter'" />
                </div>
                <div class="d-flex align-items-center  me-2">
                    <x-form.label value="{{ __('Type frais') }}" class="fw-bold me-2" />
                    <x-widget.data.list-cat-scolar-fee type='text' wire:model.live='category_fee_id_filter'
                        :error="'category_fee_id_filter'" />
                </div>
                <div class="d-flex align-items-center  me-2">
                    <x-form.label value="{{ __('Categorie') }}" class="fw-bold me-2" />

                    <x-widget.data.list-category-expense type='text' wire:model.live='category_expense_id_filter'
                        :error="'category_expense_id_filter'" />
                </div>
                <div class="d-flex align-items-center form-group">
                    <x-form.label value="{{ __('Dévise') }}" class="fw-bold me-2" />
                    <select id="my-select" class="form-control form-select" name=""
                        wire:model.live='currency_filter'>
                        <option disabled>Choisir</option>
                        <option value=''>Tout</option>
                        <option value="USD">USD</option>
                        <option value="CDF">CDF</option>
                    </select>
                </div>
            </div>

        </div>
        <table class="table table-bordered table-sm table-hover mt-2">
            <thead class="table-primary">
                <tr class="">
                    <th class="text-center">N°</th>
                    <th>DATE</th>
                    <th>DESCRIPTION</th>
                    <th>Cetegoie</th>
                    <th>Source</th>
                    <th>MOIS</th>
                    <th class="text-end">MT USD</th>
                    <th class="text-end">MT CDF</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            @if ($expenseFees->isEmpty())
                <tr>
                    <td colspan="9"><x-errors.data-empty /></td>
                </tr>
            @else
                <tbody>
                    @foreach ($expenseFees as $index => $expenseFee)
                        <tr wire:key='{{ $expenseFee->id }}' class=" ">
                            <td class="text-center ">
                                {{ $index + 1 }}
                            </td>
                            <td>{{ $expenseFee->created_at->format('d/m/Y') }}</td>
                            <td>{{ $expenseFee->description }}</td>
                            <td>
                                {{ $expenseFee->categoryFee->name }}
                            </td>
                            <td>
                                {{ $expenseFee->categoryExpense->name }}
                            </td>
                            <td>
                                {{ format_fr_month_name($expenseFee->month) }}
                            </td>
                            <td class="text-end">
                                @if ($expenseFee->currency == 'USD')
                                    {{ app_format_number($expenseFee->amount, 1) }}
                                    {{ $expenseFee->currency }}
                                @else
                                    <span class="fw-bold">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($expenseFee->currency == 'CDF')
                                    {{ app_format_number($expenseFee->amount, 1) }}
                                    {{ $expenseFee->currency }}
                                @else
                                    <span class="fw-bold">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @can('manage-payment')
                                    <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                        class="btn-secondary btn-sm">
                                        <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer'
                                            href="#" data-bs-toggle="modal" data-bs-target="#form-expense-fee"
                                            wire:click='edit({{ $expenseFee }})' />
                                        <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                            href="#" wire:confirm="Voulez-vous vraiment supprimer ?"
                                            wire:click='delete({{ $expenseFee }})' />
                                    </x-others.dropdown>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
        <div class="mt-2">
            <span>{{ $expenseFees->links('livewire::bootstrap') }}</span>
        </div>
        <livewire:application.finance.expense.form.form-expense-page />
    </div>
</div>
