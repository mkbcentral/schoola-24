{{--
    Composant: Report Summary Card
    Usage: Pour afficher une carte de résumé financier

    Props:
    - $title: Titre de la section (optionnel, défaut: "Résumé Financier")
--}}

@props(['title' => 'Résumé Financier'])

<div class="report-summary-card">
    <h6 class="summary-header">{{ $title }}</h6>
    {{ $slot }}
</div>
