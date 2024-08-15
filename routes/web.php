<?php

use App\Http\Controllers\PrintPaymentReceiptController;
use App\Livewire\Application\Admin\List\ConfigureSchoolPage;
use App\Livewire\Application\Admin\List\ListRolePage;
use App\Livewire\Application\Admin\List\ListSchoolPage;
use App\Livewire\Application\Admin\List\ListUserPage;
use App\Livewire\Application\Config\List\ListClassRoomPage;
use App\Livewire\Application\Config\List\ListOptionPage;
use App\Livewire\Application\Config\List\ListSectionPage;
use App\Livewire\Application\Dashboard\MainDashobardPage;
use App\Livewire\Application\Fee\Registration\List\ListCategoryRegistrationFeePage;
use App\Livewire\Application\Fee\Registration\List\ListRegistrationFeePage;
use App\Livewire\Application\Fee\Scolar\List\ListCategoryScolarFeePage;
use App\Livewire\Application\Fee\Scolar\MainScolarFeePage;
use App\Livewire\Application\Finance\Bank\MainBankPage;
use App\Livewire\Application\Finance\Salary\MainSalaryPage;
use App\Livewire\Application\Finance\Saving\MainSavingMoneyPage;
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

/*
Route::get('/', function () {
    return view('home');
});
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::group(['school' => ''], function () {
        Route::get('/', MainDashobardPage::class)->name('dashboard.main');
        Route::get('/responsible-sudent', ListResponsibleStudentPage::class)->name('responsible.main');
        Route::get('/students', ListStudentPage::class)->name('student.list');
        Route::get('/student/{registration}', DetailStudentPage::class)->name('student.detail');
        Route::get('section', ListSectionPage::class)->name('school.section');
        Route::get('option', ListOptionPage::class)->name('school.option');
        Route::get('class-room', ListClassRoomPage::class)->name('school.class-room');
        Route::get('registration-date/{isOld}/{dateFilter}', ListRegistrationByDatePage::class)->name('registration.date');
        Route::get('registration-month/{isOld}/{monthFilter}', ListRegistrationByMonthPage::class)->name('registration.month');
        Route::get('registration-by-class-room/{classRoomId}', ListRegistrationByClassRoomPage::class)->name('registration.by.class-room');
    });

    Route::group(['fee' => ''], function () {
        Route::get('/registration', ListRegistrationFeePage::class)->name('fee.registration');
        Route::get('/scolar', MainScolarFeePage::class)->name('fee.scolar');
        Route::get('/category-scolar', ListCategoryScolarFeePage::class)->name('category.fee.scolar');
        Route::get('/category-registration', ListCategoryRegistrationFeePage::class)->name('category.fee.registration');
    });

    Route::group(['admin' => ''], function () {
        Route::get('users', ListUserPage::class)->name('admin.main');
        Route::get('roles', ListRolePage::class)->name('admin.role');
        Route::get('schools', ListSchoolPage::class)->name('admin.schools');
        Route::get('schools', ListSchoolPage::class)->name('admin.schools');
        Route::get('configure-school{school}', ConfigureSchoolPage::class)->name('admin.school.configure');
    });
    Route::group(['payment' => ''], function () {
        Route::get('new-payment', NewPaymentPage::class)->name('payment.new');
        Route::get('regularization', MainRegularizationPaymentPage::class)->name('payment.regularization');
        Route::get('rapport', MainPaymentPage::class)->name('payment.rappport');
    });
    Route::controller(PrintPaymentReceiptController::class)->group(function () {
        Route::get('/print-receipt/{payment}', 'printReceipt')->name('print.payment.receipt');
    });

    Route::prefix('finance')->group(function () {
        Route::get('bank', MainBankPage::class)->name('finance.bank');
        Route::get('saving-money', MainSavingMoneyPage::class)->name('finance.saving.money');
        Route::get('salary', MainSalaryPage::class)->name('finance.salary');
    });
});
