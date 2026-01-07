{{-- Liste des Élèves avec Dettes - Version Tailwind CSS Moderne --}}
<div x-data="{ showStudentModal: false, showFilters: @entangle('showFilters') }">
    {{-- Breadcrumb --}}
    <x-navigation.bread-crumb icon='bi bi-exclamation-triangle-fill' label="Élèves avec Dettes">
        <x-navigation.bread-crumb-item label='Dashboard' isLinked=true link="finance.dashboard" isFirst=true />
        <x-navigation.bread-crumb-item label='Rapports' />
    </x-navigation.bread-crumb>

    <x-content.main-content-page>
        <div class="space-y-4">
            {{-- En-tête --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4 mb-4">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                    <div>
                        <h2 class="text-gray-700 dark:text-gray-200 font-semibold text-lg flex items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-yellow-600 dark:text-yellow-400"></i>
                            Élèves avec Dettes
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Liste des élèves ayant des impayés de {{ $minMonthsUnpaid }} mois ou plus
                        </p>
                    </div>
                    <div class="flex gap-2 flex-wrap">
                        <button @click="showFilters = !showFilters"
                            class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-lg transition-colors flex items-center gap-2">
                            <i class="bi bi-funnel"></i>
                            <span x-text="showFilters ? 'Masquer filtres' : 'Afficher filtres'"></span>
                        </button>
                        <a href="{{ route('student.debt.pdf.preview', [
                            'section_id' => $sectionId,
                            'option_id' => $optionId,
                            'class_room_id' => $classRoomId,
                            'category_fee_id' => $categoryFeeId,
                            'min_months_unpaid' => $minMonthsUnpaid,
                            'search' => $search,
                        ]) }}"
                            target="_blank"
                            class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                            <i class="bi bi-eye"></i> Aperçu PDF
                        </a>
                        <a href="{{ route('student.debt.pdf.download', [
                            'section_id' => $sectionId,
                            'option_id' => $optionId,
                            'class_room_id' => $classRoomId,
                            'category_fee_id' => $categoryFeeId,
                            'min_months_unpaid' => $minMonthsUnpaid,
                            'search' => $search,
                        ]) }}"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                            <i class="bi bi-download"></i> Télécharger PDF
                        </a>
                    </div>
                </div>
            </div>

            {{-- Message si aucune catégorie --}}
            @if (!$categoryFeeId)
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-600 dark:border-blue-500 rounded-lg p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle-fill text-blue-600 dark:text-blue-400 text-xl"></i>
                        <p class="text-blue-800 dark:text-blue-300 text-sm">
                            Veuillez sélectionner une catégorie de frais pour afficher les données.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Filtres --}}
            <div x-show="showFilters" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden mb-4">

                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                    <h3 class="text-white font-semibold text-base flex items-center gap-2">
                        <i class="bi bi-sliders"></i> Filtres de recherche
                    </h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        {{-- Catégorie de frais --}}
                        <div class="lg:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bi bi-tag-fill"></i> Catégorie de Frais
                            </label>
                            <select wire:model.live="categoryFeeId"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Toutes les catégories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category['id'] }}">
                                        {{ $category['name'] }} ({{ $category['currency'] ?? 'USD' }})
                                    </option>
                                @endforeach
                            </select>
                            @if ($currency)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                    <i class="bi bi-currency-exchange"></i>
                                    Devise: <strong>{{ $currency }}</strong>
                                </p>
                            @endif
                        </div>

                        {{-- Section --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Section
                            </label>
                            <select wire:model.live="sectionId"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Toutes les sections</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Option --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Option
                            </label>
                            <select wire:model.live="optionId"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed"
                                @if (!$sectionId) disabled @endif>
                                <option value="">Toutes les options</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Classe --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Classe
                            </label>
                            <select wire:model.live="classRoomId"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:cursor-not-allowed"
                                @if (!$optionId) disabled @endif>
                                <option value="">Toutes les classes</option>
                                @foreach ($classRooms as $classRoom)
                                    <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Mois minimum impayés --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mois impayés (min)
                            </label>
                            <select wire:model.live="minMonthsUnpaid"
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="1">1 mois ou plus</option>
                                <option value="2">2 mois ou plus</option>
                                <option value="3">3 mois ou plus</option>
                                <option value="4">4 mois ou plus</option>
                                <option value="5">5 mois ou plus</option>
                            </select>
                        </div>

                        {{-- Recherche --}}
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Rechercher un élève
                            </label>
                            <input type="text" wire:model.live.debounce.500ms="search"
                                placeholder="Nom ou code de l'élève..."
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>

                        {{-- Bouton reset --}}
                        <div class="flex items-end">
                            <button wire:click="resetFilters"
                                class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="bi bi-x-circle"></i> Réinitialiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistiques --}}
            @if (!empty($statistics) && $categoryFeeId)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4" wire:loading.remove.delay.longer>
                    {{-- Total Élèves --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                    Total Élèves
                                </p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ $statistics['total_students'] }}
                                </h3>
                            </div>
                            <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-3">
                                <i class="bi bi-people-fill text-red-600 dark:text-red-400 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Dette Totale --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                    Dette Totale ({{ $currency }})
                                </p>
                                <h3 class="text-2xl font-bold text-red-600 dark:text-red-400">
                                    {{ number_format($statistics['total_debt_amount'], 0, ',', ' ') }}
                                </h3>
                            </div>
                            <div class="bg-yellow-100 dark:bg-yellow-900/30 rounded-lg p-3">
                                <i class="bi bi-cash-stack text-yellow-600 dark:text-yellow-400 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Mois Moyen Impayé --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                    Mois Moyen Impayé
                                </p>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format($statistics['average_months_unpaid'], 1) }}
                                </h3>
                            </div>
                            <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3">
                                <i class="bi bi-calendar-x text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Max Mois Impayé --}}
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wide mb-1">
                                    Max Mois Impayé
                                </p>
                                <h3 class="text-2xl font-bold text-red-600 dark:text-red-400">
                                    {{ $statistics['max_months_unpaid'] }}
                                </h3>
                            </div>
                            <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-3">
                                <i class="bi bi-exclamation-triangle-fill text-red-600 dark:text-red-400 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tableau des élèves --}}
            @if (!empty($studentsWithDebt) && $categoryFeeId)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden"
                    wire:loading.remove.delay.longer>
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <h3
                                class="text-gray-900 dark:text-gray-100 font-semibold text-base flex items-center gap-2">
                                Liste des élèves endettés
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                    {{ count($studentsWithDebt) }}
                                </span>
                            </h3>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        #</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Nom de l'élève</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Section/Option/Classe</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Mois Inscrip.</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Mois Dus</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Mois Payés</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Mois Impayés</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Dette ({{ $currency }})</th>
                                    <th
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($studentsWithDebt as $index => $student)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $student['student_name'] }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $student['section_name'] }} /
                                            {{ $student['option_name'] }} /
                                            {{ $student['class_room_name'] }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex flex-col gap-1">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                                    {{ $student['registration_month'] }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $student['registration_date'] }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                                {{ $student['total_months_expected'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                {{ $student['total_months_paid'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                {{ $student['months_unpaid'] }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-4 py-3 text-right text-sm font-bold text-red-600 dark:text-red-400">
                                            {{ number_format($student['total_debt_amount'], 0, ',', ' ') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button wire:click="viewStudentDetails({{ $student['student_id'] }})"
                                                class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 rounded-lg transition-colors">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif ($categoryFeeId)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-12"
                    wire:loading.remove.delay.longer>
                    <div class="text-center">
                        <div
                            class="bg-green-100 dark:bg-green-900/30 rounded-full p-6 w-24 h-24 mx-auto mb-4 flex items-center justify-center">
                            <i class="bi bi-clipboard-check text-green-600 dark:text-green-400 text-5xl"></i>
                        </div>
                        <h5 class="text-gray-700 dark:text-gray-300 font-semibold text-lg mb-2">
                            Aucun élève avec dette trouvé
                        </h5>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            Tous les élèves sont à jour avec leurs paiements ou aucun élève ne correspond aux critères
                            sélectionnés.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Modal des détails avec amélioration visuelle --}}
            @if ($selectedStudent)
                <div class="fixed inset-0 z-50 overflow-y-auto" x-show="true" x-init="showStudentModal = true"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    aria-labelledby="modal-title" role="dialog" aria-modal="true">

                    {{-- Overlay animé avec effet de flou --}}
                    <div class="fixed inset-0 bg-linear-to-br from-gray-900/85 via-red-900/40 to-purple-900/60 backdrop-blur-md transition-all"
                        x-show="true" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 backdrop-blur-none"
                        x-transition:enter-end="opacity-100 backdrop-blur-md"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 backdrop-blur-md"
                        x-transition:leave-end="opacity-0 backdrop-blur-none" @click="$wire.closeStudentDetails()">
                    </div>

                    {{-- Modal Content avec glassmorphism --}}
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="relative w-full max-w-4xl bg-white/98 dark:bg-gray-800/98 backdrop-blur-2xl rounded-3xl shadow-2xl border border-gray-200/60 dark:border-gray-700/60 overflow-hidden"
                            x-show="true" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-4" @click.stop>

                            {{-- Header --}}
                            <div
                                class="border-b border-gray-200 dark:border-gray-700 px-8 py-5 bg-gray-50 dark:bg-gray-800/50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-3">
                                            <i
                                                class="bi bi-person-badge text-gray-700 dark:text-gray-300 text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-gray-900 dark:text-gray-100 font-bold text-xl">
                                                {{ $selectedStudent['student_name'] }}</h3>
                                            <p
                                                class="text-gray-600 dark:text-gray-400 text-sm mt-1 flex items-center gap-2">
                                                <span
                                                    class="bg-gray-200 dark:bg-gray-700 px-2.5 py-0.5 rounded text-xs font-medium">
                                                    {{ $selectedStudent['student_code'] }}
                                                </span>
                                                <span>•</span>
                                                <span>{{ $selectedStudent['class_room_name'] }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <button wire:click="closeStudentDetails"
                                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 p-2">
                                        <i class="bi bi-x-lg text-xl"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Body avec sections organisées --}}
                            <div class="px-8 py-6 max-h-[calc(100vh-240px)] overflow-y-auto">
                                {{-- Section Informations Académiques --}}
                                <div class="mb-6">
                                    <h4
                                        class="text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wide mb-3 flex items-center gap-2">
                                        <i class="bi bi-mortarboard text-gray-600 dark:text-gray-400"></i>
                                        Informations Académiques
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                                                    <i class="bi bi-diagram-3 text-gray-600 dark:text-gray-400"></i>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-0.5">
                                                        Section</p>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $selectedStudent['section_name'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                                                    <i class="bi bi-bookmark text-gray-600 dark:text-gray-400"></i>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-0.5">
                                                        Option</p>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $selectedStudent['option_name'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                                                    <i class="bi bi-door-open text-gray-600 dark:text-gray-400"></i>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-0.5">
                                                        Classe</p>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $selectedStudent['class_room_name'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section Inscription --}}
                                <div class="mb-6">
                                    <h4
                                        class="text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wide mb-3 flex items-center gap-2">
                                        <i class="bi bi-calendar-check text-gray-600 dark:text-gray-400"></i>
                                        Inscription
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-1">
                                                        Date d'inscription</p>
                                                    <p
                                                        class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $selectedStudent['registration_date'] }}</p>
                                                </div>
                                                <i
                                                    class="bi bi-calendar3 text-green-600/30 dark:text-green-400/30 text-3xl"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-1">
                                                        Mois d'inscription</p>
                                                    <p
                                                        class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $selectedStudent['registration_month'] }}</p>
                                                </div>
                                                <i
                                                    class="bi bi-calendar-month text-gray-400 dark:text-gray-500 text-2xl"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section Situation Financière --}}
                                <div class="mb-6">
                                    <h4
                                        class="text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wide mb-3 flex items-center gap-2">
                                        <i class="bi bi-cash-stack text-gray-600 dark:text-gray-400"></i>
                                        Situation Financière
                                    </h4>

                                    {{-- Statistiques en ligne --}}
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3 border border-gray-200 dark:border-gray-700 text-center">
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-1">
                                                Mois Dus</p>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                                {{ $selectedStudent['total_months_expected'] }}</p>
                                        </div>
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3 border border-gray-200 dark:border-gray-700 text-center">
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-1">
                                                Mois Payés</p>
                                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                                {{ $selectedStudent['total_months_paid'] }}</p>
                                        </div>
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3 border border-gray-200 dark:border-gray-700 text-center">
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-1">
                                                Mois Impayés</p>
                                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                                                {{ $selectedStudent['months_unpaid'] }}</p>
                                        </div>
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3 border border-gray-200 dark:border-gray-700 text-center">
                                            <p
                                                class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-1">
                                                Taux</p>
                                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                                {{ $selectedStudent['total_months_expected'] > 0 ? round(($selectedStudent['total_months_paid'] / $selectedStudent['total_months_expected']) * 100) : 0 }}%
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Liste des mois impayés --}}
                                    <div
                                        class="bg-red-50 dark:bg-red-900/10 rounded-lg p-5 border border-red-200 dark:border-red-800/50 mb-4">
                                        <div class="flex items-center gap-2 mb-3">
                                            <i class="bi bi-exclamation-triangle text-red-600 dark:text-red-400"></i>
                                            <p
                                                class="text-sm text-red-900 dark:text-red-100 font-semibold uppercase tracking-wide">
                                                Mois Impayés</p>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($selectedStudent['unpaid_months'] as $month)
                                                <span
                                                    class="inline-flex items-center px-3 py-1.5 rounded text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 border border-red-200 dark:border-red-700">
                                                    <i class="bi bi-calendar-x mr-1.5"></i>
                                                    {{ $month }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Liste des mois payés --}}
                                    @if (isset($selectedStudent['paid_months']) && count($selectedStudent['paid_months']) > 0)
                                        <div
                                            class="bg-green-50 dark:bg-green-900/10 rounded-lg p-5 border border-green-200 dark:border-green-800/50">
                                            <div class="flex items-center gap-2 mb-3">
                                                <i class="bi bi-check-circle text-green-600 dark:text-green-400"></i>
                                                <p
                                                    class="text-sm text-green-900 dark:text-green-100 font-semibold uppercase tracking-wide">
                                                    Mois Payés</p>
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($selectedStudent['paid_months'] as $month)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 border border-green-200 dark:border-green-700">
                                                        <i class="bi bi-calendar-check mr-1.5"></i>
                                                        {{ $month }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Section Montants --}}
                                <div class="mb-6">
                                    <h4
                                        class="text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wide mb-3 flex items-center gap-2">
                                        <i class="bi bi-calculator text-gray-600 dark:text-gray-400"></i>
                                        Détails des Montants
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-start justify-between mb-3">
                                                <div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-1">
                                                        Montant Total Dû</p>
                                                    <p class="text-gray-500 dark:text-gray-400 text-xs">Somme à régler
                                                    </p>
                                                </div>
                                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                                                    <i class="bi bi-wallet2 text-gray-600 dark:text-gray-400"></i>
                                                </div>
                                            </div>
                                            <p class="text-3xl font-black text-red-700 dark:text-red-300">
                                                {{ number_format($selectedStudent['total_amount_due'], 0, ',', ' ') }}
                                                <span class="text-lg font-medium">{{ $currency }}</span>
                                            </p>
                                        </div>

                                        <div
                                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                            <div class="flex items-start justify-between mb-3">
                                                <div>
                                                    <p
                                                        class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wide mb-1">
                                                        Montant Payé</p>
                                                    <p class="text-gray-500 dark:text-gray-400 text-xs">Somme versée
                                                    </p>
                                                </div>
                                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                                                    <i class="bi bi-check-circle text-gray-600 dark:text-gray-400"></i>
                                                </div>
                                            </div>
                                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">
                                                {{ number_format($selectedStudent['total_amount_paid'], 0, ',', ' ') }}
                                                <span class="text-lg font-medium">{{ $currency }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Dette totale --}}
                                    <div
                                        class="mt-4 bg-red-600 dark:bg-red-700 rounded-lg p-5 border-l-4 border-red-800 dark:border-red-900">
                                        <div class="flex items-center justify-between text-white">
                                            <div>
                                                <div class="flex items-center gap-2 mb-2">
                                                    <i class="bi bi-exclamation-octagon-fill text-white text-xl"></i>
                                                    <p
                                                        class="text-white/90 font-bold uppercase tracking-wider text-sm">
                                                        Dette Totale</p>
                                                </div>
                                                <p class="text-5xl font-black text-white mb-1">
                                                    {{ number_format($selectedStudent['total_debt_amount'], 0, ',', ' ') }}
                                                </p>
                                                <p class="text-white/80 text-lg font-semibold">{{ $currency }}</p>
                                            </div>
                                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6">
                                                <i class="bi bi-exclamation-triangle text-white text-5xl"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section Contact Responsable --}}
                                @if ($selectedStudent['responsible_name'] || $selectedStudent['responsible_phone'])
                                    <div>
                                        <h4
                                            class="text-gray-900 dark:text-gray-100 font-semibold text-sm uppercase tracking-wide mb-3 flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                                <i
                                                    class="bi bi-person-lines-fill text-indigo-600 dark:text-indigo-400"></i>
                                            </div>
                                            Contact du Responsable
                                        </h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @if ($selectedStudent['responsible_name'])
                                                <div
                                                    class="bg-linear-to-br from-indigo-50 to-indigo-100/50 dark:from-indigo-900/20 dark:to-indigo-900/10 rounded-xl p-4 border border-indigo-200/50 dark:border-indigo-800/50">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="bg-indigo-100 dark:bg-indigo-900/40 rounded-lg p-2.5">
                                                            <i
                                                                class="bi bi-person text-indigo-600 dark:text-indigo-400 text-lg"></i>
                                                        </div>
                                                        <div>
                                                            <p
                                                                class="text-xs text-indigo-600 dark:text-indigo-400 font-medium uppercase tracking-wide mb-0.5">
                                                                Nom</p>
                                                            <p
                                                                class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                                {{ $selectedStudent['responsible_name'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($selectedStudent['responsible_phone'])
                                                <div
                                                    class="bg-gradient-to-br from-violet-50 to-violet-100/50 dark:from-violet-900/20 dark:to-violet-900/10 rounded-xl p-4 border border-violet-200/50 dark:border-violet-800/50">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="bg-violet-100 dark:bg-violet-900/40 rounded-lg p-2.5">
                                                            <i
                                                                class="bi bi-telephone text-violet-600 dark:text-violet-400 text-lg"></i>
                                                        </div>
                                                        <div>
                                                            <p
                                                                class="text-xs text-violet-600 dark:text-violet-400 font-medium uppercase tracking-wide mb-0.5">
                                                                Téléphone</p>
                                                            <p
                                                                class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                                {{ $selectedStudent['responsible_phone'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Footer avec gradient --}}
                            <div
                                class="bg-gradient-to-r from-gray-50 to-gray-100/80 dark:from-gray-700/80 dark:to-gray-700/60 backdrop-blur-sm px-8 py-5 border-t border-gray-200/50 dark:border-gray-600/50 flex justify-end gap-3">
                                <button wire:click="closeStudentDetails"
                                    class="px-6 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                                    <i class="bi bi-x-circle"></i>
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Indicateur de chargement --}}
            <x-v2.loading-overlay title="Chargement en cours..." subtitle="Récupération des données" />
        </div>
    </x-content.main-content-page>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
