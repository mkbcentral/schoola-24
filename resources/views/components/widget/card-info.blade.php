@props([
    'label' => '',
    'icon' => '',
    'value' => '',
    'link' => '',
    'linkLabel' => '',
    'bg' => '',
    'iconColor' => '',
])
<a @if ($link == '') href="#" @else href="{{ route($link) }}" @endif {{ $attributes }}
    class="text-decoration-none">
    <div class="card ">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-title text-uppercase">{{ $label }}</h6>
                    <h2 class="mb-0">{{ $value }}</h2>
                </div>
                <i class="bi {{ $icon }} display-4 text-{{ $iconColor }}"></i>
            </div>
            <small class="d-block mt-2">
                <i class="bi bi-arrow-right"></i>{{ $linkLabel }}
            </small>
        </div>
    </div>
</a>
