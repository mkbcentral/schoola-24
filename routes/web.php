<?php

use App\Http\Controllers\Payment\PaymentReportPdfController;
use App\Http\Controllers\PrintPaymentController;
use App\Http\Controllers\PrintPaymentReceiptController;
use App\Http\Controllers\SchoolDataPrinterController;
use App\Http\Controllers\StockExportController;
use App\Http\Controllers\StudentPrinterController;
use App\Livewire\Application\Admin\AttacheSingleMenuToUserPage;
use App\Livewire\Application\Admin\AttacheSubMenuToUserPage;
use App\Livewire\Application\Admin\AttachMultiAppLinkToUserPage;
use App\Livewire\Application\Admin\List\ConfigureSchoolPage;
use App\Livewire\Application\Admin\List\ListRolePage;
use App\Livewire\Application\Admin\List\ListSchoolPage;
use App\Livewire\Application\Admin\List\ListUserPage;
use App\Livewire\Application\Admin\School\SchoolListPage;
use App\Livewire\Application\Admin\School\CreateSchoolPage;
use App\Livewire\Application\Admin\School\EditSchoolPage;
use App\Livewire\Application\Admin\School\SchoolUsersPage;
use App\Livewire\Application\Admin\UserProfilePage;
use App\Livewire\Application\Config\List\ListClassRoomPage;
use App\Livewire\Application\Config\List\ListOptionPage;
use App\Livewire\Application\Config\List\ListSectionPage;
use App\Livewire\Application\Config\List\SchoolYearList;
use App\Livewire\Application\Dashboard\Finance\FinancialDashboardPage;
use App\Livewire\Application\Dashboard\MainDashobardPage;
use App\Livewire\Application\Fee\Registration\List\ListCategoryRegistrationFeePage;
use App\Livewire\Application\Fee\Registration\List\ListRegistrationFeePage;
use App\Livewire\Application\Fee\Scolar\List\ListCategoryScolarFeePage;
use App\Livewire\Application\Fee\Scolar\MainScolarFeePage;
use App\Livewire\Application\Finance\Bank\MainBankPage;
use App\Livewire\Application\Finance\Borrowing\MainMoneyBorrowingPage;
use App\Livewire\Application\Finance\Budget\BudgetForecastPage;
use App\Livewire\Application\Finance\Expense\ExpenseManagementPage;
use App\Livewire\Application\Finance\Expense\ExpenseManagementPageRefactored;
use App\Livewire\Application\Finance\Expense\MainCateoryExpensePage;
use App\Livewire\Application\Finance\Expense\MainExpensePage;
use App\Livewire\Application\Finance\Expense\MainOtherExpensePage;
use App\Livewire\Application\Finance\Expense\MainOtherSourceExpensePage;
use App\Livewire\Application\Finance\Expense\Settings\ExpenseSettingsPage;
use App\Livewire\Application\Finance\Rate\MainRatePage;
use App\Livewire\Application\Finance\Rate\ManageExchangeRatePage;
use App\Livewire\Application\Finance\Recipe\MainOtherRecipePage;
use App\Livewire\Application\Finance\Salary\List\ListCategorySalaryPage;
use App\Livewire\Application\Finance\Salary\MainSalaryPage;
use App\Livewire\Application\Finance\Saving\MainSavingMoneyPage;
use App\Livewire\Application\Navigation\MainMultiAppLinkPage;
use App\Livewire\Application\Navigation\MainSingleAppLinkPage;
use App\Livewire\Application\Navigation\MainSubLinkPage;
use App\Livewire\Application\Payment\MainControlPaymentPage;
use App\Livewire\Application\Payment\MainPaymentPage;
use App\Livewire\Application\Payment\NewPaymentPage;
use App\Livewire\Application\Payment\PaymentListPage;
use App\Livewire\Application\Payment\QuickPaymentPage;
use App\Livewire\Application\Payment\Reguralization\MainRegularizationPaymentPage;
use App\Livewire\Application\Payment\Report\PaymentReportPage;
use App\Livewire\Application\Registration\List\ListRegistrationByClassRoomPage;
use App\Livewire\Application\Registration\List\ListRegistrationByDatePage;
use App\Livewire\Application\Registration\List\ListRegistrationByMonthPage;
use App\Livewire\Application\Registration\MainRegistrationPage;
use App\Livewire\Application\Report\MissingRevenueReportPage;
use App\Livewire\Application\Report\ReportStudentEnrollmentReport;
use App\Livewire\Application\Report\StudentSpecialStatusReportPage;
use App\Livewire\Application\AllReport\ComparisonReportPage;
use App\Livewire\Application\AllReport\ForecastReportPage;
use App\Livewire\Application\AllReport\TreasuryReportPage;
use App\Livewire\Application\AllReport\ProfitabilityReportPage;
use App\Livewire\Application\Payment\List\ListReportPaymentPage;
use App\Livewire\Application\Setting\MainSettingPage;
use App\Livewire\Application\Stock\ArticleCategoryManager;
use App\Livewire\Application\Stock\ArticleInventoryManager;
use App\Livewire\Application\Stock\ArticleStockManager;
use App\Livewire\Application\Stock\ArticleStockMovementManager;
use App\Livewire\Application\Stock\AuditHistoryViewer;
use App\Livewire\Application\Stock\StockDashboard;
use App\Livewire\Application\Student\DetailStudentPage;
use App\Livewire\Application\Student\List\ListResponsibleStudentPage;
use App\Livewire\Application\Student\List\ListStudentPage;
use App\Livewire\Application\Student\StudentInfoPage;
use App\Livewire\Application\V2\Registration\RegistrationListPage;
use App\Livewire\Application\V2\Report\ListStudentDebtPage;
use App\Livewire\Application\V2\School\SchoolManagementPage;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('payment/list', PaymentListPage::class)->name('payment.list')->lazy();
    Route::get('payment/pdf/generate', [\App\Http\Controllers\Payment\PaymentPdfController::class, 'generate'])->name('payments.pdf');
    Route::get('payment/quick', QuickPaymentPage::class)->name('payment.quick')->lazy();
    Route::get('report/payments', PaymentReportPage::class)->name('report.payments')->lazy();
    Route::get('expense/manage', ExpenseManagementPageRefactored::class)->name('expense.manage')->lazy();
    Route::get('expense/settings', ExpenseSettingsPage::class)->name('expense.settings')->lazy();


    Route::get('infos', StudentInfoPage::class)->name('student.info')->lazy();

    Route::get('/', FinancialDashboardPage::class)->name('finance.dashboard')->lazy();


    Route::get('student-debt', ListStudentDebtPage::class)
        ->name('rapport.student.debt')->lazy();


    // Routes work on financial reports
    Route::prefix('reports')->group(function () {
        Route::get('comparison', ComparisonReportPage::class)->name('reports.comparison')->lazy();
        Route::get('forecast', ForecastReportPage::class)->name('reports.forecast')->lazy();
        Route::get('treasury', TreasuryReportPage::class)->name('reports.treasury')->lazy();
        Route::get('profitability', ProfitabilityReportPage::class)->name('reports.profitability')->lazy();
    });

    Route::get('/scolar', MainScolarFeePage::class)->name('fee.scolar')->lazy();



    // Routes de stock réservées au SCHOOL_GUARD
    Route::middleware(['stock.guard'])->prefix('stock')->group(function () {});
    Route::get('dashboard', StockDashboard::class)->name('stock.dashboard')->lazy();
    Route::get('catalog', ArticleStockManager::class)->name('stock.main')->lazy();
    Route::get('categories', ArticleCategoryManager::class)->name('stock.categories')->lazy();
    Route::get('inventory', ArticleInventoryManager::class)->name('stock.inventory')->lazy();
    Route::get('audit', AuditHistoryViewer::class)->name('stock.audit')->lazy();
    Route::get('audit/{articleId}', AuditHistoryViewer::class)->name('stock.audit.article')->lazy();
    Route::get('movements/{article}', ArticleStockMovementManager::class)->name('app.stock.movements')->lazy();
    Route::get('export/movements-pdf/{article}', [StockExportController::class, 'exportMovementsPdf'])->name('stock.export.movements.pdf');
    /*
    Route::middleware(['access.chercker'])->group(function () {
        Route::get('/', MainDashobardPage::class)->name('dashboard.main')->lazy();
        Route::get('/responsables', ListResponsibleStudentPage::class)->name('responsable.main')->lazy();
        // Routes load on registration group
        Route::prefix('registration')->group(function () {
            Route::get('/new', MainRegistrationPage::class)->name('responsible.main')->lazy();
            Route::get('/day', ListRegistrationByDatePage::class)->name('registration.day')->lazy();
            Route::get('/students', ListStudentPage::class)->name('student.list')->lazy();
        });

        // Routes work on confiiguration group
        Route::prefix('configuration')->group(function () {
            Route::get('section', ListSectionPage::class)->name('school.section')->lazy();
            Route::get('option', ListOptionPage::class)->name('school.option')->lazy();
            Route::get('class-room', ListClassRoomPage::class)->name('school.class-room')->lazy();
            Route::get('school-year', SchoolYearList::class)->name('school.year')->lazy();
        });
        // Routes work on sttings school fees
        Route::prefix('fee-setting')->group(function () {
            Route::get('/registration', ListRegistrationFeePage::class)->name('fee.registration')->lazy();
            //Route::get('/scolar', MainScolarFeePage::class)->name('fee.scolar')->lazy();
            Route::get('/category-scolar', ListCategoryScolarFeePage::class)->name('category.fee.scolar')->lazy();
            Route::get('/category-registration', ListCategoryRegistrationFeePage::class)->name('category.fee.registration')->lazy();
        });
        // Routes work on administration
        Route::prefix('administration')->group(function () {
            Route::get('users', ListUserPage::class)->name('admin.main')->lazy();
            Route::get('roles', ListRolePage::class)->name('admin.role')->lazy();
            Route::get('schools', ListSchoolPage::class)->name('admin.schools')->lazy();

            // Routes pour la gestion des écoles (APP_ADMIN uniquement)
            Route::middleware(['can:viewAny,App\Models\School'])->prefix('school-management')->group(function () {
                Route::get('/', SchoolListPage::class)->name('admin.schools.index')->lazy();
                Route::get('/create', CreateSchoolPage::class)->name('admin.schools.create')->lazy();
                Route::get('/{schoolId}/edit', EditSchoolPage::class)->name('admin.schools.edit')->lazy();

            });
        });
        // Routes work on payments
        Route::prefix('payment')->group(function () {
            Route::get('new-payment', NewPaymentPage::class)->name('payment.new')->lazy();

            Route::get('regularization', MainRegularizationPaymentPage::class)->name('payment.regularization')->lazy();
            Route::get('report', MainPaymentPage::class)->name('payment.rappport')->lazy();
            Route::get('control', MainControlPaymentPage::class)->name('payment.control')->lazy();
        });
        // Route work on finance
        Route::prefix('finance')->group(function () {
            //Route::get('dashboard', \App\Livewire\Application\Dashboard\Finance\FinancialDashboardPage::class)->name('finance.dashboard')->lazy();
            Route::get('bank', MainBankPage::class)->name('finance.bank')->lazy();
            Route::get('saving-money', MainSavingMoneyPage::class)->name('finance.saving.money')->lazy();
            Route::get('salary', MainSalaryPage::class)->name('finance.salary')->lazy();
            Route::get('salary/category', ListCategorySalaryPage::class)->name('finance.salary.category')->lazy();
            Route::get('money-borrowing', MainMoneyBorrowingPage::class)->name('finance.money.borrowing')->lazy();
            Route::get('rate', MainRatePage::class)->name('finance.rate')->lazy();
            Route::get('rate/manage', ManageExchangeRatePage::class)->name('rate.manage')->lazy();
            Route::get('other-recipes', MainOtherRecipePage::class)->name('finance.recipe')->lazy();
            Route::get('budget-forecast', BudgetForecastPage::class)->name('finance.budget.forecast')->lazy();
        });
        // Routes work on expense

        Route::prefix('expense')->group(function () {
            Route::get('category', ExpenseSettingsPage::class)->name('expense.category')->lazy();
            Route::get('other-source', ExpenseSettingsPage::class)->name('expense.other.source')->lazy();
            Route::get('fee', ExpenseManagementPageRefactored::class)->name('expense.fee')->lazy();
            Route::get('other', ExpenseManagementPageRefactored::class)->name('expense.other')->lazy();
        });

        // Routes work on navigation
        Route::prefix('navigation')->group(function () {
            Route::get('single', MainSingleAppLinkPage::class)->name('navigation.single')->lazy();
            Route::get('multi', MainMultiAppLinkPage::class)->name('navigation.multi')->lazy();
            Route::get('sub', MainSubLinkPage::class)->name('navigation.sub')->lazy();
        });

        Route::prefix('rapport')->group(function () {
            // repport des effectifs Route::get('students-by-class-room/{classRoomId}', ListStudentByClassRoomPage::class)->name('students.by.class.room')->lazy();
            Route::get('student-enrollment', ReportStudentEnrollmentReport::class)->name('rapport.student.enrollment')->lazy();
            Route::get('student-special-status', StudentSpecialStatusReportPage::class)->name('rapport.student.special.status')->lazy();
            Route::get('missing-revenue', MissingRevenueReportPage::class)
                ->name('missing.revenue.report');
        });
    });
    */

    // Route::get('report/payments', PaymentReportPage::class)->name('report.payments')->lazy();

    // Routes for payment report PDF export
    Route::controller(PaymentReportPdfController::class)->group(function () {
        Route::get('payment/report/pdf/download', 'download')->name('payment-report.pdf.download');
        Route::get('payment/report/pdf/preview', 'preview')->name('payment-report.pdf.preview');
    });

    // Routes for student information
    // Route::get('/', StudentInfoPage::class)->name('student.info')->lazy();

    Route::get('registration/student/{registration}', DetailStudentPage::class)->name('student.detail')->lazy();

    Route::get('registration/registration-date/{isOld}/{dateFilter}', ListRegistrationByDatePage::class)->name('registration.date')->lazy();
    Route::get('registration/registration-month/{isOld}/{monthFilter}', ListRegistrationByMonthPage::class)->name('registration.month')->lazy();
    Route::get('registration/registration-by-class-room/{classRoomId}', ListRegistrationByClassRoomPage::class)->name('registration.by.class-room')->lazy();

    Route::get('administration/attach-single-menu/{user}', AttacheSingleMenuToUserPage::class)->name('admin.attach.single.menu')->lazy();
    Route::get('administration/attach-multi-menu/{user}', AttachMultiAppLinkToUserPage::class)->name('admin.attach.multi.menu')->lazy();
    Route::get('administration/attach-sub-menu/{user}', AttacheSubMenuToUserPage::class)->name('admin.attach.sub.menu')->lazy();
    Route::get('administration/configure-school/{school}', ConfigureSchoolPage::class)->name('admin.school.configure')->lazy();
    Route::get('administration/user-profile', UserProfilePage::class)->name('admin.user.profile');
    // Routes work to print receipt
    Route::controller(PrintPaymentReceiptController::class)->group(function () {
        Route::get('/print-receipt/{payment}', 'printReceipt')->name('print.payment.receipt');
        // print.reguralization.receipt
        Route::get('/print-reguralization-receipt/{paymentRegularization}', 'printRegReceipt')->name('print.reguralization.receipt');
    });

    Route::prefix('settings')->group(function () {
        Route::get('main', MainSettingPage::class)->name('settings.main')->lazy();
    });

    Route::prefix('print')->group(function () {
        /**
         * Route d'imprssion des éffectifs par (toutes les options,classe,date,mois)
         */
        Route::controller(SchoolDataPrinterController::class)->group(function () {
            Route::get('class-room-by-option', 'printStudentNumbersPerClassRoom')
                ->name('class.room.by.option');
            Route::get('students-by-class-room/{classRoomId}/{sortAsc}', 'printListStudeForClassRoom')
                ->name('print.students.by.classRomm');
            Route::get('students-cards-by-class-room/{classRoomId}/{sortAsc}', 'printListStudentCardsForClassRoom')
                ->name('print.students.cards.by.classRomm');
            Route::get('students-by-date/{date}/{isOld}/{sortAsc}', 'printListStudentByDate')
                ->name('print.students.by.date');
            Route::get('students-by-month/{month}/{isOld}/{sortAsc}', 'printListStudentByMonth')
                ->name('print.students.by.month');
        });
        Route::controller(StudentPrinterController::class)->group(function () {
            Route::get('student-payemnts/{registration}', 'printStudentPayments')->name('print.student.payemnts');
            Route::get('student-payemnt-by-classroom/{classRoom}', 'printStudentPaymentsByClassRoom')
                ->name('print.student.payemnts.by.classroom');
            // print all student list
            Route::get('all-student-list/{optionId}/{classRoomId}', 'printAllStudentList')
                ->name('print.all.student.list');
        });
        /**
         * Route d'impression de paiemnts
         */
        Route::controller(PrintPaymentController::class)->group(function () {
            Route::get('payments-by-date/{date}/{categoryFeeId}/{feeId}/{sectionid}/{optionid}/{classRoomId}', 'printPaymentsByDate')->name('print.payment.date');
            Route::get('payments-by-month/{month}/{categoryFeeId}/{feeId}/{sectionid}/{optionid}/{classRoomId}', 'printPaymentsByMonth')->name('print.payment.month');
            Route::get('payments-slip-by-date/{date}', 'printPaymentSlipByDate')->name('print.payment.slip.date');
            Route::get('payments-slip-by-month/{month}', 'printPaymentSlipByMonth')->name('print.payment.slip.month');
        });
    });


    Route::get('/registration/v2', RegistrationListPage::class)
        ->name('registration.v2.index')->lazy();
    Route::get('config/manage/v2', \App\Livewire\Application\V2\Configuration\ConfigurationManagementPage::class)->name('config.manage')->lazy();
    Route::get('config/section-manage/v2', \App\Livewire\Application\V2\Configuration\SectionManagementPage::class)->name('config.section.manage')->lazy();
    Route::get('fee/manage/v2', \App\Livewire\Application\V2\Fee\FeeManagementPage::class)->name('fee.manage')->lazy();
    Route::get('user/manage/v2', \App\Livewire\Application\V2\User\UserManagementPage::class)->name('user.manage')->lazy();

    Route::get('schools/manage/v2', SchoolManagementPage::class)
        ->name('v2.schools.index')
        ->middleware('can:viewAny,App\Models\School');
    Route::get('schools/{schoolId}/users/v2', SchoolUsersPage::class)->name('admin.schools.users')->lazy();
});
