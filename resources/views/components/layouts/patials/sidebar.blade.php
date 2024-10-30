<aside class="app-sidebar  shadow" data-bs-theme="dark">
    <div class="sidebar-brand ">
        <a href="/" class="brand-link text-start">
            <img src="{{ asset('images/Vector-white.svg') }}" alt="Logo"
                class="brand-image text-start opacity-75 shadow">
            <span class="brand-text fw-light">{{ config('app.name') }}</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                @foreach (Auth::user()->singleAppLinks as $singleAppLink)
                    <x-navigation.nav-link linkLabel='{{ $singleAppLink->name }}' icon='{{ $singleAppLink->icon }}'
                        href="{{ route($singleAppLink->route) }}" :active="request()->routeIs([$singleAppLink->route])" />
                @endforeach
                @foreach (Auth::user()->multiAppLinks as $multiAppLink)
                    <x-navigation.dropdown-link wire:ignore.self label='{{ $multiAppLink->name }}'
                        icon='"{{ $multiAppLink->icon }}' class="text-white ">
                        @foreach (Auth::user()->subLinks()->where('multi_app_link_id', $multiAppLink->id)->get() as $subLink)
                            <x-navigation.nav-link linkLabel='{{ $subLink->name }}' icon='{{ $subLink->icon }}'
                                href="{{ route($subLink->route) }}" :active="request()->routeIs([$subLink->route])" />
                        @endforeach
                    </x-navigation.dropdown-link>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
