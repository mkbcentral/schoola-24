<div>
    @php
        $total_cdf = 0;
        $total_usd = 0;
    @endphp
    <x-modal.build-modal-fixed idModal='dialog-other-expense' size='fullscreen' headerLabel="AUTRES DEPENSES ANNUELLES"
        headerLabelIcon="bi bi-person-fill-add">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex ">
                    <div class="d-flex align-items-center  me-2">
                        <x-form.label value="{{ __('Source') }}" class="fw-bold me-2" />
                        <x-widget.data.list-other-source-expense type='text'
                            wire:model.live='categoryExpenseIdFilter' :error="'categoryExpenseIdFilter'" />
                    </div>
                    <div class="d-flex align-items-center  me-2">
                        <x-form.label value="{{ __('Categorie') }}" class="fw-bold me-2" />
                        <x-widget.data.list-category-expense type='text' wire:model.live='otherSourceExpenseIdFilter'
                            :error="'otherSourceExpenseIdFilter'" />
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive table-sm">
                    <table class="table financial-summary">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th class="text-end">Total CDF</th>
                                <th class="text-end">Total USD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($expenses->isEmpty())
                                <tr>
                                    <td colspan='3'> <x-errors.data-empty /></td>
                                </tr>
                            @else
                                @foreach ($expenses as $expense)
                                    <tr>
                                        <td class=" text-uppercase">{{ format_fr_month_name($expense->month) }}</td>
                                        <td class="text-end fw-bold">
                                            {{ app_format_number($expense->total_cdf, 1) }}
                                        </td>
                                        <td class="text-end fw-bold">
                                            {{ app_format_number($expense->total_usd, 1) }}
                                        </td>
                                    </tr>
                                    @php
                                        $total_cdf += $expense->total_cdf;
                                        $total_usd += $expense->total_usd;
                                    @endphp
                                @endforeach
                                <tr class="bg-dark text-uppercase h3 fw-bold">
                                    <td class="text-end">Total</td>
                                    <td class="text-end">{{ app_format_number($total_cdf, 1) }}</td>
                                    <td class="text-end">{{ app_format_number($total_usd, 1) }}</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-modal.build-modal-fixed>
</div>
