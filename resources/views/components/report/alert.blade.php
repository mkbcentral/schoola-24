{{--
    Composant: Report Alert
    Usage: Afficher des alertes dans les pages de rapports

    Props:
    - $type: Type d'alerte (error, warning, info)
    - $title: Titre de l'alerte (optionnel)
--}}

@props(['type' => 'info', 'title' => null])

<div class="report-alert report-alert-{{ $type }}">
    <p>
        @if ($title)
            <strong>{{ $title }}:</strong>
        @endif
        {{ $slot }}
    </p>
</div>
