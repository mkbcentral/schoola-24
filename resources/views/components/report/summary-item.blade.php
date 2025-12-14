{{--
    Composant: Report Summary Item
    Usage: Pour afficher un item de résumé (total, devise, etc.)

    Props:
    - $label: Label du résumé
    - $value: Valeur à afficher
    - $type: Type de résumé (total, usd, cdf, eur) pour les couleurs
    - $badge: Badge optionnel (nombre de paiements, etc.)
--}}

@props(['label', 'value', 'type' => 'total', 'badge' => null])

<div class="summary-item summary-item-{{ $type }}">
    <p class="summary-label">{{ $label }}</p>
    <h3 class="summary-value">{{ $value }}</h3>
    @if ($badge)
        <span class="summary-badge">{{ $badge }}</span>
    @endif
</div>
