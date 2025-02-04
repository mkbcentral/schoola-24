@props(['label' => '', 'idItem' => '', 'icon' => ''])
<li>
    <a href="#{{ $idItem }}" data-bs-toggle="collapse" class="dropdown-toggle" aria-expanded="true">
        <i class="{{ $icon }} me-2"></i> {{ $label }}
    </a>
    <ul class="list-unstyled collapse " id="{{ $idItem }}" style="">
        {{ $slot }}
    </ul>
</li>
