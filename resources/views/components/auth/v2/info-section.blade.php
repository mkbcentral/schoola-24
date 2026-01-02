{{-- Composant : Section information (côté droit) --}}
<div class="col-md-6 col-lg-7 d-none d-md-flex">
    <div class="right-section w-100 d-flex align-items-center justify-content-center p-5 position-relative overflow-hidden">
        <!-- Formes décoratives animées -->
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
            <div class="shape shape-5"></div>
        </div>

        <!-- Motif de fond décoratif moderne -->
        <div class="decorative-pattern"></div>

        <!-- Contenu -->
        <div class="text-white text-center position-relative z-3 content-wrapper">
            <!-- Icône principale avec animation -->
            <div class="main-icon-wrapper mb-4 animate-float">
                <div class="icon-background"></div>
                <i class="bi bi-mortarboard-fill main-icon"></i>
            </div>
            
            <!-- Titre avec effet -->
            <h2 class="fw-bold mb-3 main-title animate-fade-in" style="animation-delay: 0.2s;">
                Système de Gestion Scolaire
            </h2>
            <p class="lead mb-5 subtitle-description animate-fade-in" style="animation-delay: 0.3s;">
                Gérez efficacement votre établissement scolaire avec notre plateforme intuitive et complète
            </p>

            <!-- Caractéristiques améliorées -->
            <div class="row g-3 mt-4 features-grid">
                <x-auth.v2.feature-card 
                    icon="people-fill" 
                    title="Gestion des élèves" 
                    description="Inscriptions & suivi"
                    delay="0.4s"
                />
                <x-auth.v2.feature-card 
                    icon="cash-stack" 
                    title="Gestion financière" 
                    description="Paiements & rapports"
                    delay="0.5s"
                />
                <x-auth.v2.feature-card 
                    icon="graph-up-arrow" 
                    title="Rapports détaillés" 
                    description="Analytics avancés"
                    delay="0.6s"
                />
                <x-auth.v2.feature-card 
                    icon="shield-check" 
                    title="Sécurité avancée" 
                    description="Protection totale"
                    delay="0.7s"
                />
            </div>

            <!-- Badge statistique -->
            <div class="stats-badge animate-fade-in" style="animation-delay: 0.8s;">
                <div class="d-flex justify-content-center gap-4">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Écoles</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Élèves</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
