<div class="row g-3">
    <x-v2.mini-stat-card
        title="Total des écoles"
        :value="$stats['total_schools'] ?? 0"
        icon="bi-building"
        color="primary" />

    <x-v2.mini-stat-card
        title="Écoles actives"
        :value="$stats['active_schools'] ?? 0"
        icon="bi-check-circle"
        color="success" />

    <x-v2.mini-stat-card
        title="Écoles inactives"
        :value="$stats['inactive_schools'] ?? 0"
        icon="bi-x-circle"
        color="warning" />

    <x-v2.mini-stat-card
        title="Utilisateurs total"
        :value="$stats['total_users'] ?? 0"
        icon="bi-people"
        color="info" />
</div>
