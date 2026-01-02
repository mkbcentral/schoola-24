<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModuleFeature;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createModules();
    }

    /**
     * Créer les modules avec leurs fonctionnalités
     */
    private function createModules(): void
    {
        $modulesData = [
            [
                'name' => 'Gestion des Paiements',
                'code' => 'payment',
                'description' => 'Module complet pour gérer les paiements des élèves avec rapports détaillés',
                'icon' => 'bi bi-cash-stack',
                'price' => 50000,
                'sort_order' => 1,
                'features' => [
                    ['name' => 'Nouveau paiement', 'url' => 'payment.quick', 'icon' => 'bi bi-plus-lg'],
                    ['name' => 'Liste des paiements', 'url' => 'payment.list', 'icon' => 'bi bi-list-ul'],
                    ['name' => 'Rapports de paiements', 'url' => 'report.payments', 'icon' => 'bi bi-bar-chart-fill'],
                ],
            ],
            [
                'name' => 'Gestion des Dépenses',
                'code' => 'expense',
                'description' => 'Suivez et gérez toutes les dépenses de votre école avec catégorisation',
                'icon' => 'bi bi-receipt',
                'price' => 40000,
                'sort_order' => 2,
                'features' => [
                    ['name' => 'Gérer les dépenses', 'url' => 'expense.manage', 'icon' => 'bi bi-list-ul'],
                    ['name' => 'Paramètres des dépenses', 'url' => 'expense.settings', 'icon' => 'bi bi-gear-fill'],
                    ['name' => 'Catégories de dépenses', 'url' => 'expense.category', 'icon' => 'bi bi-tags-fill'],
                    ['name' => 'Autres sources', 'url' => 'expense.other.source', 'icon' => 'bi bi-diagram-3-fill'],
                ],
            ],
            [
                'name' => 'Rapports Financiers',
                'code' => 'financial_reports',
                'description' => 'Rapports financiers avancés avec analyses et prévisions',
                'icon' => 'bi bi-graph-up',
                'price' => 60000,
                'sort_order' => 3,
                'features' => [
                    ['name' => 'Tableau de bord financier', 'url' => 'finance.dashboard', 'icon' => 'bi bi-pie-chart-fill'],
                    ['name' => 'Rapport de comparaison', 'url' => 'reports.comparison', 'icon' => 'bi bi-calculator-fill'],
                    ['name' => 'Prévisions', 'url' => 'reports.forecast', 'icon' => 'bi bi-graph-up-arrow'],
                    ['name' => 'Trésorerie', 'url' => 'reports.treasury', 'icon' => 'bi bi-coin'],
                    ['name' => 'Rentabilité', 'url' => 'reports.profitability', 'icon' => 'bi bi-bar-chart-line-fill'],
                ],
            ],
            [
                'name' => 'Gestion des Stocks',
                'code' => 'stock',
                'description' => 'Gérez l\'inventaire et les mouvements de stocks de votre école',
                'icon' => 'bi bi-box-seam-fill',
                'price' => 55000,
                'sort_order' => 4,
                'features' => [
                    ['name' => 'Tableau de bord stocks', 'url' => 'stock.dashboard', 'icon' => 'bi bi-graph-up'],
                    ['name' => 'Catalogue des articles', 'url' => 'stock.main', 'icon' => 'bi bi-box'],
                    ['name' => 'Inventaire', 'url' => 'stock.inventory', 'icon' => 'bi bi-clipboard-check-fill'],
                    ['name' => 'Catégories', 'url' => 'stock.categories', 'icon' => 'bi bi-tags-fill'],
                    ['name' => 'Audit des stocks', 'url' => 'stock.audit', 'icon' => 'bi bi-search'],
                ],
            ],
            [
                'name' => 'Gestion des Élèves',
                'code' => 'students',
                'description' => 'Gérez les informations, inscriptions et dettes des élèves',
                'icon' => 'bi bi-mortarboard-fill',
                'price' => 45000,
                'sort_order' => 5,
                'features' => [
                    ['name' => 'Informations élèves', 'url' => 'student.info', 'icon' => 'bi bi-person-vcard-fill'],
                    ['name' => 'Dettes élèves', 'url' => 'rapport.student.debt', 'icon' => 'bi bi-file-earmark-text-fill'],
                    ['name' => 'Inscriptions', 'url' => 'registration.v2.index', 'icon' => 'bi bi-person-plus-fill'],
                ],
            ],
            [
                'name' => 'Configuration',
                'code' => 'configuration',
                'description' => 'Configurez les paramètres de votre école (sections, frais, utilisateurs)',
                'icon' => 'bi bi-gear-wide-connected',
                'price' => 35000,
                'sort_order' => 6,
                'features' => [
                    ['name' => 'Configuration générale', 'url' => 'config.manage', 'icon' => 'bi bi-sliders'],
                    ['name' => 'Sections et options', 'url' => 'config.section.manage', 'icon' => 'bi bi-layers-fill'],
                    ['name' => 'Gestion des frais', 'url' => 'fee.manage', 'icon' => 'bi bi-cash'],
                    ['name' => 'Gestion des utilisateurs', 'url' => 'user.manage', 'icon' => 'bi bi-people-fill'],
                ],
            ],
            [
                'name' => 'Gestion des Écoles',
                'code' => 'schools_management',
                'description' => 'Gérez les écoles de la plateforme (Admin uniquement)',
                'icon' => 'bi bi-building',
                'price' => 0, // Gratuit pour les admins
                'sort_order' => 7,
                'features' => [
                    ['name' => 'Liste des écoles', 'url' => 'v2.schools.index', 'icon' => 'bi bi-list-ul'],
                ],
            ],
        ];

        foreach ($modulesData as $moduleData) {
            // Extraire les fonctionnalités
            $features = $moduleData['features'];
            unset($moduleData['features']);

            // Créer le module
            $module = Module::create($moduleData);

            // Créer les fonctionnalités
            foreach ($features as $index => $feature) {
                ModuleFeature::create([
                    'module_id' => $module->id,
                    'name' => $feature['name'],
                    'url' => $feature['url'],
                    'icon' => $feature['icon'],
                    'sort_order' => $index,
                ]);
            }
        }

        $this->command->info('Modules créés avec succès !');
    }
}
