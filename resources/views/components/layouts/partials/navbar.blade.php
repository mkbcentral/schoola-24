<nav class="navbar-modern" style="z-index: 1050;">
    <div class="navbar-content">
        {{-- Section gauche : Toggle + Info --}}
        <div class="navbar-left">
            <button type="button" id="sidebarCollapse" class="navbar-toggle" aria-label="Toggle sidebar">
                <span class="toggle-icon"></span>
            </button>

            {{-- Année scolaire (Desktop) --}}
            <div class="navbar-info d-none d-lg-flex">
                <livewire:application.widgets.school-year-label />
            </div>
        </div>

        {{-- Section droite : Actions --}}
        <div class="navbar-right">

            {{-- Séparateur --}}
            <div class="navbar-divider d-none d-lg-block"></div>

            {{-- User Menu --}}
            <div class="navbar-user dropdown">
                <button class="navbar-user-btn" type="button" id="userDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <span class="user-avatar">
                        <i class="bi bi-person-circle"></i>
                    </span>
                    <span class="user-name d-none d-lg-inline">{{ Auth::user()->name }}</span>
                    <i class="bi bi-chevron-down user-arrow"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end navbar-dropdown" aria-labelledby="userDropdown">
                    <li class="dropdown-header">
                        <div class="dropdown-user-info">
                            <i class="bi bi-person-circle fs-3"></i>
                            <div>
                                <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </div>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a href="{{ route('admin.user.profile') }}" class="dropdown-item">
                            <i class="bi bi-person"></i>
                            <span>Mon profil</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.main') }}" class="dropdown-item">
                            <i class="bi bi-gear"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item dropdown-item-danger">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Se déconnecter</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
