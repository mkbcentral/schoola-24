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
    <table class="table table-bordered table-hover mt-2">
        <thead class="table-primary">
            <tr class="">
                <th class="text-center">N°</th>
                <th>DATE OPERATION</th>
                <th>DESCRIPTION</th>
                <th>MOIS</th>
                <th class="text-end">MT USD</th>
                <th class="text-end">MT CDF</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @if ($moneyBorrowings->isEmpty())
            <tr>
                <td colspan="7"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($moneyBorrowings as $index => $moneyBorrowing)
                    <tr wire:key='{{ $moneyBorrowing->id }}' class=" ">
                        <td class="text-center ">
                            {{ $index + 1 }}
                        </td>
                        <td>{{ $moneyBorrowing->created_at->format('d/m/Y') }}</td>
                        <td>{{ $moneyBorrowing->description }}</td>
                        <td>
                            {{ format_fr_month_name($moneyBorrowing->month) }}
                        </td>
                        <td class="text-end">
                            @if ($moneyBorrowing->currency == 'USD')
                                {{ app_format_number($moneyBorrowing->amount, 1) }}
                                {{ $moneyBorrowing->currency }}
                            @else
                                <span class="fw-bold">-</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($moneyBorrowing->currency == 'CDF')
                                {{ app_format_number($moneyBorrowing->amount, 1) }}
                                {{ $moneyBorrowing->currency }}
                            @else
                                <span class="fw-bold">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @can('manage-payment')
                                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                    class="btn-secondary btn-sm">
                                    <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer' href="#"
                                        wire:click='edit({{ $moneyBorrowing }})' />
                                    <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer' href="#"
                                        wire:confirm="Voulez-vous vraiment supprimer ?"
                                        wire:click='delete({{ $moneyBorrowing }})' />
                                </x-others.dropdown>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
    <div class="d-flex justify-content-between">
        <span>{{ $moneyBorrowings->links('livewire::bootstrap') }}</span>
        <x-others.table-page-number wire:model.live='per_page' />
    </div>
</div>
