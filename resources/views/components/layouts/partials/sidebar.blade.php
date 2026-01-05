<nav id="sidebar" class="sidebar-modern">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <img src="{{ asset('images/Vector-white.svg') }}" alt="Logo" class="brand-image">
            <span class="brand-text">{{ config('app.name') }}</span>
        </div>
    </div>
    <ul class="list-unstyled components">
        {{-- Dashboard Financier --}}
        <x-navigation.nav-link linkLabel='Dashboard Financier' icon='bi bi-speedometer2'
            href="{{ route('finance.dashboard') }}"
            :active="request()->routeIs('finance.dashboard')" />

        {{-- Paiements --}}
        <x-navigation.dropdown-link wire:ignore.self label='Paiements' icon='bi bi-credit-card'
            class="text-white" idItem="paiements">
            <x-navigation.nav-link linkLabel='Liste des paiements' icon='bi bi-list-ul'
                href="{{ route('payment.list') }}" :active="request()->routeIs('payment.list')" :show="request()->routeIs('payment.*')" />
            <x-navigation.nav-link linkLabel='Paiement rapide' icon='bi bi-lightning'
                href="{{ route('payment.quick') }}" :active="request()->routeIs('payment.quick')" :show="request()->routeIs('payment.*')" />
            <x-navigation.nav-link linkLabel='Rapport paiements' icon='bi bi-file-earmark-bar-graph'
                href="{{ route('report.payments') }}" :active="request()->routeIs('report.payments')" :show="request()->routeIs('report.payments')" />
        </x-navigation.dropdown-link>

        {{-- Dépenses --}}
        <x-navigation.dropdown-link wire:ignore.self label='Dépenses' icon='bi bi-wallet2'
            class="text-white" idItem="depenses">
            <x-navigation.nav-link linkLabel='Gestion des dépenses' icon='bi bi-cash-stack'
                href="{{ route('expense.manage') }}" :active="request()->routeIs('expense.manage')" :show="request()->routeIs('expense.*')" />
            <x-navigation.nav-link linkLabel='Paramètres dépenses' icon='bi bi-gear'
                href="{{ route('expense.settings') }}" :active="request()->routeIs('expense.settings')" :show="request()->routeIs('expense.*')" />
        </x-navigation.dropdown-link>

        {{-- Étudiants --}}
        <x-navigation.dropdown-link wire:ignore.self label='Étudiants' icon='bi bi-people'
            class="text-white" idItem="etudiants">
            <x-navigation.nav-link linkLabel='Informations étudiants' icon='bi bi-person-lines-fill'
                href="{{ route('student.info') }}" :active="request()->routeIs('student.info')" :show="request()->routeIs('student.*')" />
            <x-navigation.nav-link linkLabel='Dettes étudiants' icon='bi bi-exclamation-triangle'
                href="{{ route('rapport.student.debt') }}" :active="request()->routeIs('rapport.student.debt')" :show="request()->routeIs('rapport.student.*')" />
        </x-navigation.dropdown-link>

        {{-- Frais scolaires --}}
        <x-navigation.nav-link linkLabel='Frais scolaires' icon='bi bi-mortarboard'
            href="{{ route('fee.scolar') }}"
            :active="request()->routeIs('fee.scolar')" />

        {{-- Rapports Financiers --}}
        <x-navigation.dropdown-link wire:ignore.self label='Rapports Financiers' icon='bi bi-graph-up'
            class="text-white" idItem="rapports">
            <x-navigation.nav-link linkLabel='Comparaison' icon='bi bi-bar-chart-line'
                href="{{ route('reports.comparison') }}" :active="request()->routeIs('reports.comparison')" :show="request()->routeIs('reports.*')" />
            <x-navigation.nav-link linkLabel='Prévisions' icon='bi bi-graph-up-arrow'
                href="{{ route('reports.forecast') }}" :active="request()->routeIs('reports.forecast')" :show="request()->routeIs('reports.*')" />
            <x-navigation.nav-link linkLabel='Trésorerie' icon='bi bi-safe'
                href="{{ route('reports.treasury') }}" :active="request()->routeIs('reports.treasury')" :show="request()->routeIs('reports.*')" />
            <x-navigation.nav-link linkLabel='Rentabilité' icon='bi bi-percent'
                href="{{ route('reports.profitability') }}" :active="request()->routeIs('reports.profitability')" :show="request()->routeIs('reports.*')" />
        </x-navigation.dropdown-link>

        {{-- Stock --}}
        <x-navigation.dropdown-link wire:ignore.self label='Stock' icon='bi bi-box-seam'
            class="text-white" idItem="stock">
            <x-navigation.nav-link linkLabel='Dashboard Stock' icon='bi bi-speedometer'
                href="{{ route('stock.dashboard') }}" :active="request()->routeIs('stock.dashboard')" :show="request()->routeIs('stock.*')" />
            <x-navigation.nav-link linkLabel='Catalogue articles' icon='bi bi-grid'
                href="{{ route('stock.main') }}" :active="request()->routeIs('stock.main')" :show="request()->routeIs('stock.*')" />
            <x-navigation.nav-link linkLabel='Catégories' icon='bi bi-tags'
                href="{{ route('stock.categories') }}" :active="request()->routeIs('stock.categories')" :show="request()->routeIs('stock.*')" />
            <x-navigation.nav-link linkLabel='Inventaire' icon='bi bi-clipboard-check'
                href="{{ route('stock.inventory') }}" :active="request()->routeIs('stock.inventory')" :show="request()->routeIs('stock.*')" />
            <x-navigation.nav-link linkLabel='Historique audit' icon='bi bi-clock-history'
                href="{{ route('stock.audit') }}" :active="request()->routeIs('stock.audit')" :show="request()->routeIs('stock.*')" />
        </x-navigation.dropdown-link>
    </ul>
    <div class="sidebar-footer">
        <div class="footer-content">
            <p class="footer-app-name">{{ config('app.name') }}</p>
            <p class="version-badge">v1.0.0</p>
        </div>
    </div>
</nav>
