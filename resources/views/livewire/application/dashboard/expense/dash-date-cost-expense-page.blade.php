<div>
    <div class="card">
        <div class="card-header ">
            <h4 class="text-uppercase text-secondary">Dépenses journ. sur le frais scloaraire</h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center me-2">
                    <x-form.label value="{{ __('Date') }}" class="me-2" />
                    <x-form.input type='date' wire:model.live='date_filter' :error="'date_filter'" />
                </div>
                <div class="d-flex align-items-center  me-2">
                    <x-form.label value="{{ __('Categorie Frais') }}" class="fw-bold me-2" />
                    <x-widget.data.list-cat-scolar-fee type='text' wire:model.live='category_fee_filter'
                        :error="'category_fee_filter'" />
                </div>
                <div class="d-flex align-items-center  me-2">
                    <x-form.label value="{{ __('Type dépenses') }}" class="fw-bold me-2" />
                    <x-widget.data.list-category-expense type='text' wire:model.live='category_expense_id_filter'
                        :error="'category_expense_id_filter'" />

                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="card bg-light shadow-sm border-0">
                        <div class="card-body h-4 align-self-center">
                            <h5 class="card-title">USD {{ app_format_number($expense->total_usd ?? 0, 1) }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-light shadow-sm border-0">
                        <div class="card-body h-4  align-self-center">
                            <h5 class="card-title">CDF {{ app_format_number($expense->total_cdf ?? 0, 1) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
