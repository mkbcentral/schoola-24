@props(['icon' => '', 'name' => '', 'link' => '', 'active' => ''])
<li class="nav-item">
    <a class="nav-link {{ $active }}" href="#{{ $link }}" data-toggle="tab">
        <i class="{{ $icon }}"></i> {{ $name }}
    </a>
</li>
