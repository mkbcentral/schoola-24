<div>
    <div class="card">
        <div class="card-header">
            <h4 class="text-uppercase text-secondary">Autres dépenses journ.</h4>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center">
                        <x-form.label value="{{ __('Date') }}" class="fw-bold me-2" />
                        <x-form.input type='date' wire:model.live='date_filter' :error="'date_filter'" />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center">
                        <x-form.label value="{{ __('Source') }}" class="fw-bold me-2" />
                        <x-widget.data.list-other-source-expense type='text'
                            wire:model.live='otherSourceExpenseIdFilter' :error="'otherSourceExpenseIdFilter'" />
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center">
                        <x-form.label value="{{ __('Categorie') }}" class="fw-bold me-2" />
                        <x-widget.data.list-category-expense type='text' wire:model.live='categoryExpenseIdFilter'
                            :error="'categoryExpenseIdFilter'" />
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <div class="card border-0 shadow-sm  h-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <span class="text-muted fw-semibold">Total USD</span>
                            <h4 class="card-title text-primary mt-2 mb-0">
                                $ {{ app_format_number($expense->total_usd ?? 0, 1) }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm  h-100">
                        <div class="card-body d-flex flex-column justify-content-center align-items-center py-4">
                            <span class="text-muted fw-semibold">Total CDF</span>
                            <h4 class="card-title text-success mt-2 mb-0">
                                CDF {{ app_format_number($expense->total_cdf ?? 0, 1) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
