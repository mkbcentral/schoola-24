<x-navigation.nav-link linkLabel='Dashboard' icon='bi bi-chart-bar' href="{{ route('dashboard.main') }}"
    :active="request()->routeIs('dashboard.main')" />

<x-navigation.dropdown-link wire:ignore.self label='Pédagogiques' icon='bi bi-person-gear' class="text-white"
    idItem="root">
    <x-navigation.nav-link linkLabel='Liste des eleves' icon='bi bi-person-video2'
        href="{{ route('registration.v2.index') }}" :active="request()->routeIs('registration.v2.index')" :show="request()->routeIs('registration.v2.index')" />
</x-navigation.dropdown-link>
<x-navigation.dropdown-link wire:ignore.self label='Payments' icon='bi bi-person-gear' class="text-white"
    idItem="payment">
    <x-navigation.nav-link linkLabel='Nouveau payment' icon='bi bi-person-video2' href="{{ route('payment.quick') }}"
        :active="request()->routeIs('payment.quick')" :show="request()->routeIs('payment.quick')" />
    <x-navigation.nav-link linkLabel='Suivi impaiement' icon='bi bi-person-video2'
        href="{{ route('rapport.student.debt') }}" :active="request()->routeIs('rapport.student.debt')" :show="request()->routeIs('rapport.student.debt')" />
</x-navigation.dropdown-link>
<x-navigation.dropdown-link wire:ignore.self label='Administration' icon='bi bi-person-gear' class="text-white"
    idItem="admin">
    <x-navigation.nav-link linkLabel='Gestion des utilisateurs' icon='bi bi-person-video2'
        href="{{ route('admin.main') }}" :active="request()->routeIs('admin.main')" :show="request()->routeIs('admin.main')" />
    <x-navigation.nav-link linkLabel='Gestion des roles' icon='bi bi-fingerprint' href="{{ route('admin.role') }}"
        :active="request()->routeIs('admin.role')" :show="request()->routeIs('admin.main')" />
    <x-navigation.nav-link linkLabel='Gestion école' icon='bi bi-house-gear-fill' href="{{ route('admin.schools') }}"
        :active="request()->routeIs('admin.schools')" :show="request()->routeIs('admin.main')" />
</x-navigation.dropdown-link>
