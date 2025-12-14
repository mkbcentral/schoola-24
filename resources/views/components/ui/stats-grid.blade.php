@props(['columns' => 4, 'gap' => 3])

<div class="row g-{{ $gap }} mb-3">
    {{ $slot }}
</div>
