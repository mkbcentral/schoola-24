<div>
    <div class="d-flex justify-content-center pb-2">
        <x-widget.loading-circular-md wire:loading />
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center me-2">
                <x-form.label value="{{ __('Mois') }}" class="fw-bold me-2" />
                <x-widget.list-month-fr wire:model.live='month_filter' :error="'month_filter'" />
            </div>
            <div class="d-flex align-items-center  me-2">
                <x-form.label value="{{ __('Date') }}" class="fw-bold me-2" />
                <x-form.input type='date' wire:model.live='date_filter' :error="'date_filter'" />
            </div>
        </div>
        <h2 class="fw-bold">
            Total: {{ app_format_number($total_usd, 1) }} $ | {{ app_format_number($total_cdf, 1) }}
            Fc
        </h2>
        <x-form.app-button wire:click='newSalary' data-bs-toggle="modal" data-bs-target="#form-salary"
            textButton='Nouveau' icon="bi bi-plus-circle" class="btn-primary" />
    </div>
    <table class="table table-bordered table-hover mt-2">
        <thead class="table-primary">
            <tr class="">
                <th class="text-center">N°</th>
                <th>DATE OPERATION</th>
                <th>DESCRIPTION</th>
                <th class="text-end">MT USD</th>
                <th class="text-end">MT CDF</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @if ($salaries->isEmpty())
            <tr>
                <td colspan="7"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($salaries as $index => $salary)
                    <tr wire:key='{{ $salary->id }}' class=" ">
                        <td class="text-center ">
                            {{ $index + 1 }}
                        </td>
                        <td>{{ $salary->created_at->format('d/m/Y') }}</td>
                        <td>
                            <i class="bi bi-envelope-fill"></i> Enveloppe salariale/
                            {{ format_fr_month_name($salary->month) }}
                        </td>
                        <td class="text-end">
                            {{ app_format_number($salary->getAmount('USD'), 1) }}
                        </td>
                        <td class="text-end">
                            {{ app_format_number($salary->getAmount('CDF'), 1) }}
                        </td>
                        <td class="text-center">
                            @can('manage-payment')
                                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                    class="btn-secondary btn-sm">
                                    <x-others.dropdown-link iconLink='bi bi-clipboard-plus-fill' labelText='Ajouter détail'
                                        href="#" wire:click='edit({{ $salary }})' data-bs-toggle="modal"
                                        data-bs-target="#salary-detail-page" />
                                    <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Edit' href="#"
                                        wire:click='edit({{ $salary }})' data-bs-toggle="modal"
                                        data-bs-target="#form-salary" />
                                    <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer' href="#"
                                        wire:confirm="Voulez-vous vraiment supprimer ?"
                                        wire:click='delete({{ $salary }})' />
                                </x-others.dropdown>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
    @if ($salaries->count() > 10)
        <div class="d-flex justify-content-between">
            <span>{{ $salaries->links('livewire::bootstrap') }}</span>
            <x-others.table-page-number wire:model.live='per_page' />
        </div>
    @endif
    <livewire:application.finance.salary.form.form-salary-page />
    <livewire:application.finance.salary.list.list-salary-detail-page />
    <livewire:application.finance.salary.list.list-salary-detail-page>
</div>
