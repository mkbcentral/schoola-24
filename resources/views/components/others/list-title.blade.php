@props(['title' => '', 'icon' => 'bi bi-card-checklist', 'color' => ''])
<h4 class="text-uppercase {{ $color }}">
    <i class="{{ $icon }}" aria-hidden="true"></i>
    <span>{{ $title }}</span>
</h4>
