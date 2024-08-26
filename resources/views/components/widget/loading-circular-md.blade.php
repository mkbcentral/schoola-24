@props(['color' => 'text-danger', 'size' => 'sm'])
<span {!! $attributes->merge(['class' => 'spinner-border spinner-border-{{ $size }}']) !!} role="status" aria-hidden="true"></span>
