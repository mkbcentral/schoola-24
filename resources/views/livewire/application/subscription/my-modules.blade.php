<div>
    <x-ui.page-header title="Mes Modules" icon="puzzle-piece">
        <x-slot:actions>
            <button wire:click="loadModules" class="btn btn-outline-secondary">
                <i class="fas fa-sync"></i> Actualiser
            </button>
        </x-slot:actions>
    </x-ui.page-header>

    <section class="content">
        <div class="container-fluid">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('warning'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('warning') }}
                </div>
            @endif

            @if (session()->has('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('info') }}
                </div>
            @endif

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6 mb-3">
                    <x-ui.stat-card
                        icon="check-circle"
                        label="Modules Actifs"
                        :value="$moduleStats['active']"
                        color="success"
                    />
                </div>
                <div class="col-lg-3 col-6 mb-3">
                    <x-ui.stat-card
                        icon="flask"
                        label="En Essai"
                        :value="$moduleStats['trial']"
                        color="info"
                    />
                </div>
                <div class="col-lg-3 col-6 mb-3">
                    <x-ui.stat-card
                        icon="exclamation-triangle"
                        label="Expire Bientôt"
                        :value="$moduleStats['expiring_soon']"
                        color="warning"
                    />
                </div>
                <div class="col-lg-3 col-6 mb-3">
                    <x-ui.stat-card
                        icon="puzzle-piece"
                        label="Total"
                        :value="$moduleStats['total']"
                        color="secondary"
                    />
                </div>
            </div>

            <!-- Alertes pour modules expirant bientôt -->
            @if ($expiringModules->isNotEmpty())
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Attention !</h5>
                    Les modules suivants expirent bientôt :
                    <ul class="mb-0">
                        @foreach ($expiringModules as $expiring)
                            <li>
                                <strong>{{ $expiring->module->name }}</strong> -
                                Expire dans {{ $expiring->daysRemaining() }} jour(s)
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('school.subscriptions.index') }}" class="btn btn-warning btn-sm mt-2">
                        <i class="fas fa-sync"></i> Renouveler
                    </a>
                </div>
            @endif

            <!-- Liste des modules actifs -->
            <div class="row">
                @forelse ($activeModules as $subscription)
                    <div class="col-lg-4 col-md-6 mb-4" wire:key="module-{{ $subscription->id }}">
                        <livewire:application.subscription.module-card
                            :subscription="$subscription"
                            :key="'module-card-'.$subscription->id"
                        />
                    </div>
                @empty
                    <div class="col-12">
                        <x-ui.content-card>
                            <div class="text-center py-5">
                                <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
                                <h4>Aucun module actif</h4>
                                <p class="text-muted">Vous n'avez pas encore de modules actifs.</p>
                                <a href="{{ route('school.subscriptions.index') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Découvrir les modules
                                </a>
                            </div>
                        </x-ui.content-card>
                    </div>
                @endforelse
            </div>

            <!-- Actions rapides -->
            <div class="row mt-4">
                <div class="col-12">
                    <x-ui.content-card title="Actions Rapides" icon="bolt">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('school.subscriptions.index') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-shopping-cart"></i> Souscrire à un nouveau module
                                </a>
                            </div>
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('school.subscriptions.history') }}" class="btn btn-info btn-block">
                                    <i class="fas fa-history"></i> Voir l'historique
                                </a>
                            </div>
                        </div>
                    </x-ui.content-card>
                </div>
            </div>
        </div>
    </section>
</div>
