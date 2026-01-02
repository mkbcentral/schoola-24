<div>
    <x-ui.page-header title="Gestion des Modules" icon="store" />

    <section class="content">
        <div class="container-fluid">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Modules disponibles -->
            <x-ui.content-card title="Modules Disponibles" icon="store">
                    <div class="row">
                        @forelse ($availableModules as $module)
                            <div class="col-lg-4 col-md-6 mb-4" wire:key="available-{{ $module->id }}">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-header bg-light border-0">
                                        <h5 class="mb-0 fw-semibold">
                                            <i class="{{ $module->icon }} text-primary"></i>
                                            {{ $module->name }}
                                        </h5>
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <p class="text-muted">{{ $module->description }}</p>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted small">À partir de</span>
                                                @php
                                                    $minPrice = $module->plans->min('price');
                                                @endphp
                                                <span class="text-success fw-bold fs-5">
                                                    {{ number_format($minPrice, 0, ',', ' ') }} FC
                                                </span>
                                            </div>
                                            <small class="text-muted">par mois</small>
                                        </div>

                                        @if ($module->trial_days > 0)
                                            <div class="mb-3">
                                                <span class="badge bg-info">
                                                    <i class="fas fa-flask"></i>
                                                    {{ $module->trial_days }} jours d'essai gratuit
                                                </span>
                                            </div>
                                        @endif

                                        <div class="mt-auto">
                                            <div class="btn-group-vertical w-100">
                                            @if ($module->trial_days > 0)
                                                <button wire:click="startTrial({{ $module->id }})"
                                                        class="btn btn-info">
                                                    <i class="fas fa-flask"></i> Essai Gratuit
                                                </button>
                                            @endif
                                            <button wire:click="selectModule({{ $module->id }})"
                                                    class="btn btn-primary">
                                                <i class="fas fa-shopping-cart"></i> Souscrire
                                            </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle"></i>
                                    Tous les modules disponibles ont déjà été souscrits !
                                </div>
                            </div>
                        @endforelse
                    </div>
            </x-ui.content-card>

            <!-- Modules souscrits -->
            <x-ui.content-card title="Mes Souscriptions" icon="box" class="mt-4">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Plan</th>
                                    <th>Statut</th>
                                    <th>Expire le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subscribedModules as $subscription)
                                    <tr wire:key="subscribed-{{ $subscription->id }}">
                                        <td>
                                            <i class="{{ $subscription->module->icon }}"></i>
                                            {{ $subscription->module->name }}
                                        </td>
                                        <td>{{ $subscription->plan->name ?? 'N/A' }}</td>
                                        <td>{!! $subscription->getStatusBadge() !!}</td>
                                        <td>
                                            {{ $subscription->getFormattedExpirationDate() }}
                                        </td>
                                        <td>
                                            <a href="{{ route('school.modules.dashboard') }}"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Aucune souscription active
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
            </x-ui.content-card>
        </div>
    </section>

    <!-- Modal de souscription -->
    @if ($showSubscribeModal && $selectedModule)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <livewire:application.subscription.module-subscribe-modal
                        :module="$selectedModule"
                        :key="'subscribe-modal-'.$selectedModule->id"
                        @close-modal="closeModal"
                    />
                </div>
            </div>
        </div>
    @endif
</div>
