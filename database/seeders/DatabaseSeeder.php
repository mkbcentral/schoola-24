<?php

namespace Database\Seeders;

use App\Enums\RoleType;
use App\Models\Currency;
use App\Models\MultiAppLink;
use App\Models\Rate;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\SingleAppLink;
use App\Models\SubLink;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $school = School::create(['name' => 'MY SCHOOL', 'type' => 'C.S']);
        $currencies = [
            ['name' => 'USD'],
            ['name' => 'CDF'],
        ];
        Currency::insert($currencies);
        Rate::create(['amount' => 2850, 'school_id' => School::first()->id]);
        SchoolYear::create(
            [
                'name' => '2025-2026',
                'school_id' => School::first()->id,
                'is_active' => true,
            ]
        );
        Role::insert(RoleType::getRoles());
        User::create(
            [
                'name' => 'user',
                'email' => 'user@schoola.app',
                'phone' => '0971330007',
                'password' => bcrypt('password'),
                'school_id' => School::first()->id,
                'role_id' => Role::first()->id,
                'is_active' => true,
            ]
        );

        $multi_app_links = [
            [
                'id' => 1,
                'name' => 'Finance',
                'icon' => 'bi bi-box-seam-fill',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Dépenses',
                'icon' => 'bi bi-journal-text',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => 'Gestion des frais',
                'icon' => 'bi bi-wallet2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'name' => 'Navigation',
                'icon' => 'bi bi-link-45deg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'name' => 'Configuration',
                'icon' => 'bi bi-gear-fill',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'name' => 'Administration',
                'icon' => 'bi bi-person-gear',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        MultiAppLink::insert($multi_app_links);
        // Exemple de tableau PHP pour la table "sub_links"
        $sub_links = [
            [
                'id' => 3,
                'name' => 'Dépot banque',
                'icon' => 'bi bi-bank2',
                'route' => 'finance.bank',
                'multi_app_link_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Epargne',
                'icon' => 'bi bi-wallet-fill',
                'route' => 'finance.saving.money',
                'multi_app_link_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => 'Salaire',
                'icon' => 'bi bi-card-heading',
                'route' => 'finance.salary',
                'multi_app_link_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'name' => 'Gestion emprunts',
                'icon' => 'bi bi-wallet',
                'route' => 'finance.money.borrowing',
                'multi_app_link_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'name' => 'Gestion taux',
                'icon' => 'bi bi-cash-coin',
                'route' => 'finance.rate',
                'multi_app_link_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'name' => 'Depenses sur le frais',
                'icon' => 'bi bi-folder',
                'route' => 'expense.fee',
                'multi_app_link_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'name' => 'Autres dépenses',
                'icon' => 'bi bi-folder-symlink',
                'route' => 'expense.other',
                'multi_app_link_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'name' => 'Categori dépense',
                'icon' => 'bi bi-journals',
                'route' => 'expense.category',
                'multi_app_link_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 11,
                'name' => 'Source autres dép.',
                'icon' => 'bi bi-journal-bookmark-fill',
                'route' => 'expense.other.source',
                'multi_app_link_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 12,
                'name' => 'Frais inscription',
                'icon' => 'bi bi-file-earmark-plus-fill',
                'route' => 'fee.registration',
                'multi_app_link_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 13,
                'name' => 'Frais scolaire',
                'icon' => 'bi bi-file-earmark-plus-fill',
                'route' => 'fee.scolar',
                'multi_app_link_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 14,
                'name' => 'Categorie Frais insc.',
                'icon' => 'bi bi-tags-fill',
                'route' => 'category.fee.registration',
                'multi_app_link_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 15,
                'name' => 'Categorie Frais scolaire.',
                'icon' => 'bi bi-wallet2',
                'route' => 'category.fee.scolar',
                'multi_app_link_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 16,
                'name' => 'Simple menu',
                'icon' => 'bi bi-link',
                'route' => 'navigation.single',
                'multi_app_link_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 17,
                'name' => 'Multi menu',
                'icon' => 'bi bi-link',
                'route' => 'navigation.multi',
                'multi_app_link_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 18,
                'name' => 'Sous-menu',
                'icon' => 'bi bi-link',
                'route' => 'navigation.sub',
                'multi_app_link_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 19,
                'name' => 'Section',
                'icon' => 'bi bi-diagram-2',
                'route' => 'school.section',
                'multi_app_link_id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 20,
                'name' => 'Option',
                'icon' => 'bi bi-columns-gap',
                'route' => 'school.option',
                'multi_app_link_id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 21,
                'name' => 'Classe',
                'icon' => 'bi bi-houses-fill',
                'route' => 'school.class-room',
                'multi_app_link_id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 22,
                'name' => 'Gestion des utilisateurs',
                'icon' => 'bi bi-person-video2',
                'route' => 'admin.main',
                'multi_app_link_id' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 23,
                'name' => 'Gestion des roles',
                'icon' => 'bi bi-fingerprint',
                'route' => 'admin.role',
                'multi_app_link_id' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 24,
                'name' => 'Categorie salaire',
                'icon' => 'bi bi-postcard-fill',
                'route' => 'finance.salary.category',
                'multi_app_link_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 25,
                'name' => 'Gestion école',
                'icon' => 'bi bi-house-gear-fill',
                'route' => 'admin.schools',
                'multi_app_link_id' => 8,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 26,
                'name' => 'Caisse eau',
                'icon' => 'bi bi-cash-coin',
                'route' => 'finance.recipe',
                'multi_app_link_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        SubLink::insert($sub_links);

        $single_app_links = [
            [
                'id' => 1,
                'name' => 'Dashbaoard',
                'icon' => 'bi bi-bar-chart-fill',
                'route' => 'dashboard.main',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Inscriptions',
                'icon' => 'bi bi-person-fill-add',
                'route' => 'responsible.main',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Liste des élèves',
                'icon' => 'bi bi-people-fill',
                'route' => 'student.list',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Nouveau paiement',
                'icon' => 'bi bi-arrow-left-right',
                'route' => 'payment.new',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => 'Régularisation',
                'icon' => 'bi bi-wallet2',
                'route' => 'payment.regularization',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'name' => 'Liste des payments',
                'icon' => 'bi bi-people-fill',
                'route' => 'payment.rappport',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'name' => 'Control paiement',
                'icon' => 'bi bi-check2-circle',
                'route' => 'payment.control',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        SingleAppLink::insert($single_app_links);
    }
}
