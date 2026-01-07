<?php

use App\Http\Controllers\Academic\StudentDebtReportPdfController;
use App\Http\Controllers\Payment\PaymentReportPdfController;
use App\Http\Controllers\PrintPaymentReceiptController;
use App\Http\Controllers\StockExportController;
use App\Livewire\Academic\Fee\FeeManagementPage;
use App\Livewire\Academic\Fee\MainScolarFeePage;
use App\Livewire\Academic\Registration\ListRegistrationByClassRoomPage;
use App\Livewire\Academic\Registration\ListRegistrationByDatePage;
use App\Livewire\Academic\Registration\ListRegistrationByMonthPage;
use App\Livewire\Academic\Registration\RegistrationListPage;
use App\Livewire\Academic\Student\ListStudentDebtPage;
use App\Livewire\Academic\Student\DetailStudentPage;
use App\Livewire\Academic\Student\StudentInfoPage;
use App\Livewire\Admin\School\ConfigureSchoolPage;
use App\Livewire\Admin\School\SchoolManagementPage;
use App\Livewire\Admin\School\SchoolUsersPage;
use App\Livewire\Admin\User\Menu\AttacheSingleMenuToUserPage;
use App\Livewire\Admin\User\Menu\AttacheSubMenuToUserPage;
use App\Livewire\Admin\User\Menu\AttachMultiAppLinkToUserPage;
use App\Livewire\Admin\User\UserManagementPage;
use App\Livewire\Admin\User\UserProfilePage;
use App\Livewire\Configuration\Settings\MainSettingPage;
use App\Livewire\Configuration\System\ConfigurationManagementPage;
use App\Livewire\Configuration\System\SectionManagementPage;
use App\Livewire\Financial\Dashboard\FinancialDashboardPage;
use App\Livewire\Financial\Expense\ExpenseManagementPage;
use App\Livewire\Financial\Expense\Settings\ExpenseSettingsPage;
use App\Livewire\Financial\Payment\PaymentListPage;
use App\Livewire\Financial\Payment\QuickPaymentPage;
use App\Livewire\Financial\Payment\Report\PaymentReportPage;
use App\Livewire\Financial\Report\ComparisonReportPage;
use App\Livewire\Financial\Report\ForecastReportPage;
use App\Livewire\Financial\Report\ProfitabilityReportPage;
use App\Livewire\Financial\Report\TreasuryReportPage;
use App\Livewire\Inventory\ArticleCategoryManager;
use App\Livewire\Inventory\ArticleInventoryManager;
use App\Livewire\Inventory\ArticleStockManager;
use App\Livewire\Inventory\ArticleStockMovementManager;
use App\Livewire\Inventory\AuditHistoryViewer;
use App\Livewire\Inventory\StockDashboard;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {

    // ============================================================
    // DASHBOARD - Page d'accueil
    // ============================================================
    Route::get('/', FinancialDashboardPage::class)->name('finance.dashboard')->lazy();

    // ============================================================
    // PAIEMENTS - Gestion des paiements
    // ============================================================
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('list', PaymentListPage::class)->name('list')->lazy();
        Route::get('quick', QuickPaymentPage::class)->name('quick')->lazy();
        Route::get('pdf/generate', [\App\Http\Controllers\Payment\PaymentPdfController::class, 'generate'])->name('pdf');

        // Reçus de paiement
        Route::controller(PrintPaymentReceiptController::class)->group(function () {
            Route::get('print-receipt/{payment}', 'printReceipt')->name('print.receipt');
            Route::get('print-reguralization-receipt/{paymentRegularization}', 'printRegReceipt')->name('print.reguralization.receipt');
        });

        // Rapports de paiements
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/', PaymentReportPage::class)->name('payments')->lazy();
            Route::controller(PaymentReportPdfController::class)->group(function () {
                Route::get('pdf/download', 'download')->name('pdf.download');
                Route::get('pdf/preview', 'preview')->name('pdf.preview');
            });
        });
    });

    // ============================================================
    // DÉPENSES - Gestion des dépenses
    // ============================================================
    Route::prefix('expense')->name('expense.')->group(function () {
        Route::get('manage', ExpenseManagementPage::class)->name('manage')->lazy();
        Route::get('settings', ExpenseSettingsPage::class)->name('settings')->lazy();
    });

    // ============================================================
    // ÉTUDIANTS - Informations et inscriptions
    // ============================================================
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('infos', StudentInfoPage::class)->name('info')->lazy();
        Route::get('debt/list', ListStudentDebtPage::class)->name('debt.list')->lazy();

        // Export PDF des dettes étudiantes
        Route::controller(StudentDebtReportPdfController::class)->group(function () {
            Route::get('debt/pdf/download', 'download')->name('debt.pdf.download');
            Route::get('debt/pdf/preview', 'preview')->name('debt.pdf.preview');
        });

        Route::get('{registration}', DetailStudentPage::class)->name('detail')->lazy();
    });

    // ============================================================
    // INSCRIPTIONS - Gestion des inscriptions
    // ============================================================
    Route::prefix('registration')->name('registration.')->group(function () {
        // Ancienne version
        Route::get('by-date/{isOld}/{dateFilter}', ListRegistrationByDatePage::class)->name('date')->lazy();
        Route::get('by-month/{isOld}/{monthFilter}', ListRegistrationByMonthPage::class)->name('month')->lazy();
        Route::get('by-class-room/{classRoomId}', ListRegistrationByClassRoomPage::class)->name('by.class-room')->lazy();

        // Version 2
        Route::get('v2', RegistrationListPage::class)->name('v2.index')->lazy();
    });

    // ============================================================
    // FRAIS SCOLAIRES - Gestion des frais
    // ============================================================
    Route::prefix('fee')->name('fee.')->group(function () {
        Route::get('scolar', MainScolarFeePage::class)->name('scolar')->lazy();
        Route::get('manage/v2', FeeManagementPage::class)->name('manage')->lazy();
    });

    // ============================================================
    // RAPPORTS FINANCIERS - Analyses et prévisions
    // ============================================================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('comparison', ComparisonReportPage::class)->name('comparison')->lazy();
        Route::get('forecast', ForecastReportPage::class)->name('forecast')->lazy();
        Route::get('treasury', TreasuryReportPage::class)->name('treasury')->lazy();
        Route::get('profitability', ProfitabilityReportPage::class)->name('profitability')->lazy();
    });

    // ============================================================
    // STOCK - Gestion du stock (SCHOOL_GUARD requis)
    // ============================================================
    Route::middleware(['stock.guard'])->prefix('stock')->name('stock.')->group(function () {
        Route::get('dashboard', StockDashboard::class)->name('dashboard')->lazy();
        Route::get('catalog', ArticleStockManager::class)->name('main')->lazy();
        Route::get('categories', ArticleCategoryManager::class)->name('categories')->lazy();
        Route::get('inventory', ArticleInventoryManager::class)->name('inventory')->lazy();
        Route::get('audit', AuditHistoryViewer::class)->name('audit')->lazy();
        Route::get('audit/{articleId}', AuditHistoryViewer::class)->name('audit.article')->lazy();
        Route::get('movements/{article}', ArticleStockMovementManager::class)->name('movements')->lazy();
        Route::get('export/movements-pdf/{article}', [StockExportController::class, 'exportMovementsPdf'])->name('export.movements.pdf');
    });

    // ============================================================
    // CONFIGURATION - Paramètres système
    // ============================================================
    Route::prefix('config')->name('config.')->group(function () {
        Route::get('manage/v2', ConfigurationManagementPage::class)->name('manage')->lazy();
        Route::get('section-manage/v2', SectionManagementPage::class)->name('section.manage')->lazy();
    });

    // ============================================================
    // PARAMÈTRES - Configuration de l'application
    // ============================================================
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('main', MainSettingPage::class)->name('main')->lazy();
    });

    // ============================================================
    // ADMINISTRATION - Gestion des utilisateurs et écoles
    // ============================================================
    Route::prefix('administration')->name('admin.')->group(function () {
        // Gestion des menus utilisateurs
        Route::get('attach-single-menu/{user}', AttacheSingleMenuToUserPage::class)->name('attach.single.menu')->lazy();
        Route::get('attach-multi-menu/{user}', AttachMultiAppLinkToUserPage::class)->name('attach.multi.menu')->lazy();
        Route::get('attach-sub-menu/{user}', AttacheSubMenuToUserPage::class)->name('attach.sub.menu')->lazy();

        // Gestion des écoles
        Route::get('configure-school/{school}', ConfigureSchoolPage::class)->name('school.configure')->lazy();

        // Profil utilisateur
        Route::get('user-profile', UserProfilePage::class)->name('user.profile');

        // Gestion des utilisateurs
        Route::get('user/manage/v2', UserManagementPage::class)->name('user.manage')->lazy();

        // Gestion des écoles (V2)
        Route::get('schools/manage/v2', SchoolManagementPage::class)
            ->name('schools.index')
            ->middleware('can:viewAny,App\Models\School')
            ->lazy();
        Route::get('schools/{schoolId}/users/v2', SchoolUsersPage::class)->name('schools.users')->lazy();
    });
});
