<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">{{ Auth::user()?->school?->name }}</a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
                    id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                    data-bs-display="static">
                    <span class="theme-icon-active">
                        <i class="my-1"></i>
                    </span>
                    <span class="d-lg-none ms-2" id="bd-theme-text">Thème</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text"
                    style="--bs-dropdown-min-width: 8rem;">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center active"
                            data-bs-theme-value="light" aria-pressed="false">
                            <i class="bi bi-sun-fill me-2"></i>
                            Clair
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="dark" aria-pressed="false">
                            <i class="bi bi-moon-fill me-2"></i>
                            Sombre
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center"
                            data-bs-theme-value="auto" aria-pressed="true">
                            <i class="bi bi-circle-fill-half-stroke me-2"></i>
                            Auto
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown user-menu"> <a href="#" class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"> <img src="{{ asset('images/defautl-user.jpg') }}"
                        class="user-image rounded-circle shadow" alt="User Image"> <span
                        class="d-none d-md-inline">Alexander Pierce</span> </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end"> <!--begin::User Image-->
                    <li class="user-header text-bg-primary"> <img src="{{ asset('images/defautl-user.jpg') }}"
                            class="rounded-circle shadow" alt="User Image">
                        <p>
                            {{ Auth::user()->name }}
                            <small>{{ Auth::user()->email }}</small>
                        </p>
                    </li> <!--end::User Image--> <!--begin::Menu Body-->
                    <li class="user-body"> <!--begin::Row-->
                        <div class="row">
                            <div class="col-4 text-center"> <a href="#">Followers</a> </div>
                            <div class="col-4 text-center"> <a href="#">Sales</a> </div>
                            <div class="col-4 text-center"> <a href="#">Friends</a> </div>
                        </div> <!--end::Row-->
                    </li> <!--end::Menu Body--> <!--begin::Menu Footer-->
                    <li class="user-footer">
                        <a href="#" class="btn btn-default btn-flat">Profile</a>

                        <form method="POST" action="{{ route('logout') }}" class="float-end">
                            @csrf
                            <a href="#"
                                onclick="event.preventDefault();
                        this.closest('form').submit();"
                                class="btn btn-default btn-flat ">Se deconnecter</a>
                        </form>

                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li> <!--end::User Menu Dropdown-->
        </ul> <!--end::End Navbar Links-->
    </div> <!--end::Container-->

    @push('js')
        <script type="module">
            (() => {
                "use strict";

                const storedTheme = localStorage.getItem("theme");

                const getPreferredTheme = () => {
                    if (storedTheme) {
                        return storedTheme;
                    }

                    return window.matchMedia("(prefers-color-scheme: dark)").matches ?
                        "dark" :
                        "light";
                };

                const setTheme = function(theme) {
                    if (
                        theme === "auto" &&
                        window.matchMedia("(prefers-color-scheme: dark)").matches
                    ) {
                        document.documentElement.setAttribute("data-bs-theme", "dark");
                    } else {
                        document.documentElement.setAttribute("data-bs-theme", theme);
                    }
                };

                setTheme(getPreferredTheme());

                const showActiveTheme = (theme, focus = false) => {
                    const themeSwitcher = document.querySelector("#bd-theme");

                    if (!themeSwitcher) {
                        return;
                    }

                    const themeSwitcherText = document.querySelector("#bd-theme-text");
                    const activeThemeIcon = document.querySelector(".theme-icon-active i");
                    const btnToActive = document.querySelector(
                        `[data-bs-theme-value="${theme}"]`
                    );
                    const svgOfActiveBtn = btnToActive.querySelector("i").getAttribute("class");

                    for (const element of document.querySelectorAll("[data-bs-theme-value]")) {
                        element.classList.remove("active");
                        element.setAttribute("aria-pressed", "false");
                    }

                    btnToActive.classList.add("active");
                    btnToActive.setAttribute("aria-pressed", "true");
                    activeThemeIcon.setAttribute("class", svgOfActiveBtn);
                    const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`;
                    themeSwitcher.setAttribute("aria-label", themeSwitcherLabel);

                    if (focus) {
                        themeSwitcher.focus();
                    }
                };

                window
                    .matchMedia("(prefers-color-scheme: dark)")
                    .addEventListener("change", () => {
                        if (storedTheme !== "light" || storedTheme !== "dark") {
                            setTheme(getPreferredTheme());
                        }
                    });

                window.addEventListener("DOMContentLoaded", () => {
                    showActiveTheme(getPreferredTheme());

                    for (const toggle of document.querySelectorAll("[data-bs-theme-value]")) {
                        toggle.addEventListener("click", () => {
                            const theme = toggle.getAttribute("data-bs-theme-value");
                            localStorage.setItem("theme", theme);
                            setTheme(theme);
                            showActiveTheme(theme, true);
                        });
                    }
                });
            })();
        </script>
    @endpush
</nav>