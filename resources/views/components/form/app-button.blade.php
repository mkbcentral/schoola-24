@props(['onPressed' => '', 'textButton' => '', 'icon' => '', 'tooltipText' => 'Action'])
<button {{ $attributes->merge(['class' => 'btn ']) }} data-bs-toggle="tooltip" data-bs-placement="bottom"
    data-bs-title="{{ $tooltipText }}">
    <span>
        <i class="{{ $icon }}" aria-hidden="true"></i> {{ $textButton }}
    </span>
</button>
