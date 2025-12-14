<x-modal.build-modal-fixed idModal="emailReportModal" size="lg" headerLabelIcon="bi bi-envelope"
    headerLabel="Envoyer le Rapport par Email">

    <div style="padding: 0.5rem;">
        @if (session()->has('success'))
            <div class="alert alert-success" style="border-radius: 8px; border-left: 4px solid #059669;">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger" style="border-radius: 8px; border-left: 4px solid #dc2626;">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <div class="mb-4">
            <p style="color: #6b7280; font-size: 0.95rem; margin-bottom: 1rem;">
                Le rapport financier sera envoyé en pièce jointe au format PDF aux destinataires suivants :
            </p>

            <!-- Liste des destinataires -->
            <div
                style="background-color: #f8f9fa; padding: 1rem; border-radius: 8px; border: 1px solid #e1e4e8; max-height: 300px; overflow-y: auto;">
                @if (empty($recipients))
                    <p class="text-center text-muted mb-0" style="padding: 1rem;">
                        <i class="bi bi-info-circle me-2"></i>Aucun destinataire chargé
                    </p>
                @else
                    <div class="list-group list-group-flush">
                        @foreach ($recipients as $index => $recipient)
                            <div class="list-group-item d-flex justify-content-between align-items-start"
                                style="background: white; border: 1px solid #e1e4e8; border-radius: 6px; margin-bottom: 0.5rem; padding: 0.75rem 1rem;">
                                <div class="grow">
                                    <div style="font-weight: 600; color: #1a1f36; font-size: 0.9rem;">
                                        <i class="bi bi-person-circle me-2"
                                            style="color: #059669;"></i>{{ $recipient['name'] }}
                                    </div>
                                    <div style="color: #6b7280; font-size: 0.85rem;">
                                        <i class="bi bi-envelope me-1"></i>{{ $recipient['email'] }}
                                    </div>
                                    @if ($recipient['is_default'])
                                        <span class="badge"
                                            style="background-color: #e0f2fe; color: #0369a1; font-size: 0.75rem; margin-top: 0.25rem;">
                                            {{ $recipient['role'] }}
                                        </span>
                                    @else
                                        <span class="badge"
                                            style="background-color: #fef3c7; color: #92400e; font-size: 0.75rem; margin-top: 0.25rem;">
                                            Personnalisé
                                        </span>
                                    @endif
                                </div>
                                <button type="button" wire:click="removeRecipient({{ $index }})"
                                    class="btn btn-sm btn-outline-danger"
                                    style="padding: 0.25rem 0.5rem; border-radius: 4px;"
                                    {{ $isSendingEmail ? 'disabled' : '' }}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Formulaire d'ajout d'email -->
            <div class="mt-3"
                style="background-color: ssss padding: 1rem; border-radius: 8px; border: 1px solid #e1e4e8;">
                <label
                    style="color: #1a1f36; font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; display: block;">
                    <i class="bi bi-plus-circle me-2" style="color: #059669;"></i>Ajouter un email
                    personnalisé
                </label>
                <div class="input-group">
                    <input type="email" wire:model="newEmail"
                        class="form-control @error('newEmail') is-invalid @enderror" placeholder="exemple@email.com"
                        style="border: 1px solid #d1d5db; border-radius: 6px 0 0 6px; padding: 0.6rem 1rem;"
                        {{ $isSendingEmail ? 'disabled' : '' }}>
                    <button type="button" wire:click="addEmail" class="btn"
                        style="background-color: #059669; color: white; border: none; border-radius: 0 6px 6px 0; padding: 0.6rem 1.2rem;"
                        {{ $isSendingEmail ? 'disabled' : '' }}>
                        <i class="bi bi-plus-lg"></i> Ajouter
                    </button>
                </div>
                @error('newEmail')
                    <div class="text-danger" style="font-size: 0.85rem; margin-top: 0.25rem;">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div style="background-color: padding: 1rem; border-radius: 8px; border-left: 4px solid #059669;">
            <small style="color: #1a1f36; font-size: 0.85rem;">
                <strong><i class="bi bi-info-circle me-1"></i>Type de rapport :</strong>
                @switch($reportType)
                    @case('daily')
                        Journalier
                    @break

                    @case('weekly')
                        Hebdomadaire
                    @break

                    @case('monthly')
                        Mensuel
                    @break

                    @case('custom')
                        Personnalisé
                    @break

                    @case('last_30_days')
                        Derniers 30 jours
                    @break

                    @case('last_12_months')
                        Derniers 12 mois
                    @break
                @endswitch
            </small>
        </div>
    </div>

    <!-- Footer avec boutons -->
    <div class="modal-footer" style="border-top: 2px solid #e1e4e8; padding: 1.25rem;">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
            style="padding: 0.6rem 1.5rem; border-radius: 6px; font-weight: 500;"
            {{ $isSendingEmail ? 'disabled' : '' }}>
            <i class="bi bi-x-circle me-2"></i>Annuler
        </button>
        <button type="button" class="btn" wire:click="sendReportByEmail"
            style="background-color: #059669; color: white; padding: 0.6rem 1.5rem; border-radius: 6px; font-weight: 500; border: none;"
            {{ $isSendingEmail ? 'disabled' : '' }}>
            @if ($isSendingEmail)
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Envoi en cours...
            @else
                <i class="bi bi-send me-2"></i>Envoyer
            @endif
        </button>
    </div>
    </div>
</x-modal.build-modal-fixed>
