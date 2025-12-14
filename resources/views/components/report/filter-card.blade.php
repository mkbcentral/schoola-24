{{--
    Composant: Report Filter Card
    Usage: Pour afficher une carte de filtres dans les pages de rapports

    Props:
    - $title: Titre de la section de filtres (optionnel, défaut: "Filtres & Paramètres")
--}}

@props(['title' => 'Filtres & Paramètres'])

<div class="report-filter-card">
    <h6 class="filter-header">{{ $title }}</h6>
    <div class="row">
        {{ $slot }}
    </div>
</div>
