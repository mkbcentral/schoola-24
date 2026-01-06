<div>
    <x-navigation.bread-crumb icon='bi bi-file-earmark-text' label="Rapports de Paiement">
        <x-navigation.bread-crumb-item label='Paiements' />
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="finance.dashboard" />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div class="min-vh-100" style="background-color: #f5f6fa;">
            <!-- Header avec Actions -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                @if (!empty($report))
                    <div class="d-flex gap-2 ms-auto">
                        <button type="button" class="btn btn-sm" data-bs-toggle="modal"
                            data-bs-target="#emailReportModal" @click="$wire.loadRecipients()"
                            style="background-color: #059669; color: white; border: none; font-weight: 500; padding: 0.6rem 1.2rem; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                            <i class="bi bi-envelope me-2" style="font-size: 0.9rem;"></i>Envoyer par Email
                        </button>
                        <a href="{{ $this->getExportPdfUrl() }}" class="btn btn-sm"
                            style="background-color: #1a1f36; color: white; border: none; text-decoration: none; font-weight: 500; padding: 0.6rem 1.2rem; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                            <i class="bi bi-download me-2" style="font-size: 0.9rem;"></i>Télécharger
                        </a>
                        <a href="{{ $this->getPreviewPdfUrl() }}" target="_blank" class="btn btn-sm"
                            style="background-color: white; color: #1a1f36; border: 1px solid #d1d5db; text-decoration: none; font-weight: 500; padding: 0.6rem 1.2rem; border-radius: 6px; transition: all 0.2s;">
                            <i class="bi bi-eye me-2" style="font-size: 0.9rem;"></i>Aperçu
                        </a>
                    </div>
                @endif
            </div>

            <div class="container-fluid py-4">
                <!-- Filtres et Résumé sur la même ligne -->
                <div class="row mb-4 g-3">
                    <!-- Filtres -->
                    <div class="col-lg-6">
                        <div
                            style="background: white; border: 1px solid #e1e4e8; border-radius: 8px; padding: 1.75rem; height: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                            <h6
                                style="color: #1a1f36; font-weight: 600; margin-bottom: 1.5rem; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1a1f36; padding-bottom: 0.75rem; display: inline-block;">
                                Filtres & Paramètres
                            </h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label
                                        style="color: #555; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; display: block;">Type
                                        de Rapport</label>
                                    <select wire:model.live="reportType"
                                        style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #1a1a1a; font-size: 0.9rem;">
                                        <option value="daily">Rapport Journalier</option>
                                        <option value="weekly">Rapport Hebdomadaire</option>
                                        <option value="monthly">Rapport Mensuel</option>
                                        <option value="custom">Période Personnalisée</option>
                                        <option value="last_30_days">Derniers 30 Jours</option>
                                        <option value="last_12_months">Derniers 12 Mois</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filtres Dynamiques Selon Type -->
                            @if ($reportType === 'daily')
                                <div class="row">
                                    <div class="col-12">
                                        <label
                                            style="color: #555; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; display: block;">Date</label>
                                        <input type="date" wire:model.live="selectedDate"
                                            style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #1a1a1a;">
                                    </div>
                                </div>
                            @elseif ($reportType === 'weekly')
                                <div class="row">
                                    <div class="col-12">
                                        <label
                                            style="color: #555; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; display: block;">Semaine
                                            Contenant la Date</label>
                                        <input type="date" wire:model.live="selectedDate"
                                            style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #1a1a1a;">
                                    </div>
                                </div>
                            @elseif ($reportType === 'monthly')
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label
                                            style="color: #555; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; display: block;">Mois</label>
                                        <select wire:model.live="selectedMonth"
                                            style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #1a1a1a;">
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}">
                                                    {{ ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'][$i - 1] }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label
                                            style="color: #555; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; display: block;">Année</label>
                                        <select wire:model.live="selectedYear"
                                            style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #1a1a1a;">
                                            @for ($y = now()->year - 5; $y <= now()->year; $y++)
                                                <option value="{{ $y }}">{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            @elseif ($reportType === 'custom')
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label
                                            style="color: #555; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; display: block;">Date
                                            de Début</label>
                                        <input type="date" wire:model.live="customStartDate"
                                            style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #1a1a1a;">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label
                                            style="color: #555; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; display: block;">Date
                                            de Fin</label>
                                        <input type="date" wire:model.live="customEndDate"
                                            style="width: 100%; padding: 0.6rem 0.8rem; border: 1px solid #ddd; border-radius: 4px; background-color: white; color: #1a1a1a;">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <button wire:click="updateCustomDates"
                                            style="width: 100%; padding: 0.6rem 0.8rem; background-color: #1a1a1a; color: white; border: none; border-radius: 4px; font-weight: 500; font-size: 0.9rem; cursor: pointer;">Générer
                                            Rapport</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Résumé Global -->
                    @if (!empty($report))
                        <div class="col-lg-6">
                            <div
                                style="background: white; border: 1px solid #e1e4e8; border-radius: 8px; padding: 1.75rem; height: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                                <h6
                                    style="color: #1a1f36; font-weight: 600; margin-bottom: 1.5rem; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1a1f36; padding-bottom: 0.75rem; display: inline-block;">
                                    Résumé Financier
                                </h6>

                                <!-- Total Paiements -->
                                <div
                                    style="padding: 1.25rem; background-color: #f9fafb; border: 1px solid #e1e4e8; border-left: 4px solid #1a1f36; border-radius: 6px; margin-bottom: 1rem; text-align: center;">
                                    <p
                                        style="color: #6b7280; font-size: 0.75rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">
                                        Total Paiements</p>
                                    <h3
                                        style="color: #1a1f36; margin: 0.5rem 0 0 0; font-weight: 700; font-size: 1.75rem;">
                                        {{ $report['total_payments'] ?? 0 }}</h3>
                                </div>

                                <!-- Devises avec Totaux -->
                                @foreach ($report['total_by_currency'] as $index => $currency)
                                    @php
                                        $colors = [
                                            'USD' => '#059669',
                                            'CDF' => '#dc2626',
                                            'EUR' => '#2563eb',
                                            'GBP' => '#7c3aed',
                                        ];
                                        $color = $colors[$currency['currency']] ?? '#1a1f36';
                                    @endphp
                                    <div
                                        style="padding: 1rem; background-color: #f9fafb; border: 1px solid #e1e4e8; border-left: 4px solid {{ $color }}; border-radius: 6px; margin-bottom: 0.875rem;">
                                        <div
                                            style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <p
                                                    style="color: #6b7280; font-size: 0.75rem; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">
                                                    {{ $currency['currency'] }}</p>
                                                <p
                                                    style="color: #1a1f36; margin: 0.3rem 0 0 0; font-weight: 700; font-size: 1.25rem;">
                                                    {{ number_format($currency['total'], 0, ',', ' ') }}</p>
                                            </div>
                                            <span
                                                style="background-color: {{ $color }}; color: white; padding: 0.4rem 0.875rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">{{ $currency['payment_count'] }}</span>
                                        </div>
                                    </div>
                                @endforeach

                                <p style="color: #999; font-size: 0.75rem; margin-top: 1rem; margin-bottom: 0;">Période
                                    :
                                    <strong style="color: #1a1a1a;">{{ $report['label'] }}</strong>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Messages Flash -->
            <div class="container-fluid py-4">
                @if (session('error'))
                    <div
                        style="background-color: #fee; border-left: 3px solid #dc3545; border-radius: 4px; padding: 1rem; margin-bottom: 1rem;">
                        <p style="color: #721c24; font-size: 0.9rem; margin: 0;"><strong>Erreur :</strong>
                            {{ session('error') }}</p>
                    </div>
                @endif

                @if (empty($report))
                    <div
                        style="background-color: #fffbf0; border-left: 3px solid #ffc107; border-radius: 4px; padding: 1rem; margin-bottom: 1rem;">
                        <p style="color: #856404; font-size: 0.9rem; margin: 0;"><strong>Information :</strong> Aucun
                            paiement
                            trouvé pour la période sélectionnée</p>
                    </div>
                @else
                    <!-- Détails par Catégorie - Tableau par Devise -->
                    <div style="margin-top: 1.5rem;">
                        <h6
                            style="color: #1a1f36; font-weight: 600; margin-bottom: 1.25rem; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #1a1f36; padding-bottom: 0.75rem; display: inline-block;">
                            Détails par Catégorie
                        </h6>

                        <div
                            style="background: white; border: 1px solid #e1e4e8; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
                            <div style="overflow-x: auto;">
                                <table style="width: 100%; border-collapse: collapse; margin: 0;">
                                    <thead>
                                        <tr style="background-color: #f9fafb; border-bottom: 2px solid #e1e4e8;">
                                            <th
                                                style="color: #374151; font-weight: 600; padding: 1rem 1.25rem; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                                Catégorie</th>
                                            <th
                                                style="color: #374151; font-weight: 600; padding: 1rem 1.25rem; text-align: right; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                                USD</th>
                                            <th
                                                style="color: #374151; font-weight: 600; padding: 1rem 1.25rem; text-align: right; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                                CDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($report['categories'] as $category)
                                            @php
                                                $usdData = null;
                                                $cdfData = null;
                                                foreach ($category['by_currency'] as $curr) {
                                                    if ($curr['currency'] === 'USD') {
                                                        $usdData = $curr;
                                                    }
                                                    if ($curr['currency'] === 'CDF') {
                                                        $cdfData = $curr;
                                                    }
                                                }
                                            @endphp
                                            <tr
                                                style="border-bottom: 1px solid #e5e7eb; transition: background-color 0.15s;">
                                                <td
                                                    style="padding: 1rem 1.25rem; color: #1a1f36; font-weight: 500; font-size: 0.9rem;">
                                                    {{ $category['name'] }}</td>
                                                <td
                                                    style="padding: 1rem 1.25rem; text-align: right; color: #059669; font-weight: 600; font-size: 0.95rem;">
                                                    @if ($usdData)
                                                        {{ number_format($usdData['total'], 0, ',', ' ') }}
                                                        <small
                                                            style="color: #9ca3af; font-weight: 400; font-size: 0.75rem; margin-left: 0.5rem;">({{ $usdData['payment_count'] }})</small>
                                                    @else
                                                        <span style="color: #d1d5db; font-weight: 400;">—</span>
                                                    @endif
                                                </td>
                                                <td
                                                    style="padding: 1rem 1.25rem; text-align: right; color: #dc2626; font-weight: 600; font-size: 0.95rem;">
                                                    @if ($cdfData)
                                                        {{ number_format($cdfData['total'], 0, ',', ' ') }}
                                                        <small
                                                            style="color: #9ca3af; font-weight: 400; font-size: 0.75rem; margin-left: 0.5rem;">({{ $cdfData['payment_count'] }})</small>
                                                    @else
                                                        <span style="color: #d1d5db; font-weight: 400;">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3"
                                                    style="padding: 1rem; text-align: center; color: #999; font-size: 0.85rem;">
                                                    Aucune catégorie trouvée</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Totaux en bas du tableau -->
                            <div
                                style="background-color: #f9fafb; border-top: 2px solid #e1e4e8; padding: 1.25rem 1.5rem;">
                                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                                    <div style="text-align: center;">
                                        <p
                                            style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 0.5rem 0; font-weight: 500;">
                                            Catégories</p>
                                        <h5 style="color: #1a1f36; font-weight: 700; margin: 0; font-size: 1.5rem;">
                                            {{ count($report['categories']) }}</h5>
                                    </div>
                                    <div style="text-align: center;">
                                        <p
                                            style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 0.5rem 0; font-weight: 500;">
                                            Total USD</p>
                                        <h5 style="color: #059669; font-weight: 700; margin: 0; font-size: 1.5rem;">
                                            @php
                                                $totalUsd =
                                                    collect($report['total_by_currency'])->firstWhere(
                                                        'currency',
                                                        'USD',
                                                    )['total'] ?? 0;
                                            @endphp
                                            {{ number_format($totalUsd, 0, ',', ' ') }}
                                        </h5>
                                    </div>
                                    <div style="text-align: center;">
                                        <p
                                            style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 0.5rem 0; font-weight: 500;">
                                            Total CDF</p>
                                        <h5 style="color: #dc2626; font-weight: 700; margin: 0; font-size: 1.5rem;">
                                            @php
                                                $totalCdf =
                                                    collect($report['total_by_currency'])->firstWhere(
                                                        'currency',
                                                        'CDF',
                                                    )['total'] ?? 0;
                                            @endphp
                                            {{ number_format($totalCdf, 0, ',', ' ') }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rapports Détaillés (Last 30 Days / Last 12 Months) -->
                    <div style="margin-top: 1.5rem;">
                        @if ($reportType === 'last_30_days' && !empty($report['daily_reports']))
                            <div style="margin-bottom: 1.5rem;">
                                <h6
                                    style="color: #1a1a1a; font-weight: 600; margin-bottom: 1rem; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Détail Quotidien</h6>

                                <div
                                    style="background-color: white; border: 1px solid #e8e8e8; border-radius: 8px; overflow: hidden;">
                                    <div style="overflow-x: auto;">
                                        <table style="width: 100%; border-collapse: collapse; margin: 0;">
                                            <thead>
                                                <tr
                                                    style="background-color: #f8f9fa; border-bottom: 1px solid #e8e8e8;">
                                                    <th
                                                        style="color: #666; font-weight: 500; padding: 1rem; text-align: left; font-size: 0.85rem;">
                                                        Date</th>
                                                    <th
                                                        style="color: #666; font-weight: 500; padding: 1rem; text-align: left; font-size: 0.85rem;">
                                                        Devise</th>
                                                    <th
                                                        style="color: #666; font-weight: 500; padding: 1rem; text-align: right; font-size: 0.85rem;">
                                                        Montant</th>
                                                    <th
                                                        style="color: #666; font-weight: 500; padding: 1rem; text-align: right; font-size: 0.85rem;">
                                                        Paiements</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($report['daily_reports'] as $day)
                                                    @foreach ($day['by_currency'] as $currency)
                                                        <tr style="border-bottom: 1px solid #f0f0f0;">
                                                            <td
                                                                style="padding: 1rem; color: #1a1a1a; font-weight: 500;">
                                                                {{ \Carbon\Carbon::parse($day['date'])->format('d/m/Y') }}
                                                            </td>
                                                            <td style="padding: 1rem; color: #666;">
                                                                <span
                                                                    style="background-color: #f0f0f0; color: #555; padding: 0.3rem 0.6rem; border-radius: 3px; font-size: 0.8rem; font-weight: 500;">{{ $currency['currency'] }}</span>
                                                            </td>
                                                            <td
                                                                style="padding: 1rem; text-align: right; color: #1a1a1a; font-weight: 600;">
                                                                {{ number_format($currency['total'], 0, ',', ' ') }}
                                                            </td>
                                                            <td style="padding: 1rem; text-align: right; color: #666;">
                                                                <span
                                                                    style="background-color: #e8e8e8; color: #555; padding: 0.3rem 0.6rem; border-radius: 3px; font-size: 0.8rem;">{{ $currency['payment_count'] }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($reportType === 'last_12_months' && !empty($report['monthly_reports']))
                            <div style="margin-bottom: 1.5rem;">
                                <h6
                                    style="color: #1a1a1a; font-weight: 600; margin-bottom: 1rem; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Détail Mensuel</h6>

                                <div
                                    style="background-color: white; border: 1px solid #e8e8e8; border-radius: 8px; overflow: hidden;">
                                    <div style="overflow-x: auto;">
                                        <table style="width: 100%; border-collapse: collapse; margin: 0;">
                                            <thead>
                                                <tr
                                                    style="background-color: #f8f9fa; border-bottom: 1px solid #e8e8e8;">
                                                    <th
                                                        style="color: #666; font-weight: 500; padding: 1rem; text-align: left; font-size: 0.85rem;">
                                                        Mois</th>
                                                    <th
                                                        style="color: #666; font-weight: 500; padding: 1rem; text-align: left; font-size: 0.85rem;">
                                                        Devise</th>
                                                    <th
                                                        style="color: #666; font-weight: 500; padding: 1rem; text-align: right; font-size: 0.85rem;">
                                                        Montant</th>
                                                    <th
                                                        style="color: #666; font-weight: 500; padding: 1rem; text-align: right; font-size: 0.85rem;">
                                                        Paiements</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($report['monthly_reports'] as $month)
                                                    @foreach ($month['by_currency'] as $currency)
                                                        <tr style="border-bottom: 1px solid #f0f0f0;">
                                                            <td
                                                                style="padding: 1rem; color: #1a1a1a; font-weight: 500;">
                                                                {{ $month['label'] }}</td>
                                                            <td style="padding: 1rem; color: #666;">
                                                                <span
                                                                    style="background-color: #f0f0f0; color: #555; padding: 0.3rem 0.6rem; border-radius: 3px; font-size: 0.8rem; font-weight: 500;">{{ $currency['currency'] }}</span>
                                                            </td>
                                                            <td
                                                                style="padding: 1rem; text-align: right; color: #1a1a1a; font-weight: 600;">
                                                                {{ number_format($currency['total'], 0, ',', ' ') }}
                                                            </td>
                                                            <td style="padding: 1rem; text-align: right; color: #666;">
                                                                <span
                                                                    style="background-color: #e8e8e8; color: #555; padding: 0.3rem 0.6rem; border-radius: 3px; font-size: 0.8rem;">{{ $currency['payment_count'] }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Pied de Page avec Infos de Génération -->
                        <div
                            style="margin-top: 2rem; padding: 1rem; background-color: #f8f9fa; border-left: 3px solid #1a1a1a; border-radius: 4px;">
                            <p style="color: #888; font-size: 0.8rem; margin: 0;">Rapport généré à
                                {{ $report['generated_at'] ?? now()->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Modal d'envoi par email -->
            @include('livewire.application.payment.report.send-report-email-modal')
    </x-content.main-content-page>
</div>

<style>
    /* Style professionnel sobre */
    select,
    input[type="date"] {
        transition: all 0.2s ease;
        border: 1px solid #d1d5db;
    }

    select:hover,
    input[type="date"]:hover {
        border-color: #9ca3af;
    }

    select:focus,
    input[type="date"]:focus {
        border-color: #1a1f36;
        box-shadow: 0 0 0 3px rgba(26, 31, 54, 0.08);
        outline: none;
    }

    button {
        transition: all 0.2s ease;
    }

    button:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    button:active {
        transform: translateY(0);
    }

    /* Effets subtils sur le tableau */
    table tbody tr {
        transition: background-color 0.15s ease;
    }

    table tbody tr:hover {
        background-color: #f9fafb !important;
    }

    /* Boutons d'action */
    a.btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        table {
            font-size: 0.85rem;
        }

        table td,
        table th {
            padding: 0.75rem !important;
        }

        h1 {
            font-size: 1.5rem !important;
        }

        [style*="grid-template-columns: repeat(3, 1fr)"] {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
    }
</style>
