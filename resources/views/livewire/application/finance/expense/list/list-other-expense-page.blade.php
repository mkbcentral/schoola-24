<div>
    <div class="d-flex justify-content-center pb-2">
        <x-widget.loading-circular-md wire:loading />
    </div>
    <div class="d-flex justify-content-end">
        <h2 class="fw-bold">
            Total: {{ app_format_number($total_usd, 1) }} $ | {{ app_format_number($total_cdf, 1) }}
            Fc
        </h2>
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
                <x-form.label value="{{ __('Source') }}" class="fw-bold me-2" />
                <x-widget.data.list-other-source-expense type='text' wire:model.live='other_source_expense_id_filter'
                                                         :error="'other_source_expense_id_filter'" />
            </div>
            <div class="d-flex align-items-center  me-2">
                <x-form.label value="{{ __('Categorie') }}" class="fw-bold me-2" />
                <x-widget.data.list-category-expense type='text' wire:model.live='category_expense_id_filter'
                                                     :error="'category_expense_id_filter'" />
            </div>
            <div class="d-flex align-items-center form-group">
                <x-form.label value="{{ __('Dévise') }}" class="fw-bold me-2" />
                <select id="my-select" class="form-control form-select" name="" wire:model.live='currency_filter'>
                    <option disabled>Choisir</option>
                    <option value=''>Tout</option>
                    <option value="USD">USD</option>
                    <option value="CDF">CDF</option>
                </select>
            </div>

        </div>
        @can('view-payment')
            <x-form.app-button
                wire:click='newOtherExpenseFee'
                data-bs-toggle="modal" data-bs-target="#form-other-expense-fee"
                textButton='Nouveau' icon="bi bi-plus-circle" class="app-btn" />
        @endcan
    </div>
    <table class="table table-bordered table-hover mt-2">
        <thead class="table-primary">
            <tr class="">
                <th class="text-center">N°</th>
                <th>DATE</th>
                <th>DESCRIPTION</th>
                <th>Source</th>
                <th>Cetegoie</th>
                <th>MOIS</th>
                <th class="text-end">MT USD</th>
                <th class="text-end">MT CDF</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @if ($otherExpenses->isEmpty())
            <tr>
                <td colspan="9"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($otherExpenses as $index => $otherExpense)
                    <tr wire:key='{{ $otherExpense->id }}' class=" ">
                        <td class="text-center ">
                            {{ $index + 1 }}
                        </td>
                        <td>{{ $otherExpense->created_at->format('d/m/Y') }}</td>
                        <td>{{ $otherExpense->description }}</td>
                        <td>
                            {{ $otherExpense->otherSourceExpense->name }}
                        </td>
                        <td>
                            {{ $otherExpense->categoryExpense->name }}
                        </td>
                        <td>
                            {{ format_fr_month_name($otherExpense->month) }}
                        </td>
                        <td class="text-end">
                            @if ($otherExpense->currency == 'USD')
                                {{ app_format_number($otherExpense->amount, 1) }}
                                {{ $otherExpense->currency }}
                            @else
                                <span class="fw-bold">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($otherExpense->currency == 'CDF')
                                {{ app_format_number($otherExpense->amount, 1) }}
                                {{ $otherExpense->currency }}
                            @else
                                <span class="fw-bold">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @can('manage-payment')
                                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                    class="btn-secondary btn-sm">
                                    <x-others.dropdown-link
                                        iconLink='bi bi-pencil-fill' labelText='Editer' href="#"
                                        data-bs-toggle="modal" data-bs-target="#form-other-expense-fee"
                                        wire:click='edit({{ $otherExpense }})' />
                                    <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer' href="#"
                                        wire:confirm="Voulez-vous vraiment supprimer ?"
                                        wire:click='delete({{ $otherExpense }})' />
                                </x-others.dropdown>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
    <span>{{ $otherExpenses->links('livewire::bootstrap') }}</span>
    <livewire:application.finance.expense.form.form-other-expense-page />
</div>
