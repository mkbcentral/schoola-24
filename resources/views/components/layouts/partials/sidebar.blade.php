<nav id="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/Vector-white.svg') }}" alt="Logo" class="brand-image text-start opacity-75 shadow "
            width="40px">
        <span class="h4 fw-bold fw-light">{{ config('app.name') }}</span>
    </div>

    <ul class="list-unstyled components">
        @foreach (Auth::user()->singleAppLinks as $singleAppLink)
            <x-navigation.nav-link linkLabel='{{ $singleAppLink->name }}' icon='{{ $singleAppLink->icon }}'
                href="{{ route($singleAppLink->route) }}" :active="request()->routeIs([$singleAppLink->route])" />
        @endforeach
        @foreach (Auth::user()->multiAppLinks as $multiAppLink)
            <x-navigation.dropdown-link wire:ignore.self label='{{ $multiAppLink->name }}'
                icon='{{ $multiAppLink->icon }}' class="text-white" idItem="{{ Str::slug($multiAppLink->name, '-') }}">
                @foreach (Auth::user()->subLinks()->where('multi_app_link_id', $multiAppLink->id)->get() as $subLink)
                    <x-navigation.nav-link linkLabel='{{ $subLink->name }}' icon='{{ $subLink->icon }}'
                        href="{{ route($subLink->route) }}" :active="request()->routeIs([$subLink->route])" :show="request()->routeIs([$subLink->route])" />
                @endforeach
            </x-navigation.dropdown-link>
        @endforeach
    </ul>
    <div class="sidebar-footer">
        <p>{{ config('app.name') }}</p>
        <p class="version-number">v1.0.0</p>
    </div>
</nav>
