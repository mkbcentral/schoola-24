<div>
    <div class="d-flex justify-content-center pb-2">
        <x-widget.loading-circular-md wire:loading />
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
            <div class="d-flex align-items-center form-group">
                <x-form.label value="{{ __('Dévise') }}" class="fw-bold me-2" />
                <select id="my-select" class="form-control" name="" wire:model.live='currency_filter'>
                    <option disabled>Choisir</option>
                    <option value=''>Tout</option>
                    <option value="USD">USD</option>
                    <option value="CDF">CDF</option>
                </select>
            </div>
        </div>
        <h3 class="fw-bold text-secondary">
            Total: {{ app_format_number($total_usd, 1) }} $ | {{ app_format_number($total_cdf, 1) }}
            Fc
        </h3>
        <x-form.app-button wire:click='newSavingMoney' data-bs-toggle="modal" data-bs-target="#form-saving-money"
            textButton='Nouveau' icon="bi bi-plus-circle" class="btn-primary" />
    </div>
    <table class="table table-bordered table-hover table-sm mt-2">
        <thead class="table-primary">
            <tr class="">
                <th class="text-center">N°</th>
                <th>DATE OPERATION</th>
                <th>MOIS</th>
                <th class="text-end">MT USD</th>
                <th class="text-end">MT CDF</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @if ($savingMoneys->isEmpty())
            <tr>
                <td colspan="7"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($savingMoneys as $index => $savingMoney)
                    <tr wire:key='{{ $savingMoney->id }}' class=" ">
                        <td class="text-center ">
                            {{ $index + 1 }}
                        </td>
                        <td>{{ $savingMoney->created_at->format('d/m/Y') }}</td>
                        <td>
                            {{ format_fr_month_name($savingMoney->month) }}
                        </td>
                        <td class="text-end">
                            @if ($savingMoney->currency == 'USD')
                                {{ app_format_number($savingMoney->amount, 1) }}
                                {{ $savingMoney->currency }}
                            @else
                                <span class="fw-bold">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($savingMoney->currency == 'CDF')
                                {{ app_format_number($savingMoney->amount, 1) }}
                                {{ $savingMoney->currency }}
                            @else
                                <span class="fw-bold">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @can('manage-payment')
                                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                    class="btn-secondary btn-sm">
                                    <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer' href="#"
                                        data-bs-toggle="modal" data-bs-target="#form-saving-money"
                                        wire:click='edit({{ $savingMoney }})' />
                                    <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer' href="#"
                                        wire:confirm="Voulez-vous vraiment supprimer ?"
                                        wire:click='delete({{ $savingMoney }})' />
                                </x-others.dropdown>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
    @if ($savingMoneys->count() > 10)
        <div class="d-flex justify-content-between">
            <span>{{ $savingMoneys->links('livewire::bootstrap') }}</span>
            <x-others.table-page-number wire:model.live='per_page' />
        </div>
    @endif
    <livewire:application.finance.saving.form.form-saving-money-page />
</div>
