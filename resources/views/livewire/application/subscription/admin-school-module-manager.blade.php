<div>
    <x-ui.page-header title="Gestion des Modules - {{ $school->name }}" icon="puzzle-piece">
        <x-slot:actions>
            <a href="{{ route('v2.schools.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux écoles
            </a>
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

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Informations de l'école -->
            <div class="row mb-4">
                <div class="col-12">
                    <x-ui.content-card>
                        <div class="d-flex align-items-center">
                            @if($school->logo)
                                <img src="{{ asset('storage/' . $school->logo) }}"
                                    alt="Logo"
                                    class="rounded me-3"
                                    width="64"
                                    height="64"
                                    style="object-fit: cover;">
                            @else
                                <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center me-3"
                                    style="width: 64px; height: 64px;">
                                    <i class="bi bi-building text-primary fs-3"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $school->name }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-envelope me-2"></i>{{ $school->email }}
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-telephone me-2"></i>{{ $school->phone }}
                                </p>
                            </div>
                        </div>
                    </x-ui.content-card>
                </div>
            </div>

            <!-- Modules souscrits -->
            <div class="row mb-4">
                <div class="col-12">
                    <x-ui.content-card title="Modules Actifs" icon="box">
                        @if($subscribedModules->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Cette école n'a aucun module actif</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Module</th>
                                            <th>Plan</th>
                                            <th>Statut</th>
                                            <th>Date de début</th>
                                            <th>Expire le</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subscribedModules as $module)
                                            @php
                                                $subscription = $module->pivot;
                                                // Récupérer le SchoolModule via la table pivot
                                                $schoolModule = \App\Models\SchoolModule::where('school_id', $school->id)
                                                    ->where('module_id', $module->id)
                                                    ->first();
                                            @endphp

                                            @if($schoolModule)
                                                <tr wire:key="subscribed-{{ $schoolModule->id }}">
                                                    <td>
                                                        <i class="{{ $module->icon }} text-{{ $module->color ?? 'primary' }} me-2"></i>
                                                        <strong>{{ $module->name }}</strong>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $plan = \App\Models\SubscriptionPlan::find($schoolModule->plan_id);
                                                        @endphp
                                                        {{ $plan->name ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        {!! $schoolModule->getStatusBadge() !!}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($schoolModule->started_at)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($schoolModule->expires_at)->format('d/m/Y') }}
                                                    @if($schoolModule->daysRemaining() > 0 && $schoolModule->daysRemaining() <= 7)
                                                        <i class="fas fa-exclamation-triangle text-warning ms-1"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        @if($schoolModule->status === 'active')
                                                            <button wire:click="suspendSubscription({{ $schoolModule->id }})"
                                                                    class="btn btn-warning"
                                                                    title="Suspendre">
                                                                <i class="fas fa-pause"></i>
                                                            </button>
                                                        @elseif($schoolModule->status === 'suspended')
                                                            <button wire:click="activateSubscription({{ $schoolModule->id }})"
                                                                    class="btn btn-success"
                                                                    title="Activer">
                                                                <i class="fas fa-play"></i>
                                                            </button>
                                                        @endif

                                                        @if(in_array($schoolModule->status, ['active', 'expired']))
                                                            <button wire:click="renewSubscription({{ $schoolModule->id }})"
                                                                    class="btn btn-info"
                                                                    title="Renouveler">
                                                                <i class="fas fa-sync"></i>
                                                            </button>
                                                        @endif

                                                        <button wire:click="cancelSubscription({{ $schoolModule->id }})"
                                                                wire:confirm="Êtes-vous sûr de vouloir annuler cette souscription ?"
                                                                class="btn btn-danger"
                                                                title="Annuler">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </x-ui.content-card>
                </div>
            </div>

            <!-- Modules disponibles -->
            <div class="row">
                <div class="col-12">
                    <x-ui.content-card title="Modules Disponibles" icon="store">
                        @if($availableModules->isEmpty())
                            <div class="alert alert-info text-center mb-0">
                                <i class="fas fa-info-circle"></i>
                                Tous les modules disponibles ont été souscrits par cette école !
                            </div>
                        @else
                            <div class="row">
                                @foreach ($availableModules as $module)
                                    <div class="col-lg-4 col-md-6 mb-4" wire:key="available-{{ $module->id }}">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-header  border-0">
                                                <h5 class="mb-0 fw-semibold">
                                                    <i class="{{ $module->icon }} text-{{ $module->color ?? 'primary' }}"></i>
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
                                                        <span class="badge text-bg-info">
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
                                                        <button wire:click="openSubscribeModal({{ $module->id }})"
                                                                class="btn btn-primary">
                                                            <i class="fas fa-plus"></i> Souscrire pour cette école
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </x-ui.content-card>
                </div>
            </div>
        </div>
    </section>

    <!-- Indicateur de chargement -->
    <x-v2.loading-overlay title="Chargement en cours..." subtitle="Veuillez patienter" />

    <!-- Modal de souscription -->
    @if ($showSubscribeModal && $selectedModule)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" wire:click.self="closeModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <livewire:application.subscription.module-subscribe-modal
                        :module="$selectedModule"
                        :key="'admin-subscribe-modal-'.$selectedModule->id"
                        @close-modal="closeModal"
                    />
                </div>
            </div>
        </div>
    @endif
</div>
