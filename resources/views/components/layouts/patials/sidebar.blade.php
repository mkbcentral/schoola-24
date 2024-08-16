  <aside class="app-sidebar bg-side shadow" data-bs-theme="dark">
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
                  <x-navigation.nav-link linkLabel='Dashbord' icon='bi bi-bar-chart-fill'
                      href="{{ route('dashboard.main') }}" :active="request()->routeIs(['dashboard.main'])" />
                  @can('view-school-access')
                      @can('manage-student')
                          <x-navigation.nav-link linkLabel='Inscriptions' icon='bi bi-person-fill-add'
                              href="{{ route('responsible.main') }}" :active="request()->routeIs(['responsible.main'])" />
                          <x-navigation.nav-link linkLabel='Liste des élèves' icon='bi bi-people-fill'
                              href="{{ route('student.list') }}" :active="request()->routeIs(['student.list'])" />
                      @endcan
                      @can('manage-payment')
                          <x-navigation.nav-link linkLabel='Liste des élèves' icon='bi bi-people-fill'
                              href="{{ route('student.list') }}" :active="request()->routeIs(['student.list'])" />
                          <x-navigation.nav-link linkLabel='Nouveau paiement' icon='bi bi-arrow-left-right'
                              href="{{ route('payment.new') }}" :active="request()->routeIs(['payment.new'])" />
                          <x-navigation.nav-link linkLabel='Régularisation' icon='bi bi-wallet2'
                              href="{{ route('payment.regularization') }}" :active="request()->routeIs(['payment.regularization'])" />
                          <x-navigation.nav-link linkLabel='Liste des payments' icon='bi bi-people-fill'
                              href="{{ route('payment.rappport') }}" :active="request()->routeIs(['payment.rappport'])" />
                          <x-navigation.dropdown-link wire:ignore.self label='Finace' icon='bi bi-box-seam-fill'
                              class="text-white ">
                              <x-navigation.nav-link linkLabel='Dépot banque' icon='bi bi-bank2'
                                  href="{{ route('finance.bank') }}" :active="request()->routeIs(['finance.bank'])" />
                              <x-navigation.nav-link linkLabel='Epargne' icon='bi bi-wallet-fill'
                                  href="{{ route('finance.saving.money') }}" :active="request()->routeIs(['finance.saving.money'])" />
                              <x-navigation.nav-link linkLabel='Salaire' icon='bi bi-card-heading'
                                  href="{{ route('finance.salary') }}" :active="request()->routeIs(['finance.salary'])" />
                          </x-navigation.dropdown-link>
                          <x-navigation.dropdown-link wire:ignore.self label='Gestion des frais' icon='bi bi-wallet2'
                              class="text-white ">
                              <x-navigation.nav-link linkLabel='Frais inscription' icon='bi bi-file-earmark-plus-fill'
                                  href="{{ route('fee.registration') }}" :active="request()->routeIs(['fee.registration'])" />
                              <x-navigation.nav-link linkLabel='Frais scolaire' icon='bi bi-file-earmark-plus-fill'
                                  href="{{ route('fee.scolar') }}" :active="request()->routeIs(['fee.scolar'])" />
                              <x-navigation.nav-link linkLabel='Categorie Frais insc.' icon='bi bi-tags-fill'
                                  href="{{ route('category.fee.registration') }}" :active="request()->routeIs(['category.fee.registration'])" />
                              <x-navigation.nav-link linkLabel='Categorie Frais scolaire.' icon='bi bi-wallet2'
                                  href="{{ route('category.fee.scolar') }}" :active="request()->routeIs(['category.fee.scolar'])" />
                          </x-navigation.dropdown-link>
                      @endcan
                  @endcan
                  @can('manage-school')
                      <x-navigation.nav-link linkLabel='Liste des élèves' icon='bi bi-people-fill'
                          href="{{ route('student.list') }}" :active="request()->routeIs(['student.list'])" />
                      <x-navigation.nav-link linkLabel='Liste des payments' icon='bi bi-people-fill'
                          href="{{ route('payment.rappport') }}" :active="request()->routeIs(['payment.rappport'])" />
                      <x-navigation.dropdown-link wire:ignore.self label='Configuration' icon='bi bi-gear-fill'
                          class="text-white">
                          <x-navigation.nav-link linkLabel='Section' icon='bi bi-diagram-2'
                              href="{{ route('school.section') }}" :active="request()->routeIs(['school.section'])" />
                          <x-navigation.nav-link linkLabel='Option' icon='bi bi-columns-gap'
                              href="{{ route('school.option') }}" :active="request()->routeIs(['school.option'])" />
                          <x-navigation.nav-link linkLabel='Classe' icon='bi bi-houses-fill'
                              href="{{ route('school.class-room') }}" :active="request()->routeIs(['school.class-room'])" />
                      </x-navigation.dropdown-link>
                      <x-navigation.dropdown-link wire:ignore.self label='Administration' icon='bi bi-person-gear'
                          class="text-white">
                          <x-navigation.nav-link linkLabel='Gestion des utilisateurs' icon='bi bi-person-video2'
                              href="{{ route('admin.main') }}" :active="request()->routeIs(['admin.main'])" />
                          <x-navigation.nav-link linkLabel='Gestion des roles' icon='bi bi-fingerprint'
                              href="{{ route('admin.role') }}" :active="request()->routeIs(['admin.role'])" />
                      </x-navigation.dropdown-link>
                  @endcan
                  @can('view-school-unaccess')
                      <x-navigation.dropdown-link wire:ignore.self label='Administration' icon='bi bi-person-gear'
                          class="text-white">

                          <x-navigation.nav-link linkLabel='Gestion des roles' icon='bi bi-fingerprint'
                              href="{{ route('admin.role') }}" :active="request()->routeIs(['admin.role'])" />
                          <x-navigation.nav-link linkLabel='Gestionnaire écoles' icon='bi bi-house-gear-fill'
                              href="{{ route('admin.schools') }}" :active="request()->routeIs(['admin.schools'])" />

                      </x-navigation.dropdown-link>
                  @endcan

              </ul>
          </nav>
      </div>
  </aside>
