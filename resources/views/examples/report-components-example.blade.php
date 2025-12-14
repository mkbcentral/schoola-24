{{--
    EXEMPLE D'UTILISATION DES NOUVEAUX COMPOSANTS
    Ce fichier sert de référence pour les développeurs
--}}

<div class="report-container">
    <div class="container-fluid py-4">

        {{-- En-tête avec actions --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <h3 class="fw-bold">
                <i class="bi bi-file-earmark-text text-primary"></i>
                Mon Rapport
            </h3>

            <div class="d-flex gap-2">
                <x-report.action-button type="email" label="Envoyer par Email" data-bs-toggle="modal"
                    data-bs-target="#emailModal" />

                <x-report.action-button type="download" label="Télécharger" href="#" />

                <x-report.action-button type="preview" label="Aperçu" href="#" target="_blank" />
            </div>
        </div>

        {{-- Filtres et Résumé --}}
        <div class="row mb-4 g-3">
            {{-- Filtres --}}
            <div class="col-lg-6">
                <x-report.filter-card title="Filtres & Paramètres">
                    <div class="col-12 mb-3">
                        <label class="filter-label">Type de Rapport</label>
                        <select class="filter-select">
                            <option value="daily">Rapport Journalier</option>
                            <option value="monthly">Rapport Mensuel</option>
                            <option value="custom">Période Personnalisée</option>
                        </select>
                    </div>

                    <div class="col-6 mb-3">
                        <label class="filter-label">Date de Début</label>
                        <input type="date" class="filter-input">
                    </div>

                    <div class="col-6 mb-3">
                        <label class="filter-label">Date de Fin</label>
                        <input type="date" class="filter-input">
                    </div>
                </x-report.filter-card>
            </div>

            {{-- Résumé --}}
            <div class="col-lg-6">
                <x-report.summary-card title="Résumé Financier">
                    {{-- Total général --}}
                    <x-report.summary-item label="Total Paiements" value="150" type="total" />

                    {{-- Par devise --}}
                    <x-report.summary-item label="USD" value="25,000" type="usd" badge="75 paiements" />

                    <x-report.summary-item label="CDF" value="12,500,000" type="cdf" badge="75 paiements" />

                    <p class="summary-footer">
                        Période : <strong>Décembre 2025</strong>
                    </p>
                </x-report.summary-card>
            </div>
        </div>

        {{-- Alertes --}}
        <x-report.alert type="info">
            Le rapport a été généré avec succès.
        </x-report.alert>

        <x-report.alert type="warning" title="Attention">
            Certaines données peuvent être incomplètes pour la période sélectionnée.
        </x-report.alert>

        <x-report.alert type="error" title="Erreur">
            Impossible de générer le rapport PDF. Veuillez réessayer.
        </x-report.alert>

        {{-- Table de détails --}}
        <div class="report-details-table">
            <h6 class="details-header">Détails par Catégorie</h6>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Catégorie</th>
                            <th style="text-align: right;">USD</th>
                            <th style="text-align: right;">CDF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Frais de Scolarité</td>
                            <td style="text-align: right;" class="text-success">
                                15,000 <small>(45)</small>
                            </td>
                            <td style="text-align: right;" class="text-danger">
                                7,500,000 <small>(45)</small>
                            </td>
                        </tr>
                        <tr>
                            <td>Frais d'Examen</td>
                            <td style="text-align: right;" class="text-success">
                                5,000 <small>(15)</small>
                            </td>
                            <td style="text-align: right;" class="text-danger">
                                2,500,000 <small>(15)</small>
                            </td>
                        </tr>
                        <tr>
                            <td>Autres Frais</td>
                            <td style="text-align: right;" class="text-success">
                                5,000 <small>(15)</small>
                            </td>
                            <td style="text-align: right;" class="text-danger">
                                2,500,000 <small>(15)</small>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Footer avec totaux --}}
            <div class="table-footer">
                <div class="footer-grid">
                    <div class="footer-item">
                        <p class="footer-label">Catégories</p>
                        <h5 class="footer-value">3</h5>
                    </div>
                    <div class="footer-item">
                        <p class="footer-label">Total USD</p>
                        <h5 class="footer-value text-success">25,000</h5>
                    </div>
                    <div class="footer-item">
                        <p class="footer-label">Total CDF</p>
                        <h5 class="footer-value text-danger">12,500,000</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
