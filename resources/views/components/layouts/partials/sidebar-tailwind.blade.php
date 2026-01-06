{{--
  Sidebar Version Tailwind - Migration progressive
  Conserve la structure et fonctionnalités existantes
--}}
<nav id="sidebar"
     class="sidebar-modern fixed top-0 left-0 bottom-0 z-50
            bg-gradient-to-b from-gray-800 to-gray-900
            dark:from-gray-900 dark:to-black
            text-white overflow-y-auto overflow-x-hidden
            flex flex-col sidebar-transition
            scrollbar-thin shadow-2xl border-r border-white/5
            sidebar-blur"
     style="width: var(--sidebar-width-expanded); min-width: var(--sidebar-width-expanded); max-width: var(--sidebar-width-expanded);">

    {{-- Header --}}
    <div class="sidebar-header px-6 py-5 border-b border-white/10 bg-gradient-to-r from-gray-800/50 to-gray-900/50">
        <div class="sidebar-brand flex items-center gap-3 transition-all duration-400">
            <img src="{{ asset('images/Vector-white.svg') }}"
                 alt="Logo"
                 class="brand-image w-10 h-10 transition-all duration-400">
            <span class="brand-text text-xl font-bold tracking-tight transition-all duration-400">
                {{ config('app.name') }}
            </span>
        </div>
    </div>

    {{-- Menu Items --}}
    <ul class="components list-none p-0 m-0 flex-1 py-4">

        {{-- Dashboard Financier --}}
        <li class="nav-item">
            <a href="{{ route('finance.dashboard') }}"
               data-label="Dashboard Financier"
               class="nav-link flex items-center gap-3 px-6 py-3
                      text-white/90 hover:text-white hover:bg-gray-700/50
                      transition-all duration-200 relative
                      {{ request()->routeIs('finance.dashboard') ? 'bg-blue-600/20 text-white border-l-4 border-blue-500' : '' }}">
                <i class="bi bi-speedometer2 text-lg"></i>
                <span class="nav-text font-medium">Dashboard Financier</span>
            </a>
        </li>

        {{-- Paiements --}}
        <li class="nav-item dropdown">
            <a href="#paiements"
               data-bs-toggle="collapse"
               data-label="Paiements"
               class="nav-link dropdown-toggle flex items-center justify-between gap-3 px-6 py-3
                      text-white/90 hover:text-white hover:bg-gray-700/50
                      transition-all duration-200"
               aria-expanded="false">
                <div class="flex items-center gap-3">
                    <i class="bi bi-credit-card text-lg"></i>
                    <span class="nav-text font-medium">Paiements</span>
                </div>
                <span class="dropdown-arrow transition-transform duration-200">
                    <i class="bi bi-chevron-down text-sm"></i>
                </span>
            </a>
            <ul class="dropdown-submenu list-none collapse pl-6 pr-4 py-2 space-y-1" id="paiements">
                <li>
                    <a href="{{ route('payment.list') }}"
                       data-label="Liste des paiements"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('payment.list') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-list-ul text-base"></i>
                        <span class="nav-text text-sm">Liste des paiements</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('payment.quick') }}"
                       data-label="Paiement rapide"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('payment.quick') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-lightning text-base"></i>
                        <span class="nav-text text-sm">Paiement rapide</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('report.payments') }}"
                       data-label="Rapport paiements"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('report.payments') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph text-base"></i>
                        <span class="nav-text text-sm">Rapport paiements</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Dépenses --}}
        <li class="nav-item dropdown">
            <a href="#depenses"
               data-bs-toggle="collapse"
               data-label="Dépenses"
               class="nav-link dropdown-toggle flex items-center justify-between gap-3 px-6 py-3
                      text-white/90 hover:text-white hover:bg-gray-700/50
                      transition-all duration-200"
               aria-expanded="false">
                <div class="flex items-center gap-3">
                    <i class="bi bi-wallet2 text-lg"></i>
                    <span class="nav-text font-medium">Dépenses</span>
                </div>
                <span class="dropdown-arrow transition-transform duration-200">
                    <i class="bi bi-chevron-down text-sm"></i>
                </span>
            </a>
            <ul class="dropdown-submenu list-none collapse pl-6 pr-4 py-2 space-y-1" id="depenses">
                <li>
                    <a href="{{ route('expense.manage') }}"
                       data-label="Gestion des dépenses"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('expense.manage') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-cash-stack text-base"></i>
                        <span class="nav-text text-sm">Gestion des dépenses</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('expense.settings') }}"
                       data-label="Paramètres dépenses"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('expense.settings') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-gear text-base"></i>
                        <span class="nav-text text-sm">Paramètres dépenses</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Étudiants --}}
        <li class="nav-item dropdown">
            <a href="#etudiants"
               data-bs-toggle="collapse"
               data-label="Étudiants"
               class="nav-link dropdown-toggle flex items-center justify-between gap-3 px-6 py-3
                      text-white/90 hover:text-white hover:bg-gray-700/50
                      transition-all duration-200"
               aria-expanded="false">
                <div class="flex items-center gap-3">
                    <i class="bi bi-people text-lg"></i>
                    <span class="nav-text font-medium">Étudiants</span>
                </div>
                <span class="dropdown-arrow transition-transform duration-200">
                    <i class="bi bi-chevron-down text-sm"></i>
                </span>
            </a>
            <ul class="dropdown-submenu list-none collapse pl-6 pr-4 py-2 space-y-1" id="etudiants">
                <li>
                    <a href="{{ route('student.info') }}"
                       data-label="Informations étudiants"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('student.info') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-person-lines-fill text-base"></i>
                        <span class="nav-text text-sm">Informations étudiants</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('rapport.student.debt') }}"
                       data-label="Dettes étudiants"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('rapport.student.debt') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-exclamation-triangle text-base"></i>
                        <span class="nav-text text-sm">Dettes étudiants</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Frais scolaires --}}
        <li class="nav-item">
            <a href="{{ route('fee.scolar') }}"
               data-label="Frais scolaires"
               class="nav-link flex items-center gap-3 px-6 py-3
                      text-white/90 hover:text-white hover:bg-gray-700/50
                      transition-all duration-200 relative
                      {{ request()->routeIs('fee.scolar') ? 'bg-blue-600/20 text-white border-l-4 border-blue-500' : '' }}">
                <i class="bi bi-mortarboard text-lg"></i>
                <span class="nav-text font-medium">Frais scolaires</span>
            </a>
        </li>

        {{-- Rapports Financiers --}}
        <li class="nav-item dropdown">
            <a href="#rapports"
               data-bs-toggle="collapse"
               data-label="Rapports Financiers"
               class="nav-link dropdown-toggle flex items-center justify-between gap-3 px-6 py-3
                      text-white/90 hover:text-white hover:bg-gray-700/50
                      transition-all duration-200"
               aria-expanded="false">
                <div class="flex items-center gap-3">
                    <i class="bi bi-graph-up text-lg"></i>
                    <span class="nav-text font-medium">Rapports Financiers</span>
                </div>
                <span class="dropdown-arrow transition-transform duration-200">
                    <i class="bi bi-chevron-down text-sm"></i>
                </span>
            </a>
            <ul class="dropdown-submenu list-none collapse pl-6 pr-4 py-2 space-y-1" id="rapports">
                <li>
                    <a href="{{ route('reports.comparison') }}"
                       data-label="Comparaison"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('reports.comparison') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-bar-chart-line text-base"></i>
                        <span class="nav-text text-sm">Comparaison</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.forecast') }}"
                       data-label="Prévisions"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('reports.forecast') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-graph-up-arrow text-base"></i>
                        <span class="nav-text text-sm">Prévisions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.treasury') }}"
                       data-label="Trésorerie"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('reports.treasury') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-safe text-base"></i>
                        <span class="nav-text text-sm">Trésorerie</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.profitability') }}"
                       data-label="Rentabilité"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('reports.profitability') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-percent text-base"></i>
                        <span class="nav-text text-sm">Rentabilité</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Stock --}}
        <li class="nav-item dropdown">
            <a href="#stock"
               data-bs-toggle="collapse"
               data-label="Stock"
               class="nav-link dropdown-toggle flex items-center justify-between gap-3 px-6 py-3
                      text-white/90 hover:text-white hover:bg-gray-700/50
                      transition-all duration-200"
               aria-expanded="false">
                <div class="flex items-center gap-3">
                    <i class="bi bi-box-seam text-lg"></i>
                    <span class="nav-text font-medium">Stock</span>
                </div>
                <span class="dropdown-arrow transition-transform duration-200">
                    <i class="bi bi-chevron-down text-sm"></i>
                </span>
            </a>
            <ul class="dropdown-submenu list-none collapse pl-6 pr-4 py-2 space-y-1" id="stock">
                <li>
                    <a href="{{ route('stock.dashboard') }}"
                       data-label="Dashboard Stock"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('stock.dashboard') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-speedometer text-base"></i>
                        <span class="nav-text text-sm">Dashboard Stock</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock.main') }}"
                       data-label="Catalogue articles"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('stock.main') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-grid text-base"></i>
                        <span class="nav-text text-sm">Catalogue articles</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock.categories') }}"
                       data-label="Catégories"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('stock.categories') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-tags text-base"></i>
                        <span class="nav-text text-sm">Catégories</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock.inventory') }}"
                       data-label="Inventaire"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('stock.inventory') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-clipboard-check text-base"></i>
                        <span class="nav-text text-sm">Inventaire</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('stock.audit') }}"
                       data-label="Historique audit"
                       class="flex items-center gap-3 px-6 py-2
                              text-white/80 hover:text-white hover:bg-gray-700/30
                              rounded-lg transition-all duration-200
                              {{ request()->routeIs('stock.audit') ? 'bg-blue-600/10 text-white' : '' }}">
                        <i class="bi bi-clock-history text-base"></i>
                        <span class="nav-text text-sm">Historique audit</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Lien vers page Tailwind Demo (dev seulement) --}}
        @if(config('app.env') !== 'production')
        <li class="nav-item mt-4 border-t border-white/10 pt-4">
            <a href="{{ route('tailwind.demo') }}"
               data-label="Démo Tailwind CSS"
               class="nav-link flex items-center gap-3 px-6 py-3
                      text-purple-400 hover:text-purple-300 hover:bg-purple-900/20
                      transition-all duration-200 relative
                      {{ request()->routeIs('tailwind.demo') ? 'bg-purple-600/20 text-purple-300 border-l-4 border-purple-500' : '' }}">
                <i class="bi bi-palette text-lg"></i>
                <span class="nav-text font-medium">Démo Tailwind</span>
            </a>
        </li>
        @endif
    </ul>

    {{-- Footer --}}
    <div class="sidebar-footer px-6 py-4 border-t border-white/10 bg-gradient-to-t from-gray-900/50 to-transparent">
        <div class="footer-content text-center">
            <p class="footer-app-name text-white/60 text-xs font-medium transition-all duration-400">
                {{ config('app.name') }}
            </p>
            <p class="version-badge text-white/40 text-xs mt-1 transition-all duration-400">v1.0.0</p>
        </div>
    </div>
</nav>
