@props(['icon' => '', 'link' => '#', 'textLabel' => ''])
<a href="{{ route($link) }}" {{ $attributes->merge(['class' => 'btn btn-sm']) }}>
    <i class="{{ $icon }}"></i> {{ $textLabel }}
</a>
