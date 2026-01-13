<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

/**
 * Service Provider pour personnaliser le chemin des vues Livewire
 * 
 * Cette approche configure Livewire pour chercher les vues dans un préfixe personnalisé
 * sans avoir à modifier chaque composant individuellement.
 */
class LivewireViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // OPTION 1: Préfixe global "application"
        // Toutes les vues seront cherchées dans livewire/application/*
        // Exemple: App\Livewire\Academic\Fee\FeeManagementPage 
        //          → livewire/application/academic/fee/feemanagement-page.blade.php
        
        // Livewire::setViewPath(resource_path('views/livewire/application'));

        
        // OPTION 2: Resolver personnalisé pour gérer les cas spéciaux
        // Permet de définir des règles personnalisées de résolution de vues
        
        Livewire::component('academic.fee.fee-management-page', 
            \App\Livewire\Academic\Fee\FeeManagementPage::class);
        
        // Vous pouvez automatiser cela avec une boucle sur tous les composants
        $this->registerLivewireComponents();
    }

    /**
     * Enregistre automatiquement tous les composants Livewire 
     * avec leurs chemins de vues personnalisés
     */
    protected function registerLivewireComponents(): void
    {
        // Mapping des composants vers leurs vues
        $components = [
            \App\Livewire\Academic\Fee\FeeManagementPage::class 
                => 'livewire.application.v2.fee.fee-management-page',
            
            \App\Livewire\Academic\Fee\MainScolarFeePage::class 
                => 'livewire.application.fee.scolar.main-scolar-fee-page',
            
            \App\Livewire\Academic\Student\StudentInfoPage::class 
                => 'livewire.application.student.student-info-page-tailwind',
            
            \App\Livewire\Academic\Student\DetailStudentPage::class 
                => 'livewire.application.student.detail-student-page',
            
            \App\Livewire\Academic\Student\ListStudentDebtPage::class 
                => 'livewire.application.v2.report.list-student-debt-page-tailwind',
            
            \App\Livewire\Financial\Dashboard\FinancialDashboardPage::class 
                => 'livewire.application.dashboard.finance.financial-dashboard-page',
            
            \App\Livewire\Financial\Expense\ExpenseManagementPage::class 
                => 'livewire.application.v2.expense.expense-management-page',
            
            \App\Livewire\Financial\Expense\Settings\ExpenseSettingsPage::class 
                => 'livewire.application.finance.expense.settings.expense-settings-page',
            
            \App\Livewire\Financial\Payment\QuickPaymentPage::class 
                => 'livewire.application.payment.quick-payment-page-tailwind',
            
            \App\Livewire\Financial\Payment\PaymentListPage::class 
                => 'livewire.application.payment.payment-list-page-tailwind',
            
            \App\Livewire\Financial\Payment\Report\PaymentReportPage::class 
                => 'livewire.application.payment.report.payment-report-page-tailwind',
            
            // Ajoutez tous les autres composants ici...
        ];

        // Enregistrement automatique
        foreach ($components as $class => $view) {
            $alias = str_replace('\\', '.', 
                strtolower(str_replace('App\\Livewire\\', '', $class)));
            
            // Livewire::component($alias, $class);
        }
    }
}
