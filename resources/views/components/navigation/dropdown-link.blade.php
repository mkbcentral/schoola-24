@props(['label' => '', 'idItem' => '', 'icon' => ''])
<li class="nav-item dropdown">
    <a href="#{{ $idItem }}" data-bs-toggle="collapse" class="nav-link dropdown-toggle d-flex align-items-center"
        aria-expanded="false" data-label="{{ $label }}">
        @if ($icon)
            <i class="{{ $icon }}"></i>
        @endif
        <span class="nav-text">{{ $label }}</span>
        <span class="dropdown-arrow">
            <i class="bi bi-chevron-down"></i>
        </span>
    </a>
    <ul class="list-unstyled collapse dropdown-submenu" id="{{ $idItem }}">
        {{ $slot }}
    </ul>
</li>
