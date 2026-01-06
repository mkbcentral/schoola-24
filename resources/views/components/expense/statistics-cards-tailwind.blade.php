@props(['statistics' => []])

@php
    $loadingTargets = 'switchExpenseType,resetFilters,filterMonth,filterCurrency,filterCategoryExpense,filterCategoryFee,filterOtherSource,date,dateDebut,dateFin,dateRange';
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total USD -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 relative overflow-hidden">
        <div class="flex justify-between items-center">
            <div wire:loading.remove wire:target="{{ $loadingTargets }}">
                <h6 class="text-blue-100 text-sm font-medium mb-2">Total USD</h6>
                <h4 class="text-white text-3xl font-bold mb-0">{{ app_format_number($statistics['totalUSD'] ?? 0, 2) }} $</h4>
            </div>
            <div class="w-8 h-8 border-4 border-white border-t-transparent rounded-full animate-spin" 
                wire:loading wire:target="{{ $loadingTargets }}">
            </div>
            <div class="text-6xl text-white/20 absolute -right-4 -bottom-2">
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
    </div>

    <!-- Total CDF -->
    <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl shadow-sm p-6 relative overflow-hidden">
        <div class="flex justify-between items-center">
            <div wire:loading.remove wire:target="{{ $loadingTargets }}">
                <h6 class="text-cyan-100 text-sm font-medium mb-2">Total CDF</h6>
                <h4 class="text-white text-3xl font-bold mb-0">{{ app_format_number($statistics['totalCDF'] ?? 0, 0) }} FC</h4>
            </div>
            <div class="w-8 h-8 border-4 border-white border-t-transparent rounded-full animate-spin" 
                wire:loading wire:target="{{ $loadingTargets }}">
            </div>
            <div class="text-6xl text-white/20 absolute -right-4 -bottom-2">
                <i class="bi bi-cash"></i>
            </div>
        </div>
    </div>

    <!-- Total Converti USD -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm p-6 relative overflow-hidden">
        <div class="flex justify-between items-center">
            <div wire:loading.remove wire:target="{{ $loadingTargets }}">
                <h6 class="text-green-100 text-sm font-medium mb-2">Total (USD)</h6>
                <h4 class="text-white text-3xl font-bold mb-0">{{ app_format_number($statistics['totalUSDConverted'] ?? 0, 2) }} $</h4>
            </div>
            <div class="w-8 h-8 border-4 border-white border-t-transparent rounded-full animate-spin" 
                wire:loading wire:target="{{ $loadingTargets }}">
            </div>
            <div class="text-6xl text-white/20 absolute -right-4 -bottom-2">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
        </div>
    </div>

    <!-- Nombre de dépenses -->
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-sm p-6 relative overflow-hidden">
        <div class="flex justify-between items-center">
            <div wire:loading.remove wire:target="{{ $loadingTargets }}">
                <h6 class="text-yellow-100 text-sm font-medium mb-2">Nombre</h6>
                <h4 class="text-white text-3xl font-bold mb-0">{{ $statistics['count'] ?? 0 }}</h4>
            </div>
            <div class="w-8 h-8 border-4 border-white border-t-transparent rounded-full animate-spin" 
                wire:loading wire:target="{{ $loadingTargets }}">
            </div>
            <div class="text-6xl text-white/20 absolute -right-4 -bottom-2">
                <i class="bi bi-list-ol"></i>
            </div>
        </div>
    </div>
</div>
