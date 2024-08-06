@props(['iconLink' => '', 'labelText' => ''])
<a {{ $attributes->merge(['class' => 'dropdown-item']) }}>
    <i class="{{ $iconLink }}" aria-hidden="true"></i> {{ $labelText }}
</a>
