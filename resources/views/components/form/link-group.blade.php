@props(['icon' => '', 'label' => '', 'link' => '', 'params' => []])

<a {{ $attributes->merge(['class' => 'dropdown-item']) }}>
    <i class="{{ $icon }}"></i>
    {{ $label }}
</a>
