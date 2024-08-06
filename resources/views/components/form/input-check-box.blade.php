@props([
    'color' => '',
    'label' => '',
    'icon' => '',
    'idAndFor' => '',
    'text' => '',
    'textColor' => '',
])
<div class="my-2">
    <span class="text-bold text-md text-{{ $color }} mr-2">
        <i class="{{ $icon }}" aria-hidden="true"></i>
        {{ $label }}
    </span>
    <div class="icheck-{{ $color }} d-inline">
        <input {{ $attributes }} type="checkbox" id="{{ $idAndFor }}">
        <label for="{{ $idAndFor }}" class="{{ $textColor }} fw-bold">
            {{ $text }}
        </label>
    </div>
</div>
