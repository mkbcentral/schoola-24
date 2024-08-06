@props(['isLinked' => false, 'label' => '', 'link' => ''])
@if ($isLinked == false)
    <li class="breadcrumb-item active">{{ $label }}</li>
@else
    <li class="breadcrumb-item "><a href="{{ route($link) }}" wire:navigate>{{ $label }}</a></li>
@endif
