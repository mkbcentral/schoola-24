<?php

/**
 * Routes V3 - Nouvelles fonctionnalités et améliorations
 * 
 * Ce fichier contient toutes les routes pour les composants de la version V3
 * avec des interfaces modernisées et des fonctionnalités améliorées.
 */

use App\Livewire\Application\V3\MyUpdate;
use App\Livewire\Application\V3\Payment\PaymentManagementPage;
use Illuminate\Support\Facades\Route;

 Route::get('/', MyUpdate::class)->name('main')->lazy();
    // ==================== PAYMENT ROUTES ====================
    
    /**
     * Gestion des paiements V3
     * Interface modernisée avec recherche d'élève, formulaire dynamique et liste interactive
     */
    Route::prefix('payment')->name('payment.')->group(function () {
        
        // Page principale de gestion des paiements
        Route::get('/manage', PaymentManagementPage::class)
            ->name('manage')
            ->lazy();
        
        // Routes futures pour extensions V3 des paiements
        // Route::get('/analytics', PaymentAnalyticsPage::class)->name('analytics')->lazy();
        // Route::get('/bulk-import', PaymentBulkImportPage::class)->name('bulk-import')->lazy();
        
    });
    
    // ==================== FUTURE V3 ROUTES ====================
    
    /**
     * Routes futures pour d'autres modules V3
     * Décommenter et adapter selon les besoins
     */
    
    // Student Management V3
    // Route::prefix('student')->name('student.')->group(function () {
    //     Route::get('/manage', StudentManagementPageV3::class)->name('manage')->lazy();
    // });
    
    // Fee Management V3
    // Route::prefix('fee')->name('fee.')->group(function () {
    //     Route::get('/manage', FeeManagementPageV3::class)->name('manage')->lazy();
    // });
    
    // Reports V3
    // Route::prefix('reports')->name('reports.')->group(function () {
    //     Route::get('/financial', FinancialReportV3::class)->name('financial')->lazy();
    //     Route::get('/students', StudentReportV3::class)->name('students')->lazy();
    // });
    