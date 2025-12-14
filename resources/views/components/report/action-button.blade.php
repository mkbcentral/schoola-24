{{--
    Composant: Report Action Button
    Usage: Bouton d'action pour les rapports (email, télécharger, aperçu)

    Props:
    - $type: Type de bouton (email, download, preview)
    - $icon: Icône Bootstrap (défaut selon le type)
    - $label: Texte du bouton
    - $href: Lien (optionnel)
    - $target: Target du lien (optionnel)
--}}

@props([
    'type' => 'download',
    'icon' => null,
    'label' => 'Action',
    'href' => null,
    'target' => null,
])

@php
    $defaultIcons = [
        'email' => 'bi-envelope',
        'download' => 'bi-download',
        'preview' => 'bi-eye',
    ];

    $iconClass = $icon ?? ($defaultIcons[$type] ?? 'bi-gear');
    $btnClass = "report-action-btn report-action-{$type}";
@endphp

@if ($href)
    <a href="{{ $href }}" @if ($target) target="{{ $target }}" @endif
        class="{{ $btnClass }}" {{ $attributes }}>
        <i class="bi {{ $iconClass }}"></i>{{ $label }}
    </a>
@else
    <button class="{{ $btnClass }}" {{ $attributes }}>
        <i class="bi {{ $iconClass }}"></i>{{ $label }}
    </button>
@endif
