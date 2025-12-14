{{-- 
=============================================================================
MINI-CARDS - Guide d'utilisation
=============================================================================

Les mini-cards sont de petits cadres élégants et modernes pour afficher des 
statistiques, KPIs ou informations importantes.

=============================================================================
EXEMPLES D'UTILISATION
=============================================================================
--}}

{{-- Grid de mini-cards --}}
<div class="mini-cards-grid">

    {{-- Mini-card basique --}}
    <x-cards.mini-card title="Total Élèves" value="1,234" icon="bi bi-people-fill" variant="primary" />

    {{-- Mini-card avec badge --}}
    <x-cards.mini-card title="Paiements" value="567" icon="bi bi-cash-coin" variant="success" badge="+12%" />

    {{-- Mini-card avec gradient --}}
    <x-cards.mini-card title="Revenus" value="$12,345" icon="bi bi-graph-up-arrow" :gradient="true" />

    {{-- Mini-card warning --}}
    <x-cards.mini-card title="En attente" value="45" icon="bi bi-clock-history" variant="warning" />

    {{-- Mini-card danger --}}
    <x-cards.mini-card title="Impayés" value="23" icon="bi bi-exclamation-triangle" variant="danger" />

    {{-- Mini-card info --}}
    <x-cards.mini-card title="Notifications" value="89" icon="bi bi-bell-fill" variant="info" />
</div>

{{-- Mini-cards compacts --}}
<div class="mini-cards-grid">
    <x-cards.mini-card title="Aujourd'hui" value="156" icon="bi bi-calendar-check" variant="primary"
        :compact="true" />

    <x-cards.mini-card title="Cette semaine" value="892" icon="bi bi-calendar-week" variant="success"
        :compact="true" />

    <x-cards.mini-card title="Ce mois" value="3,421" icon="bi bi-calendar-month" variant="info" :compact="true" />
</div>

{{-- Mini-card avec contenu personnalisé --}}
<div class="mini-cards-grid">
    <x-cards.mini-card title="Taux de présence" value="94.5%" icon="bi bi-check-circle-fill" variant="success">
        <small class="text-muted">+2.3% vs mois dernier</small>
    </x-cards.mini-card>
</div>

{{-- 
=============================================================================
VARIANTES DISPONIBLES
=============================================================================

Variants (couleurs):
- primary (bleu)
- success (vert)
- warning (orange)
- danger (rouge)
- info (cyan)

Options:
- gradient: true/false (gradient coloré)
- compact: true/false (version compacte)
- badge: "texte" (badge en haut à droite)

=============================================================================
CLASSES CSS DISPONIBLES
=============================================================================

Classes principales:
- .mini-card (base)
- .mini-card-primary
- .mini-card-success
- .mini-card-warning
- .mini-card-danger
- .mini-card-info
- .mini-card-gradient
- .mini-card-compact

Éléments internes:
- .mini-card-icon (icône)
- .mini-card-title (titre)
- .mini-card-value (valeur principale)
- .mini-card-badge (badge)

Layout:
- .mini-cards-grid (grid responsive)

=============================================================================
--}}
