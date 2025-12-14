@props(['active', 'linkLabel' => '', 'icon' => ''])
@php
    $classes = $active ?? false ? 'active' : '';
@endphp
<li class="">
    <a {{ $attributes->merge(['class' => $classes]) }} class="" data-label="{{ $linkLabel }}">
        <i class="{{ $icon }}"></i>
        <span class="nav-text">{{ $linkLabel }}</span>
    </a>
</li>
