@props(['statistics' => []])

@php
    $loadingTargets =
        'switchExpenseType,resetFilters,filterMonth,filterCurrency,filterCategoryExpense,filterCategoryFee,filterOtherSource,date,dateDebut,dateFin,dateRange';
@endphp

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary  position-relative">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div wire:loading.remove wire:target="{{ $loadingTargets }}">
                        <h6 class="text-white-50 mb-1">Total USD</h6>
                        <h4 class="mb-0">{{ app_format_number($statistics['totalUSD'] ?? 0, 2) }} $</h4>
                    </div>
                    <div class="spinner-border " wire:loading wire:target="{{ $loadingTargets }}">
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info  position-relative">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div wire:loading.remove wire:target="{{ $loadingTargets }}">
                        <h6 class="-50 mb-1">Total CDF</h6>
                        <h4 class="mb-0">{{ app_format_number($statistics['totalCDF'] ?? 0, 0) }} FC</h4>
                    </div>
                    <div class="spinner-border " wire:loading wire:target="{{ $loadingTargets }}">
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-cash"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success  position-relative">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div wire:loading.remove wire:target="{{ $loadingTargets }}">
                        <h6 class="text-white-50 mb-1">Total (USD)</h6>
                        <h4 class="mb-0">{{ app_format_number($statistics['totalUSDConverted'] ?? 0, 2) }} $</h4>
                    </div>
                    <div class="spinner-border text-white" wire:loading wire:target="{{ $loadingTargets }}">
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white position-relative">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div wire:loading.remove wire:target="{{ $loadingTargets }}">
                        <h6 class="text-white-50 mb-1">Nombre</h6>
                        <h4 class="mb-0">{{ $statistics['count'] ?? 0 }}</h4>
                    </div>
                    <div class="spinner-border text-white" wire:loading wire:target="{{ $loadingTargets }}">
                    </div>
                    <div class="fs-1">
                        <i class="bi bi-list-ol"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
