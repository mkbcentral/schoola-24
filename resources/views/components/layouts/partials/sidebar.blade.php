<nav id="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/Vector-white.svg') }}" alt="Logo" class="brand-image text-start opacity-75 shadow "
            width="40px">
        <span class="h4 fw-bold fw-light">{{ config('app.name') }}</span>
    </div>

    <ul class="list-unstyled components">
        @if (Auth::user()->singleAppLinks->isEmpty() ||
                (Auth::user()->multiAppLinks->isEmpty() && Auth::user()->role->name == 'ADMIN_SCHOOL'))
            <x-navigation.nav-link linkLabel='Dashboard' icon='bi bi-chart-bar' href="{{ route('dashboard.main') }}"
                :active="request()->routeIs('dashboard.main')" />
            <x-navigation.dropdown-link wire:ignore.self label='Administration' icon='bi bi-person-gear'
                class="text-white" idItem="admin">
                <x-navigation.nav-link linkLabel='Gestion des utilisateurs' icon='bi bi-person-video2'
                    href="{{ route('admin.main') }}" :active="request()->routeIs('admin.main')" :show="request()->routeIs('admin.main')" />
                <x-navigation.nav-link linkLabel='Gestion des roles' icon='bi bi-fingerprint'
                    href="{{ route('admin.role') }}" :active="request()->routeIs('admin.role')" :show="request()->routeIs('admin.main')" />
                <x-navigation.nav-link linkLabel='Gestion école' icon='bi bi-house-gear-fill'
                    href="{{ route('admin.schools') }}" :active="request()->routeIs('admin.schools')" :show="request()->routeIs('admin.main')" />
            </x-navigation.dropdown-link>
            <x-navigation.dropdown-link wire:ignore.self label='Navigation' icon='bi bi-link-45deg' class="text-white"
                idItem="nav">
                <x-navigation.nav-link linkLabel='Simple menu' icon='bi bi-link' href="{{ route('navigation.single') }}"
                    :active="request()->routeIs('navigation.single')" :show="request()->routeIs('navigation.single')" />
                <x-navigation.nav-link linkLabel='Multi menu' icon='bi bi-link' href="{{ route('navigation.multi') }}"
                    :active="request()->routeIs('navigation.multi')" :show="request()->routeIs('navigation.multi')" />
                <x-navigation.nav-link linkLabel='Sous menu' icon='bi bi-link' href="{{ route('navigation.sub') }}"
                    :active="request()->routeIs('navigation.sub')" :show="request()->routeIs('navigation.sub')" />
            </x-navigation.dropdown-link>
        @else
            @foreach (Auth::user()->singleAppLinks as $singleAppLink)
                <x-navigation.nav-link linkLabel='{{ $singleAppLink->name }}' icon='{{ $singleAppLink->icon }}'
                    href="{{ route($singleAppLink->route) }}" :active="request()->routeIs([$singleAppLink->route])" />
            @endforeach
            @foreach (Auth::user()->multiAppLinks as $multiAppLink)
                <x-navigation.dropdown-link wire:ignore.self label='{{ $multiAppLink->name }}'
                    icon='{{ $multiAppLink->icon }}' class="text-white"
                    idItem="{{ Str::slug($multiAppLink->name, '-') }}">
                    @foreach (Auth::user()->subLinks()->where('multi_app_link_id', $multiAppLink->id)->get() as $subLink)
                        <x-navigation.nav-link linkLabel='{{ $subLink->name }}' icon='{{ $subLink->icon }}'
                            href="{{ route($subLink->route) }}" :active="request()->routeIs([$subLink->route])" :show="request()->routeIs([$subLink->route])" />
                    @endforeach
                </x-navigation.dropdown-link>
            @endforeach
        @endif
    </ul>
    <div class="sidebar-footer">
        <p>{{ config('app.name') }}</p>
        <p class="version-number">v1.0.0</p>
    </div>
</nav>
