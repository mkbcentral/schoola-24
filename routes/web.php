<?php

use App\Http\Controllers\PrintPaymentController;
use App\Http\Controllers\PrintPaymentReceiptController;
use App\Http\Controllers\SchoolDataPrinterController;
use App\Http\Controllers\StudentPrinterController;
use App\Livewire\Application\Admin\AttacheSingleMenuToUserPage;
use App\Livewire\Application\Admin\AttacheSubMenuToUserPage;
use App\Livewire\Application\Admin\AttachMultiAppLinkToUserPage;
use App\Livewire\Application\Admin\List\ConfigureSchoolPage;
use App\Livewire\Application\Admin\List\ListRolePage;
use App\Livewire\Application\Admin\List\ListSchoolPage;
use App\Livewire\Application\Admin\List\ListUserPage;
use App\Livewire\Application\Admin\UserProfilePage;
use App\Livewire\Application\Config\List\ListClassRoomPage;
use App\Livewire\Application\Config\List\ListOptionPage;
use App\Livewire\Application\Config\List\ListSectionPage;
use App\Livewire\Application\Config\List\SchoolYearList;
use App\Livewire\Application\Dashboard\MainDashobardPage;
use App\Livewire\Application\Fee\Registration\List\ListCategoryRegistrationFeePage;
use App\Livewire\Application\Fee\Registration\List\ListRegistrationFeePage;
use App\Livewire\Application\Fee\Scolar\List\ListCategoryScolarFeePage;
use App\Livewire\Application\Fee\Scolar\MainScolarFeePage;
use App\Livewire\Application\Finance\Bank\MainBankPage;
use App\Livewire\Application\Finance\Borrowing\MainMoneyBorrowingPage;
use App\Livewire\Application\Finance\Budget\BudgetForecastPage;
use App\Livewire\Application\Finance\Expense\MainCateoryExpensePage;
use App\Livewire\Application\Finance\Expense\MainExpensePage;
use App\Livewire\Application\Finance\Expense\MainOtherExpensePage;
use App\Livewire\Application\Finance\Expense\MainOtherSourceExpensePage;
use App\Livewire\Application\Finance\Rate\MainRatePage;
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
use App\Livewire\Application\Payment\Reguralization\MainRegularizationPaymentPage;
use App\Livewire\Application\Registration\List\ListRegistrationByClassRoomPage;
use App\Livewire\Application\Registration\List\ListRegistrationByDatePage;
use App\Livewire\Application\Registration\List\ListRegistrationByMonthPage;
use App\Livewire\Application\Registration\MainRegistrationPage;
use App\Livewire\Application\Report\ReportStudentEnrollmentReport;
use App\Livewire\Application\Setting\MainSettingPage;
use App\Livewire\Application\Student\DetailStudentPage;
use App\Livewire\Application\Student\List\ListResponsibleStudentPage;
use App\Livewire\Application\Student\List\ListStudentByResponsiblePage;
use App\Livewire\Application\Student\List\ListStudentPage;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware(['access.chercker'])->group(function () {
        Route::get('/', MainDashobardPage::class)->name('dashboard.main')->lazy();
        Route::get('/responsables', ListResponsibleStudentPage::class)->name('responsable.main')->lazy();
        //Routes load on registration group
        Route::prefix('registration')->group(function () {
            Route::get('/new', MainRegistrationPage::class)->name('responsible.main')->lazy();
            Route::get('/day', ListRegistrationByDatePage::class)->name('registration.day')->lazy();
            Route::get('/students', ListStudentPage::class)->name('student.list')->lazy();
        });
        //Routes work on confiiguration group
        Route::prefix('configuration')->group(function () {
            Route::get('section', ListSectionPage::class)->name('school.section')->lazy();
            Route::get('option', ListOptionPage::class)->name('school.option')->lazy();
            Route::get('class-room', ListClassRoomPage::class)->name('school.class-room')->lazy();
            Route::get('school-year', SchoolYearList::class)->name('school.year')->lazy();
        });
        //Routes work on sttings school fees
        Route::prefix('fee-setting')->group(function () {
            Route::get('/registration', ListRegistrationFeePage::class)->name('fee.registration')->lazy();
            Route::get('/scolar', MainScolarFeePage::class)->name('fee.scolar')->lazy();
            Route::get('/category-scolar', ListCategoryScolarFeePage::class)->name('category.fee.scolar')->lazy();
            Route::get('/category-registration', ListCategoryRegistrationFeePage::class)->name('category.fee.registration')->lazy();
        });
        //Routes work on administration
        Route::prefix('administration')->group(function () {
            Route::get('users', ListUserPage::class)->name('admin.main')->lazy();
            Route::get('roles', ListRolePage::class)->name('admin.role')->lazy();
            Route::get('schools', ListSchoolPage::class)->name('admin.schools')->lazy();
        });
        //Routes work on payments
        Route::prefix('payment')->group(function () {
            Route::get('new-payment', NewPaymentPage::class)->name('payment.new')->lazy();
            Route::get('regularization', MainRegularizationPaymentPage::class)->name('payment.regularization')->lazy();
            Route::get('rapport', MainPaymentPage::class)->name('payment.rappport')->lazy();
            Route::get('control', MainControlPaymentPage::class)->name('payment.control')->lazy();
        });
        //Route work on finance
        Route::prefix('finance')->group(function () {
            Route::get('bank', MainBankPage::class)->name('finance.bank')->lazy();
            Route::get('saving-money', MainSavingMoneyPage::class)->name('finance.saving.money')->lazy();
            Route::get('salary', MainSalaryPage::class)->name('finance.salary')->lazy();
            Route::get('salary/category', ListCategorySalaryPage::class)->name('finance.salary.category')->lazy();
            Route::get('money-borrowing', MainMoneyBorrowingPage::class)->name('finance.money.borrowing')->lazy();
            Route::get('rate', MainRatePage::class)->name('finance.rate')->lazy();
            Route::get('other-recipes', MainOtherRecipePage::class)->name('finance.recipe')->lazy();
            Route::get('budget-forecast', BudgetForecastPage::class)->name('finance.budget.forecast')->lazy();
        });
        //Routes work on expense
        Route::prefix('expense')->group(function () {
            Route::get('category', MainCateoryExpensePage::class)->name('expense.category')->lazy();
            Route::get('other-source', MainOtherSourceExpensePage::class)->name('expense.other.source')->lazy();
            Route::get('fee', MainExpensePage::class)->name('expense.fee')->lazy();
            Route::get('other', MainOtherExpensePage::class)->name('expense.other')->lazy();
        });
        //Routes work on navigation
        Route::prefix('navigation')->group(function () {
            Route::get('single', MainSingleAppLinkPage::class)->name('navigation.single')->lazy();
            Route::get('multi', MainMultiAppLinkPage::class)->name('navigation.multi')->lazy();
            Route::get('sub', MainSubLinkPage::class)->name('navigation.sub')->lazy();
        });

        Route::prefix('rapport')->group(function () {
            //repport des effectifs Route::get('students-by-class-room/{classRoomId}', ListStudentByClassRoomPage::class)->name('students.by.class.room')->lazy();
            Route::get('student-enrollment', ReportStudentEnrollmentReport::class)->name('rapport.student.enrollment')->lazy();
        });
    });

    Route::get('registration/student/{registration}', DetailStudentPage::class)->name('student.detail')->lazy();

    Route::get('registration/registration-date/{isOld}/{dateFilter}', ListRegistrationByDatePage::class)->name('registration.date')->lazy();
    Route::get('registration/registration-month/{isOld}/{monthFilter}', ListRegistrationByMonthPage::class)->name('registration.month')->lazy();
    Route::get('registration/registration-by-class-room/{classRoomId}', ListRegistrationByClassRoomPage::class)->name('registration.by.class-room')->lazy();

    Route::get('administration/attach-single-menu/{user}', AttacheSingleMenuToUserPage::class)->name('admin.attach.single.menu')->lazy();
    Route::get('administration/attach-multi-menu/{user}', AttachMultiAppLinkToUserPage::class)->name('admin.attach.multi.menu')->lazy();
    Route::get('administration/attach-sub-menu/{user}', AttacheSubMenuToUserPage::class)->name('admin.attach.sub.menu')->lazy();
    Route::get('administration/configure-school/{school}', ConfigureSchoolPage::class)->name('admin.school.configure')->lazy();
    Route::get('administration/user-profile', UserProfilePage::class)->name('admin.user.profile');
    //Routes work to print receipt
    Route::controller(PrintPaymentReceiptController::class)->group(function () {
        Route::get('/print-receipt/{payment}', 'printReceipt')->name('print.payment.receipt');
        //print.reguralization.receipt
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
            //print all student list
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
            Route::get('payments-slip-by-month/{month}', 'printPaymentSlipByMonth')->name('print.payment.slip.month');
        });
    });
});
