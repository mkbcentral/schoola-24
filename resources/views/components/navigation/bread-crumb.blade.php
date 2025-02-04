@props(['color' => '', 'icon' => '', 'label' => ''])

<div class="row mt-4">
    <div class="col-sm-6">
        <h3 class="mb-0 {{ $color }}"><i class="{{ $icon }}"></i> {{ $label }}</h3>
    </div>
    <div class="col-sm-6 d-flex justify-content-end">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                {{ $slot }}
            </ol>
        </nav>
    </div>
</div>
