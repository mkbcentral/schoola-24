@props(['color' => '', 'icon' => '', 'label' => ''])

<div class="row align-items-center mt-4">
    <div class="col-12 col-md-6 mb-2 mb-md-0">
        <h3 class="mb-0 fw-bold text-secondary d-flex align-items-center {{ $color }}">
            @if ($icon)
                <i class="{{ $icon }} me-2"></i>
            @endif
            {{ $label }}
        </h3>
    </div>
    <div class="col-12 col-md-6 d-flex justify-content-start justify-content-md-end">
        <nav aria-label="breadcrumb" class="w-100">
            <ol class="breadcrumb bg-transparent p-0 m-0">
                {{ $slot }}
            </ol>
        </nav>
    </div>
</div>
