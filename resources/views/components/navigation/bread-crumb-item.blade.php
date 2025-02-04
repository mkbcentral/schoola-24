@props(['isLinked' => false, 'label' => '', 'link' => ''])
@if (!$isLinked)
    <li class="breadcrumb-item active h6"> <span>{{ $label }}</span></li>
@else
    <li class="breadcrumb-item h6"><a href="{{ route($link) }}">{{ $label }}</a></li>
@endif
