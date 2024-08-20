@props(['label' => '', 'icon' => ''])

<li class="nav-item ">
    <a href="#" class="nav-link ">
        <i class="nav-icon {{ $icon }}"></i>
        <p>
            {{ $label }}
            <i class="nav-arrow bi bi-chevron-right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="box-sizing: border-box; display: none; background-color: #020410;">
        {{ $slot }}
    </ul>
</li>
