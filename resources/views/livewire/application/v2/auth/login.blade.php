<div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    {{-- Animated background gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/80 via-purple-500/80 to-pink-500/80 animate-gradient"></div>
    
    {{-- Decorative animated elements --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-white/20 to-transparent rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 animate-float">
        </div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-br from-white/20 to-transparent rounded-full blur-3xl translate-x-1/2 translate-y-1/2 animate-float-delayed"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-gradient-to-br from-white/10 to-transparent rounded-full blur-2xl -translate-x-1/2 -translate-y-1/2 animate-float-slow"></div>
        
        {{-- Additional floating orbs --}}
        <div class="absolute top-1/4 right-1/4 w-32 h-32 bg-yellow-400/20 rounded-full blur-2xl animate-float"></div>
        <div class="absolute bottom-1/3 left-1/4 w-40 h-40 bg-cyan-400/20 rounded-full blur-2xl animate-float-delayed"></div>
    </div>

    <div class="w-full max-w-5xl relative z-10">
        {{-- Main card with glassmorphism effect --}}
        <div
            class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-white/30 dark:border-gray-700/50 transform hover:scale-[1.01] transition-transform duration-500">
            <div class="grid grid-cols-1 md:grid-cols-2 min-h-150">
                {{-- Section Formulaire (Gauche) --}}
                <div class="flex flex-col p-8 lg:p-12">
                    {{-- Logo et Titre --}}
                    <div class="mb-8 text-center animate-fade-in">
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl shadow-lg mb-4 relative group hover:scale-110 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-400 via-purple-400 to-pink-400 rounded-2xl blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <i class="bi bi-mortarboard text-white text-3xl relative z-10 group-hover:rotate-12 transition-transform duration-300"></i>
                        </div>
                        <h1 class="text-4xl font-black bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">Bienvenue</h1>
                        <p class="text-gray-600 dark:text-gray-400 text-lg">Connectez-vous à votre compte</p>
                    </div>

                    {{-- Messages d'erreur globaux --}}
                    @error('login')
                        <div
                            class="mb-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-600 dark:border-red-500 rounded-lg p-4 animate-shake">
                            <div class="flex items-start gap-3">
                                <i
                                    class="bi bi-exclamation-triangle-fill text-red-600 dark:text-red-400 text-lg mt-0.5"></i>
                                <p class="text-sm text-red-700 dark:text-red-300 font-medium">{{ $message }}</p>
                            </div>
                        </div>
                    @enderror

                    {{-- Alerte de blocage --}}
                    @if ($isLocked && $lockoutTime)
                        <div
                            class="mb-4 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-600 dark:border-yellow-500 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <i
                                    class="bi bi-shield-lock-fill text-yellow-600 dark:text-yellow-400 text-lg mt-0.5"></i>
                                <div>
                                    <p class="text-sm font-bold text-yellow-900 dark:text-yellow-200 mb-1">Compte
                                        temporairement bloqué</p>
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">Veuillez patienter
                                        {{ ceil($lockoutTime / 60) }} minute(s)</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Formulaire --}}
                    <form wire:submit.prevent="login" class="space-y-5 flex-1">
                        {{-- Identifiant --}}
                        <div class="animate-slide-up" style="animation-delay: 0.1s;">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-person mr-1"></i>Identifiant
                            </label>
                            <div class="relative">
                                <input type="text" wire:model="identifier" {{ $isLocked ? 'disabled' : '' }}
                                    autofocus autocomplete="username" placeholder="Nom d'utilisateur ou email"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200 @error('identifier') @enderror disabled:opacity-50 disabled:cursor-not-allowed">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="bi bi-person text-lg"></i>
                                </div>
                            </div>
                            @error('identifier')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mot de passe --}}
                        <div class="animate-slide-up" style="animation-delay: 0.2s;">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-lock mr-1"></i>Mot de passe
                            </label>
                            <div class="relative">
                                <input type="{{ $showPassword ? 'text' : 'password' }}" wire:model="password"
                                    {{ $isLocked ? 'disabled' : '' }} autocomplete="current-password"
                                    placeholder="Votre mot de passe"
                                    class="w-full pl-12 pr-12 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-900 dark:text-gray-100 transition-all duration-200 @error('password') @enderror disabled:opacity-50 disabled:cursor-not-allowed">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="bi bi-lock text-lg"></i>
                                </div>
                                <button type="button" wire:click="togglePasswordVisibility"
                                    {{ $isLocked ? 'disabled' : '' }}
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="bi bi-eye{{ $showPassword ? '-slash' : '' }} text-lg"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Se souvenir de moi & Mot de passe oublié --}}
                        <div class="flex items-center justify-between animate-slide-up" style="animation-delay: 0.3s;">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="remember" {{ $isLocked ? 'disabled' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Se souvenir de moi</span>
                            </label>
                            <button type="button" wire:click="goToForgotPassword"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors">
                                Mot de passe oublié ?
                            </button>
                        </div>

                        {{-- Tentatives restantes --}}
                        @if ($remainingAttempts !== null && $remainingAttempts > 0 && $remainingAttempts < 3)
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-600 dark:border-blue-500 rounded-lg p-4">
                                <div class="flex items-center gap-3">
                                    <i class="bi bi-info-circle-fill text-blue-600 dark:text-blue-400 text-lg"></i>
                                    <p class="text-sm text-blue-700 dark:text-blue-300">Il vous reste
                                        {{ $remainingAttempts }} tentative(s)</p>
                                </div>
                            </div>
                        @endif

                        {{-- Bouton de connexion --}}
                        <div class="animate-slide-up" style="animation-delay: 0.4s;">
                            <button type="submit" wire:loading.attr="disabled" {{ $isLocked ? 'disabled' : '' }}
                                class="w-full py-3.5 px-6 bg-linear-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="login">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                    Se connecter
                                </span>
                                <span wire:loading wire:target="login" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    Connexion en cours...
                                </span>
                            </button>
                        </div>
                    </form>

                    {{-- Footer --}}
                    <div class="text-center mt-6 animate-fade-in" style="animation-delay: 0.5s;">
                        <div class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="bi bi-shield-check text-green-500"></i>
                            <span>Connexion sécurisée SSL</span>
                        </div>
                    </div>
                </div>

                {{-- Section Image/Description (Droite) --}}
                <div
                    class="hidden md:flex flex-col items-center justify-center bg-linear-to-br from-blue-600 via-purple-600 to-pink-600 p-12 text-white relative overflow-hidden">
                    {{-- Decorative circles --}}
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl translate-x-1/3 -translate-y-1/3">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-2xl -translate-x-1/3 translate-y-1/3">
                    </div>

                    <div class="relative z-10 text-center">
                        <div class="mb-8">
                            <div
                                class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-sm rounded-3xl shadow-2xl mb-6">
                                <i class="bi bi-book text-white text-5xl"></i>
                            </div>
                            <h2 class="text-4xl font-black mb-4">Schoola</h2>
                            <p class="text-xl text-white/90 mb-8">Système de Gestion Scolaire</p>
                        </div>

                        <div class="space-y-4 text-left max-w-sm mx-auto">
                            <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-4">
                                <div
                                    class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="bi bi-people text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold mb-1">Gestion des élèves</h3>
                                    <p class="text-sm text-white/80">Inscriptions, notes et présences</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-4">
                                <div
                                    class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="bi bi-cash-stack text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold mb-1">Finance</h3>
                                    <p class="text-sm text-white/80">Paiements et rapports financiers</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-4">
                                <div
                                    class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                    <i class="bi bi-graph-up text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold mb-1">Analyses</h3>
                                    <p class="text-sm text-white/80">Statistiques et tableaux de bord</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Copyright --}}
        <div class="text-center mt-6">
            <p class="text-sm text-white/90 font-medium">© {{ date('Y') }} Schoola. Tous droits réservés.</p>
        </div>
    </div>
</div>

