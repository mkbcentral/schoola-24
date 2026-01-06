<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                🎨 Tailwind CSS - Page de Démonstration
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Test des composants migrés de Bootstrap vers Tailwind CSS
            </p>
        </div>

        {{-- Section: Boutons --}}
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Boutons</h2>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    {{-- Boutons standards (classes préservées) --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Classes Bootstrap préservées</h3>
                        <div class="flex flex-wrap gap-2">
                            <button class="btn btn-primary">Primary</button>
                            <button class="btn btn-secondary">Secondary</button>
                            <button class="btn btn-success">Success</button>
                            <button class="btn btn-danger">Danger</button>
                            <button class="btn btn-warning">Warning</button>
                            <button class="btn btn-info">Info</button>
                            <button class="btn btn-light">Light</button>
                            <button class="btn btn-dark">Dark</button>
                        </div>
                    </div>

                    {{-- Boutons outline --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Outline</h3>
                        <div class="flex flex-wrap gap-2">
                            <button class="btn btn-outline-primary">Outline Primary</button>
                        </div>
                    </div>

                    {{-- Tailles --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tailles</h3>
                        <div class="flex flex-wrap items-center gap-2">
                            <button class="btn btn-primary btn-sm">Small</button>
                            <button class="btn btn-primary">Normal</button>
                            <button class="btn btn-primary btn-lg">Large</button>
                        </div>
                    </div>

                    {{-- Boutons Tailwind pur --}}
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tailwind pur (nouveaux)</h3>
                        <div class="flex flex-wrap gap-2">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                                Tailwind Button
                            </button>
                            <button class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-medium py-2 px-4 rounded-lg transition">
                                Gradient
                            </button>
                            <button class="border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white font-medium py-2 px-4 rounded-lg transition">
                                Outline Tailwind
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-credit-card mr-2"></i>
                    Card avec classes préservées
                </div>
                <div class="card-body">
                    <p class="text-gray-700 dark:text-gray-300">
                        Cette card utilise les classes Bootstrap préservées dans Tailwind.
                    </p>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-sm">Action</button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500 to-purple-600">
                    <i class="bi bi-star-fill text-white mr-2"></i>
                    <span class="font-semibold text-white">Card Tailwind pur</span>
                </div>
                <div class="px-6 py-4">
                    <p class="text-gray-700 dark:text-gray-300">
                        Cette card utilise uniquement des classes Tailwind natives avec gradient.
                    </p>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <button class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition">
                        Action
                    </button>
                </div>
            </div>

            <div class="card card-hover">
                <div class="card-header">
                    <i class="bi bi-graph-up mr-2"></i>
                    Card avec hover
                </div>
                <div class="card-body">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">1,234</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total des paiements</p>
                </div>
            </div>
        </div>

        {{-- Section: Badges --}}
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Badges</h2>
            </div>
            <div class="card-body">
                <div class="flex flex-wrap gap-2">
                    <span class="badge badge-primary">Primary</span>
                    <span class="badge badge-success">Success</span>
                    <span class="badge badge-danger">Danger</span>
                    <span class="badge badge-warning">Warning</span>
                    <span class="badge badge-info">Info</span>
                </div>
            </div>
        </div>

        {{-- Section: Forms --}}
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Formulaires</h2>
            </div>
            <div class="card-body">
                <form class="space-y-4">
                    <div>
                        <label class="form-label">Nom complet</label>
                        <input type="text" class="form-control" placeholder="Jean Dupont">
                    </div>

                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" placeholder="jean@example.com">
                    </div>

                    <div>
                        <label class="form-label">Classe</label>
                        <select class="form-select">
                            <option>Sélectionner...</option>
                            <option>6ème A</option>
                            <option>5ème B</option>
                            <option>4ème C</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="terms" class="mr-2 rounded">
                        <label for="terms" class="text-sm text-gray-700 dark:text-gray-300">
                            J'accepte les conditions
                        </label>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary">Annuler</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Section: Alerts --}}
        <div class="space-y-4 mb-8">
            <div class="alert alert-success">
                <i class="bi bi-check-circle mr-2"></i>
                Opération réussie ! Les données ont été enregistrées.
            </div>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle mr-2"></i>
                Erreur ! Veuillez vérifier les champs du formulaire.
            </div>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-circle mr-2"></i>
                Attention ! Cette action est irréversible.
            </div>
            <div class="alert alert-info">
                <i class="bi bi-info-circle mr-2"></i>
                Information : La maintenance est prévue ce soir.
            </div>
        </div>

        {{-- Section: Table --}}
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Tableau</h2>
            </div>
            <div class="card-body overflow-x-auto">
                <table class="table table-striped table-hover">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Élève</th>
                            <th class="px-4 py-3 text-left">Classe</th>
                            <th class="px-4 py-3 text-right">Montant</th>
                            <th class="px-4 py-3 text-center">Statut</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 py-3">Jean Dupont</td>
                            <td class="px-4 py-3">6ème A</td>
                            <td class="px-4 py-3 text-right font-medium">50,000 FCFA</td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge badge-success">Payé</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button class="btn btn-sm btn-primary">Voir</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Marie Martin</td>
                            <td class="px-4 py-3">5ème B</td>
                            <td class="px-4 py-3 text-right font-medium">25,000 FCFA</td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge badge-warning">En attente</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button class="btn btn-sm btn-primary">Voir</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3">Paul Bernard</td>
                            <td class="px-4 py-3">4ème C</td>
                            <td class="px-4 py-3 text-right font-medium">75,000 FCFA</td>
                            <td class="px-4 py-3 text-center">
                                <span class="badge badge-danger">Impayé</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button class="btn btn-sm btn-primary">Voir</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Section: Grid & Layout --}}
        <div class="card mb-8">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Grilles & Layouts Tailwind</h2>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">123</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Élèves</div>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">45</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Paiements</div>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">12</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">En attente</div>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">8</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Impayés</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section: Dark Mode Toggle --}}
        <div class="card">
            <div class="card-header">
                <h2 class="text-xl font-semibold">Test Dark Mode</h2>
            </div>
            <div class="card-body">
                <p class="mb-4 text-gray-700 dark:text-gray-300">
                    Utilisez le bouton de thème dans la navbar pour basculer entre les modes clair et sombre.
                </p>
                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Ce bloc change de couleur automatiquement selon le thème actif.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
