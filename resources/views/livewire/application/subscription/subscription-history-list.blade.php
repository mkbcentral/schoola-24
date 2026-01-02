<div>
    <x-ui.page-header title="Historique des Souscriptions" icon="history">
        <x-slot:actions>
            <a href="{{ route('school.modules.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </x-slot:actions>
    </x-ui.page-header>

    <section class="content">
        <div class="container-fluid">
            <x-ui.content-card title="Historique" icon="history">
                    <!-- Filtres -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">Module</label>
                            <select wire:model.live="moduleFilter" class="form-select">
                                <option value="">Tous les modules</option>
                                @foreach ($modules as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">Action</label>
                            <select wire:model.live="actionFilter" class="form-select">
                                <option value="">Toutes les actions</option>
                                <option value="subscribed">Souscription</option>
                                <option value="renewed">Renouvellement</option>
                                <option value="suspended">Suspension</option>
                                <option value="cancelled">Annulation</option>
                                <option value="expired">Expiration</option>
                                <option value="activated">Activation</option>
                                <option value="trial_started">Essai démarré</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">&nbsp;</label>
                            <button wire:click="clearFilters" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times"></i> Effacer les filtres
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Module</th>
                                    <th>Action</th>
                                    <th>Statut</th>
                                    <th>Utilisateur</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($history as $record)
                                    <tr>
                                        <td>{{ $record->action_date->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <i class="{{ $record->module->icon }}"></i>
                                            {{ $record->module->name }}
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">
                                                {{ $record->getActionDescription() }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($record->status_before)
                                                <small class="text-muted">{{ $record->status_before }}</small>
                                                <i class="fas fa-arrow-right mx-1"></i>
                                            @endif
                                            @if ($record->status_after)
                                                <strong>{{ $record->status_after }}</strong>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($record->performer)
                                                {{ $record->performer->name }}
                                            @else
                                                <span class="text-muted">Système</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($record->notes)
                                                <small>{{ Str::limit($record->notes, 50) }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            Aucun historique trouvé
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $history->links() }}
                    </div>
            </x-ui.content-card>
        </div>
    </section>
</div>
