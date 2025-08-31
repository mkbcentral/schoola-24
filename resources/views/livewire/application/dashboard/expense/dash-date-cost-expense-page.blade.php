<div>
    <div class="card shadow-lg border-0">
        <div class="card-header">
            <h4 class="text-uppercase mb-0 fs-6 fs-md-5">Dépenses journ. sur le frais scolaire</h4>
        </div>
        <div class="card-body">
            <form class="row g-3 align-items-end mb-4">
                <div class="col-md-4">
                    <x-form.label value="{{ __('Date') }}" class="form-label fw-semibold" />
                    <x-form.input type="date" wire:model.live="date_filter" :error="'date_filter'" class="form-control" />
                </div>
                <div class="col-md-4">
                    <x-form.label value="{{ __('Categorie Frais') }}" class="form-label fw-semibold" />
                    <x-widget.data.list-cat-scolar-fee type="text" wire:model.live="category_fee_filter"
                        :error="'category_fee_filter'" class="form-select" />
                </div>
                <div class="col-md-4">
                    <x-form.label value="{{ __('Type dépenses') }}" class="form-label fw-semibold" />
                    <x-widget.data.list-category-expense type="text" wire:model.live="category_expense_id_filter"
                        :error="'category_expense_id_filter'" class="form-select" />
                </div>
            </form>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 bg-success bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <span class="text-success fw-semibold mb-2 d-block">Total USD</span>
                            <h3 class="card-title text-success mb-0">
                                $ {{ app_format_number($expense->total_usd ?? 0, 1) }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 bg-primary bg-opacity-10 h-100">
                        <div class="card-body text-center">
                            <span class="text-primary fw-semibold mb-2 d-block">Total CDF</span>
                            <h3 class="card-title text-primary mb-0">
                                CDF {{ app_format_number($expense->total_cdf ?? 0, 1) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
