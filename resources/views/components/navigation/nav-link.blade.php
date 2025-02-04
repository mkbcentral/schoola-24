@props(['active', 'linkLabel' => '', 'icon' => ''])
@php
    $classes = $active ?? false ? 'active' : '';
@endphp
<li class="">
    <a {{ $attributes->merge(['class' => $classes]) }} class="">
        <i class="{{ $icon }}"></i> {{ $linkLabel }}
    </a>
</li>
