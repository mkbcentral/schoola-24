@props(['title', 'value', 'icon', 'color' => 'primary'])

<div class="col-md-3">
    <div class="card border-{{ $color }}">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">{{ $title }}</h6>
                    <h3 class="mb-0">{{ $value }}</h3>
                </div>
                <div class="bg-{{ $color }} bg-opacity-10 p-3 rounded">
                    <i class="bi {{ $icon }} fs-4 text-{{ $color }}"></i>
                </div>
            </div>
        </div>
    </div>
</div>
