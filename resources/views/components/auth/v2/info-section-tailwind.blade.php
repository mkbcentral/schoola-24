{{-- Composant Info Section - Version Tailwind CSS --}}
<div class="hidden md:flex md:w-1/2 lg:w-7/12">
    <div class="w-full flex items-center justify-center p-12 relative overflow-hidden bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-700">
        <!-- Formes décoratives animées -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute w-96 h-96 bg-white/10 rounded-full -top-20 -left-20 animate-float"></div>
            <div class="absolute w-64 h-64 bg-white/5 rounded-full top-1/4 -right-10 animate-float" style="animation-delay: 0.5s;"></div>
            <div class="absolute w-80 h-80 bg-white/10 rounded-full -bottom-20 left-1/3 animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute w-48 h-48 bg-white/5 rounded-full top-2/3 right-1/4 animate-float" style="animation-delay: 1.5s;"></div>
            <div class="absolute w-32 h-32 bg-white/10 rounded-full bottom-10 right-10 animate-float" style="animation-delay: 2s;"></div>
        </div>

        <!-- Motif de fond décoratif -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        </div>

        <!-- Contenu -->
        <div class="text-white text-center relative z-10 max-w-2xl">
            <!-- Icône principale avec animation -->
            <div class="mb-6 inline-block relative animate-float">
                <div class="absolute inset-0 bg-white/20 rounded-full blur-2xl"></div>
                <div class="relative w-24 h-24 mx-auto bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/30">
                    <i class="bi bi-mortarboard-fill text-5xl text-white"></i>
                </div>
            </div>
            
            <!-- Titre avec effet -->
            <h2 class="text-4xl font-bold mb-4 animate-fade-in" style="animation-delay: 0.2s;">
                Système de Gestion Scolaire
            </h2>
            <p class="text-xl text-white/90 mb-8 animate-fade-in" style="animation-delay: 0.3s;">
                Gérez efficacement votre établissement scolaire avec notre plateforme intuitive et complète
            </p>

            <!-- Caractéristiques -->
            <div class="grid grid-cols-2 gap-4 mt-6">
                <x-auth.v2.feature-card-tailwind
                    icon="people-fill" 
                    title="Gestion des élèves" 
                    description="Inscriptions & suivi"
                    delay="0.4s"
                />
                <x-auth.v2.feature-card-tailwind
                    icon="cash-stack" 
                    title="Gestion financière" 
                    description="Paiements & rapports"
                    delay="0.5s"
                />
                <x-auth.v2.feature-card-tailwind
                    icon="graph-up-arrow" 
                    title="Rapports détaillés" 
                    description="Analytics avancés"
                    delay="0.6s"
                />
                <x-auth.v2.feature-card-tailwind
                    icon="shield-check" 
                    title="Sécurité avancée" 
                    description="Protection totale"
                    delay="0.7s"
                />
            </div>

            <!-- Badge statistique -->
            <div class="mt-10 p-6 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 animate-fade-in" style="animation-delay: 0.8s;">
                <div class="flex justify-center items-center gap-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-1">1000+</div>
                        <div class="text-sm text-white/70">Écoles</div>
                    </div>
                    <div class="w-px h-12 bg-white/30"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-1">50K+</div>
                        <div class="text-sm text-white/70">Élèves</div>
                    </div>
                    <div class="w-px h-12 bg-white/30"></div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white mb-1">99.9%</div>
                        <div class="text-sm text-white/70">Uptime</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% {
        transform: translateY(0) scale(1);
    }
    50% {
        transform: translateY(-20px) scale(1.05);
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out forwards;
    opacity: 0;
}
</style>
