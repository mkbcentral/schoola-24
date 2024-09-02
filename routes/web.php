<?php

use App\Http\Controllers\PrintPaymentController;
use App\Http\Controllers\PrintPaymentReceiptController;
use App\Http\Controllers\SchoolDataPrinterController;
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
use App\Livewire\Application\Dashboard\MainDashobardPage;
use App\Livewire\Application\Fee\Registration\List\ListCategoryRegistrationFeePage;
use App\Livewire\Application\Fee\Registration\List\ListRegistrationFeePage;
use App\Livewire\Application\Fee\Scolar\List\ListCategoryScolarFeePage;
use App\Livewire\Application\Fee\Scolar\MainScolarFeePage;
use App\Livewire\Application\Finance\Bank\MainBankPage;
use App\Livewire\Application\Finance\Borrowing\MainMoneyBorrowingPage;
use App\Livewire\Application\Finance\Expense\MainCateoryExpensePage;
use App\Livewire\Application\Finance\Expense\MainExpensePage;
use App\Livewire\Application\Finance\Expense\MainOtherExpensePage;
use App\Livewire\Application\Finance\Expense\MainOtherSourceExpensePage;
use App\Livewire\Application\Finance\Rate\MainRatePage;
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
use App\Livewire\Application\Student\DetailStudentPage;
use App\Livewire\Application\Student\List\ListResponsibleStudentPage;
use App\Livewire\Application\Student\List\ListStudentPage;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', MainDashobardPage::class)->name('dashboard.main');
    //Routes load on registration group
    Route::prefix('registration')->group(function () {
        Route::get('/responsible-sudent', ListResponsibleStudentPage::class)->name('responsible.main');
        Route::get('/students', ListStudentPage::class)->name('student.list');
        Route::get('/student/{registration}', DetailStudentPage::class)->name('student.detail');
        Route::get('registration-date/{isOld}/{dateFilter}', ListRegistrationByDatePage::class)->name('registration.date');
        Route::get('registration-month/{isOld}/{monthFilter}', ListRegistrationByMonthPage::class)->name('registration.month');
        Route::get('registration-by-class-room/{classRoomId}', ListRegistrationByClassRoomPage::class)->name('registration.by.class-room');
    });
    //Routes work on confiiguration group
    Route::prefix('configuration')->group(function () {
        Route::get('section', ListSectionPage::class)->name('school.section');
        Route::get('option', ListOptionPage::class)->name('school.option');
        Route::get('class-room', ListClassRoomPage::class)->name('school.class-room');
    });
    //Routes work on sttings school fees
    Route::prefix('fee-setting')->group(function () {
        Route::get('/registration', ListRegistrationFeePage::class)->name('fee.registration');
        Route::get('/scolar', MainScolarFeePage::class)->name('fee.scolar');
        Route::get('/category-scolar', ListCategoryScolarFeePage::class)->name('category.fee.scolar');
        Route::get('/category-registration', ListCategoryRegistrationFeePage::class)->name('category.fee.registration');
    });
    //Routes work on administration
    Route::prefix('administration')->group(function () {
        Route::get('user-profile', UserProfilePage::class)->name('admin.user.profile');
        Route::get('users', ListUserPage::class)->name('admin.main');
        Route::get('roles', ListRolePage::class)->name('admin.role');
        Route::get('schools', ListSchoolPage::class)->name('admin.schools');
        Route::get('attach-single-menu/{user}', AttacheSingleMenuToUserPage::class)->name('admin.attach.single.menu');
        Route::get('attach-multi-menu/{user}', AttachMultiAppLinkToUserPage::class)->name('admin.attach.multi.menu');
        Route::get('attach-sub-menu/{user}', AttacheSubMenuToUserPage::class)->name('admin.attach.sub.menu');
        Route::get('schools', ListSchoolPage::class)->name('admin.schools');
        Route::get('configure-school/{school}', ConfigureSchoolPage::class)->name('admin.school.configure');
    });
    //Routes work on payments
    Route::prefix('payment')->group(function () {
        Route::get('new-payment', NewPaymentPage::class)->name('payment.new');
        Route::get('regularization', MainRegularizationPaymentPage::class)->name('payment.regularization');
        Route::get('rapport', MainPaymentPage::class)->name('payment.rappport');
        Route::get('control', MainControlPaymentPage::class)->name('payment.control');
    });
    //Route work on finance
    Route::prefix('finance')->group(function () {
        Route::get('bank', MainBankPage::class)->name('finance.bank');
        Route::get('saving-money', MainSavingMoneyPage::class)->name('finance.saving.money');
        Route::get('salary', MainSalaryPage::class)->name('finance.salary');
        Route::get('money-borrowing', MainMoneyBorrowingPage::class)->name('finance.money.borrowing');
        Route::get('rate', MainRatePage::class)->name('finance.rate');
    });
    //Routes work on expense
    Route::prefix('expense')->group(function () {
        Route::get('category', MainCateoryExpensePage::class)->name('expense.category');
        Route::get('other-source', MainOtherSourceExpensePage::class)->name('expense.other.source');
        Route::get('fee', MainExpensePage::class)->name('expense.fee');
        Route::get('other', MainOtherExpensePage::class)->name('expense.other');
    });
    //Routes work on navigation
    Route::prefix('navigation')->group(function () {
        Route::get('single', MainSingleAppLinkPage::class)->name('navigation.single');
        Route::get('multi', MainMultiAppLinkPage::class)->name('navigation.multi');
        Route::get('sub', MainSubLinkPage::class)->name('navigation.sub');
    });
    //Routes work to print receipt
    Route::controller(PrintPaymentReceiptController::class)->group(function () {
        Route::get('/print-receipt/{payment}', 'printReceipt')->name('print.payment.receipt');
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
            Route::get('students-by-date/{date}/{isOld}/{sortAsc}', 'printListStudentByDate')
                ->name('print.students.by.date');
            Route::get('students-by-month/{month}/{isOld}/{sortAsc}', 'printListStudentByMonth')
                ->name('print.students.by.month');
        });
        /**
         * Route d'impression de paiemnts
         */
        Route::controller(PrintPaymentController::class)->group(function () {
            Route::get('payemets-by-date/{date}/{categoryFeeId}/{feeId}/{sectionid}/{optionid}/{classRoomId}', 'printPaymentsByDate')->name('print.payment.date');
            Route::get('payemets-by-month/{month}/{categoryFeeId}/{feeId}/{sectionid}/{optionid}/{classRoomId}', 'printPaymentsByMonth')->name('print.payment.month');
            Route::get('payemets-slip-by-date/{date}', 'printPaymentSlipByDate')->name('print.payment.slip.date');
            Route::get('payemets-slip-by-month/{month}', 'printPaymentSlipByMonth')->name('print.payment.slip.month');
        });
    });
});
