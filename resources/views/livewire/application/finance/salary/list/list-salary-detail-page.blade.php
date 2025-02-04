<div>
    <x-modal.build-modal-fixed idModal='salary-detail-page' size='xl' headerLabel="DETAIL SITUATION SALIRE"
        headerLabelIcon='bi bi-arrow-left-right'>
        <div class="d-flex justify-content-center pb-2">
            <x-widget.loading-circular-md wire:loading />
        </div>
        @if ($salary != null)
            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex justify-content-center pb-2">
                        <x-widget.loading-circular-md wire:loading wire:target='getRegistration' />
                    </div>
                    <div class="card p-2">
                        <div>
                            <span>
                                <i class="bi bi-envelope-fill"></i>
                                <span>Enveloppe salariale/ </span>
                                <span>{{ format_fr_month_name($salary->month) }}</span>
                            </span>
                        </div>
                        <div>
                            <span class="fw-bold">Date:</span>
                            <span>{{ $salary->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="card mt-2">
                        <div class="card-header text-uppercase">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5> DETAILS SALAIRE</h5>
                                <h5> Total: {{ app_format_number($salary->getAmount('USD'), 1) }} $ |
                                    {{ app_format_number($salary->getAmount('CDF'), 1) }} Fc</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-sm table-hover mt-2">
                                <thead class="table-primary">
                                    <tr class="">
                                        <th class="text-center">N°</th>
                                        <th class="">DESCRIPTION</th>
                                        <th class="text-end">M.T USD</th>
                                        <th class="text-end">M.T CDF</th>
                                        <th class="text-center">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salary->salaryDetails as $index => $salaryDetail)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $salaryDetail->description }}</td>
                                            <td class="text-end">
                                                @if ($salaryDetail->currency == 'USD')
                                                    {{ app_format_number($salaryDetail->amount, 1) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if ($salaryDetail->currency == 'CDF')
                                                    {{ app_format_number($salaryDetail->amount, 1) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <x-form.app-button type='button' icon="bi bi-pencil-fill"
                                                    class="btn-sm btn-primary" wire:click='edit({{ $salaryDetail }})' />
                                                <x-form.app-button type='button' icon="bi bi-trash-fill"
                                                    class="btn-danger btn-sm"
                                                    wire:confirm='Etês-vous sûr de supprimer ?'
                                                    wire:click='delete({{ $salaryDetail }})' />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mt-2">
                        <h5><i
                                class="{{ $salaryDetailToEdit == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}"></i>
                            {{ $salaryDetailToEdit == null ? 'CREATION' : 'EDITION' }} DETAILS
                        </h5>
                        <hr>
                        <form wire:submit='handlerSubmit'>
                            <div>
                                <x-form.label value="{{ __('Dévise') }}" class="fw-bold" />
                                <x-widget.currency-widget wire:model.blur='form.currency' :error="'form.currency'" />
                                <x-errors.validation-error value='form.currency' />
                            </div>
                            <div class="mt-2">
                                <x-form.label value="{{ __('Categorie') }}" />
                                <x-widget.data.list-cat-salary wire:model.blur='form.category_salary_id'
                                    :error="'form.category_salary_id'" />
                                <x-errors.validation-error value='form.category_salary_id' />
                            </div>
                            <div class="mt-2">
                                <x-form.label value="{{ __('Description') }}" />
                                <x-form.input type='text' wire:model.blur='form.description' :error="'form.description'" />
                                <x-errors.validation-error value='form.description' />
                            </div>
                            <div class="mt-2">
                                <x-form.label value="{{ __('Monatnt') }}" />
                                <x-form.input type='text' wire:model.blur='form.amount' :error="'form.amount'" />
                                <x-errors.validation-error value='form.amount' />
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <x-form.app-button type='submit' textButton="Sauvegarder" icon="bi bi-arrow-left-righ"
                                    class="btn-primary" />
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        @endif
    </x-modal.build-modal-fixed>
</div>
