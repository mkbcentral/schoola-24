@props(['color' => '', 'icon' => '', 'label' => ''])

<div class="container-fluid d-flex justify-content-between align-items-center mb-3 pt-2">
    <div class="mb-2 mb-md-0">
        <h3 class="mb-0 fw-bold text-secondary d-flex align-items-center {{ $color }}">
            @if ($icon)
                <i class="{{ $icon }} me-2"></i>
            @endif
            {{ $label }}
        </h3>
    </div>
    <div class=" d-flex justify-content-center justify-content-md-end">
        <nav aria-label="breadcrumb" class="w-100">
            <ol class="breadcrumb bg-transparent p-0 m-0">
                {{ $slot }}
            </ol>
        </nav>
    </div>
</div>
