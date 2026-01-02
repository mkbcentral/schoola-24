{{-- 
    Exemple de lien de navigation pour la page de paiement V3
    Ajoutez ce code dans votre menu de navigation principal
--}}

<!-- Option 1 : Lien simple dans un menu -->
<li class="nav-item">
    <a href="{{ route('v3.payment.manage') }}" class="nav-link">
        <i class="bi bi-credit-card-2-front me-2"></i>
        <span>Paiements V3</span>
    </a>
</li>

<!-- Option 2 : Avec badge "Nouveau" -->
<li class="nav-item">
    <a href="{{ route('v3.payment.manage') }}" class="nav-link d-flex align-items-center justify-content-between">
        <span>
            <i class="bi bi-credit-card-2-front me-2"></i>
            Paiements V3
        </span>
        <span class="badge bg-success">Nouveau</span>
    </a>
</li>

<!-- Option 3 : Dans un dropdown avec l'ancienne version -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
        <i class="bi bi-cash-stack me-2"></i>
        Paiements
    </a>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="{{ route('payment.quick') }}">
                <i class="bi bi-lightning me-2"></i>
                Paiement Rapide (V2)
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('v3.payment.manage') }}">
                <i class="bi bi-credit-card-2-front me-2"></i>
                Gestion Paiements (V3)
                <span class="badge bg-success ms-2">Nouveau</span>
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item" href="{{ route('payment.list') }}">
                <i class="bi bi-list-ul me-2"></i>
                Liste des paiements
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('report.payments') }}">
                <i class="bi bi-file-earmark-bar-graph me-2"></i>
                Rapport de paiements
            </a>
        </li>
    </ul>
</li>

<!-- Option 4 : Sidebar avec style moderne -->
<div class="sidebar-item">
    <a href="{{ route('v3.payment.manage') }}" class="sidebar-link {{ request()->routeIs('v3.payment.*') ? 'active' : '' }}">
        <div class="d-flex align-items-center">
            <div class="icon-wrapper me-3">
                <i class="bi bi-credit-card-2-front fs-5"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-semibold">Paiements V3</div>
                <small class="text-muted">Nouvelle interface</small>
            </div>
            @if(request()->routeIs('v3.payment.*'))
                <i class="bi bi-chevron-right text-primary"></i>
            @endif
        </div>
    </a>
</div>

{{-- 
    Styles recommandés pour l'option 4 (à ajouter dans votre CSS)
--}}
<style>
    .sidebar-link {
        display: block;
        padding: 0.75rem 1rem;
        color: inherit;
        text-decoration: none;
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .sidebar-link:hover {
        background-color: #f8fafc;
        transform: translateX(4px);
    }

    .sidebar-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .sidebar-link.active .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .sidebar-link .icon-wrapper {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(102, 126, 234, 0.1);
        border-radius: 0.5rem;
    }

    .sidebar-link.active .icon-wrapper {
        background-color: rgba(255, 255, 255, 0.2);
    }
</style>

{{--
    Exemple d'utilisation dans un layout Blade
--}}

@if(auth()->check())
    <!-- Dans votre menu principal -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="{{ route('finance.dashboard') }}" class="nav-link">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>
        
        <!-- Ajoutez ici le lien vers Paiements V3 -->
        <li class="nav-item">
            <a href="{{ route('v3.payment.manage') }}" 
               class="nav-link {{ request()->routeIs('v3.payment.*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-2-front me-2"></i>
                Paiements V3
                <span class="badge bg-success ms-2 small">Nouveau</span>
            </a>
        </li>
        
        <!-- Autres liens -->
    </ul>
@endif
