<nav id="sidebar" class="fixed left-0 top-0 h-screen w-64 bg-linear-to-br from-indigo-950 via-blue-950 to-indigo-900 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 shadow-2xl z-50 flex flex-col transition-all duration-300 backdrop-blur-xl border-r border-blue-800/20 dark:border-gray-800/30">
    <!-- Header -->
    <div class="px-5 py-6 border-b border-blue-700/20 dark:border-gray-700/20 bg-linear-to-r from-blue-900/30 to-transparent">
        <div class="flex items-center gap-3 group cursor-pointer">
            <div class="relative">
                <div class="absolute inset-0 bg-blue-400 blur-lg opacity-20 group-hover:opacity-40 transition-opacity duration-300"></div>
                <img src="{{ asset('images/Vector-white.svg') }}" alt="Logo" class="relative w-11 h-11 drop-shadow-lg">
            </div>
            <div>
                <span class="text-xl font-bold text-white tracking-tight">{{ config('app.name') }}</span>
                <p class="text-xs text-blue-300/70 dark:text-gray-400">Gestion scolaire</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto py-4 px-3 scrollbar-thin scrollbar-thumb-blue-600/30 scrollbar-track-blue-900/10 hover:scrollbar-thumb-blue-600/50">
        <ul class="space-y-1.5">
            {{-- Dashboard Financier --}}
            <li>
                <a href="{{ route('finance.dashboard') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 text-white/90 hover:text-white hover:bg-blue-700/30 dark:hover:bg-gray-700/40 rounded-lg transition-all duration-200 group {{ request()->routeIs('finance.dashboard') ? 'bg-blue-700/40 dark:bg-gray-700/50 text-white font-medium' : '' }}">
                    <i class="bi bi-speedometer2 text-lg"></i>
                    <span class="text-sm font-medium">Dashboard Financier</span>
                </a>
            </li>

            {{-- Paiements --}}
            <li x-data="{ open: {{ request()->routeIs('payment.*') || request()->routeIs('report.payments') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 text-white/90 hover:text-white hover:bg-blue-700/30 dark:hover:bg-gray-700/40 rounded-lg transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-credit-card text-lg"></i>
                        <span class="text-sm font-medium">Paiements</span>
                    </div>
                    <i class="bi bi-chevron-down text-xs transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul x-show="open" x-collapse class="mt-2 ml-3 space-y-1 border-l-2 border-blue-600/30 dark:border-gray-700/40 pl-4">
                    <li>
                        <a href="{{ route('payment.list') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('payment.list') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-list-ul text-xs opacity-70"></i>
                            <span>Liste des paiements</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('payment.quick') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('payment.quick') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-lightning text-xs opacity-70"></i>
                            <span>Paiement rapide</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('report.payments') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('report.payments') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-file-earmark-bar-graph text-xs opacity-70"></i>
                            <span>Rapport paiements</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Dépenses --}}
            <li x-data="{ open: {{ request()->routeIs('expense.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 text-white/90 hover:text-white hover:bg-blue-700/30 dark:hover:bg-gray-700/40 rounded-lg transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-wallet2 text-lg"></i>
                        <span class="text-sm font-medium">Dépenses</span>
                    </div>
                    <i class="bi bi-chevron-down text-xs transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul x-show="open" x-collapse class="mt-2 ml-3 space-y-1 border-l-2 border-blue-600/30 dark:border-gray-700/40 pl-4">
                    <li>
                        <a href="{{ route('expense.manage') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('expense.manage') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-cash-stack text-xs opacity-70"></i>
                            <span>Gestion des dépenses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('expense.settings') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('expense.settings') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-gear text-xs opacity-70"></i>
                            <span>Paramètres dépenses</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Étudiants --}}
            <li x-data="{ open: {{ request()->routeIs('student.*') || request()->routeIs('rapport.student.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 text-white/90 hover:text-white hover:bg-blue-700/30 dark:hover:bg-gray-700/40 rounded-lg transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-people text-lg"></i>
                        <span class="text-sm font-medium">Élèves</span>
                    </div>
                    <i class="bi bi-chevron-down text-xs transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul x-show="open" x-collapse class="mt-2 ml-3 space-y-1 border-l-2 border-blue-600/30 dark:border-gray-700/40 pl-4">
                    <li>
                        <a href="{{ route('student.info') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('student.info') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-person-lines-fill text-xs opacity-70"></i>
                            <span>Informations élèves</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('rapport.student.debt') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('rapport.student.debt') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-exclamation-triangle text-xs opacity-70"></i>
                            <span>Dettes élèves</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Frais scolaires --}}
            <li>
                <a href="{{ route('fee.scolar') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 text-white/90 hover:text-white hover:bg-blue-700/30 dark:hover:bg-gray-700/40 rounded-lg transition-all duration-200 group {{ request()->routeIs('fee.scolar') ? 'bg-blue-700/40 dark:bg-gray-700/50 text-white font-medium' : '' }}">
                    <i class="bi bi-mortarboard text-lg"></i>
                    <span class="text-sm font-medium">Frais scolaires</span>
                </a>
            </li>

            {{-- Rapports Financiers --}}
            <li x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 text-white/90 hover:text-white hover:bg-blue-700/30 dark:hover:bg-gray-700/40 rounded-lg transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-graph-up text-lg"></i>
                        <span class="text-sm font-medium">Rapports Financiers</span>
                    </div>
                    <i class="bi bi-chevron-down text-xs transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul x-show="open" x-collapse class="mt-2 ml-3 space-y-1 border-l-2 border-blue-600/30 dark:border-gray-700/40 pl-4">
                    <li>
                        <a href="{{ route('reports.comparison') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('reports.comparison') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-bar-chart-line text-xs opacity-70"></i>
                            <span>Comparaison</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.forecast') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('reports.forecast') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-graph-up-arrow text-xs opacity-70"></i>
                            <span>Prévisions</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.treasury') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('reports.treasury') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-safe text-xs opacity-70"></i>
                            <span>Trésorerie</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.profitability') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('reports.profitability') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-percent text-xs opacity-70"></i>
                            <span>Rentabilité</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Stock --}}
            <li x-data="{ open: {{ request()->routeIs('stock.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-3.5 py-2.5 text-white/90 hover:text-white hover:bg-blue-700/30 dark:hover:bg-gray-700/40 rounded-lg transition-all duration-200 group">
                    <div class="flex items-center gap-3">
                        <i class="bi bi-box-seam text-lg"></i>
                        <span class="text-sm font-medium">Stock</span>
                    </div>
                    <i class="bi bi-chevron-down text-xs transition-transform duration-300" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul x-show="open" x-collapse class="mt-2 ml-3 space-y-1 border-l-2 border-blue-600/30 dark:border-gray-700/40 pl-4">
                    <li>
                        <a href="{{ route('stock.dashboard') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('stock.dashboard') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-speedometer text-xs opacity-70"></i>
                            <span>Dashboard Stock</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stock.main') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('stock.main') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-grid text-xs opacity-70"></i>
                            <span>Catalogue articles</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stock.categories') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('stock.categories') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-tags text-xs opacity-70"></i>
                            <span>Catégories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stock.inventory') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('stock.inventory') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-clipboard-check text-xs opacity-70"></i>
                            <span>Inventaire</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stock.audit') }}"
                           class="flex items-center gap-2.5 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-blue-700/20 dark:hover:bg-gray-700/30 rounded-lg transition-all {{ request()->routeIs('stock.audit') ? 'bg-blue-700/30 dark:bg-gray-700/40 text-white font-medium' : '' }}">
                            <i class="bi bi-clock-history text-xs opacity-70"></i>
                            <span>Historique audit</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="px-5 py-4 border-t border-blue-700/20 dark:border-gray-700/20 bg-gradient-to-r from-blue-900/20 to-transparent backdrop-blur-sm" x-data="themeToggle()">
        <!-- Theme Toggle -->
        <button @click="toggle()" class="w-full mb-3 flex items-center justify-between gap-3 px-3 py-2.5 text-white/90 hover:text-white hover:bg-blue-700/30 dark:hover:bg-gray-700/40 rounded-lg transition-all duration-200 group">
            <div class="flex items-center gap-3">
                <i class="bi bi-sun-fill text-lg hidden dark:block"></i>
                <i class="bi bi-moon-stars-fill text-lg block dark:hidden"></i>
                <span class="text-sm font-medium">
                    <span class="hidden dark:block">Mode Clair</span>
                    <span class="block dark:hidden">Mode Sombre</span>
                </span>
            </div>
            <i class="bi bi-chevron-right text-xs opacity-50"></i>
        </button>

        <div class="text-center space-y-2">
            <p class="text-white font-semibold text-sm tracking-wide">{{ config('app.name') }}</p>
            <div class="flex items-center justify-center gap-2">
                <div class="h-px flex-1 bg-linear-to-r from-transparent via-blue-500/30 to-transparent"></div>
                <span class="px-3 py-1 bg-linear-to-r from-blue-600/30 to-blue-700/20 backdrop-blur-sm text-blue-200 text-xs rounded-full border border-blue-500/20 font-medium">v1.0.0</span>
                <div class="h-px flex-1 bg-linear-to-r from-transparent via-blue-500/30 to-transparent"></div>
            </div>
        </div>
    </div>
</nav>

<script>
    function themeToggle() {
        return {
            toggle() {
                const html = document.documentElement;
                const currentTheme = html.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                // Update theme
                html.setAttribute('data-bs-theme', newTheme);

                // Toggle dark class for Tailwind
                if (newTheme === 'dark') {
                    html.classList.add('dark', 'dark-mode');
                } else {
                    html.classList.remove('dark', 'dark-mode');
                }

                // Save to localStorage
                localStorage.setItem('schoola-theme', newTheme);

                // Dispatch event for navbar sync
                window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: newTheme } }));
            }
        }
    }

    // Listen for theme changes from navbar
    window.addEventListener('theme-changed', (event) => {
        // Theme already updated by the component that triggered it
    });
</script>
