<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <button type="button" id="sidebarCollapse" class="btn btn-info">
            <i class="bi bi-list"></i>
        </button>
        <livewire:application.widgets.school-year-label />
        <div class="d-flex align-items-center">
            <div class="dropdown ms-3">
                <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="userDropdown"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2"></i>
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a href="{{ route('admin.user.profile') }}" class="dropdown-item">Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('settings.main') }}">Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="float-end">
                            @csrf
                            <a href="#"
                                onclick="event.preventDefault();
                                        this.closest('form').submit();"
                                class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i>Se déconnecter</a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
