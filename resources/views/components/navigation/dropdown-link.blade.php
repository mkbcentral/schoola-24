@props(['label' => '', 'idItem' => '', 'icon' => ''])
<li class="nav-item dropdown">
    <a href="#{{ $idItem }}" data-bs-toggle="collapse" class="nav-link dropdown-toggle d-flex align-items-center"
        aria-expanded="false">
        @if ($icon)
            <i class="{{ $icon }} me-2"></i>
        @endif
        <span>{{ $label }}</span>
        <span class="ms-auto">
            <i class="bi bi-chevron-down transition"></i>
        </span>
    </a>
    <ul class="list-unstyled collapse ps-4" id="{{ $idItem }}">
        {{ $slot }}
    </ul>
</li>
