@props(['active', 'linkLabel' => '', 'icon' => ''])
@php
    $classes = $active ?? false ? 'nav-link bg-warning text-dark fw-bolder' : 'nav-link text text-white';
@endphp
<li class="nav-item ">
    <a {{ $attributes->merge(['class' => $classes]) }} class="nav-link">
        <i class="{{ $icon }}"></i>
        <p>{{ $linkLabel }}</p>
    </a>
</li>
