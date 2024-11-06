<?php

use App\Http\Controllers\Api\Expense\ExpenseFeeController;
use App\Http\Controllers\Api\Expense\ListCategoryExpenseController;
use App\Http\Controllers\Api\Payment\MakePaymentController;
use App\Http\Controllers\Api\Payment\PaymentRepportPaymentController;
use App\Http\Controllers\Api\Student\StudentCounterController;
use App\Http\Controllers\Api\Student\StudentPaymentStatusController;
use App\Http\Controllers\Api\User\AuthUserController;
use App\Http\Controllers\Api\User\LoginController;
use App\Http\Controllers\Api\User\LogoutController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(StudentPaymentStatusController::class)->group(function () {
        Route::get('payment/status/{code}/{categoryFeeId}/{month}', 'checkStudentHasPaid');
        Route::get('fee/categories', 'getListCategoryFee');
        Route::get('fee/scolar', 'getScolarFeesByCategory');
    });

    Route::controller(PaymentRepportPaymentController::class)->group(function () {
        Route::get('payment/report/date/{date}', 'getPaymentByDate');
        Route::get('payment/report/month/{month}', 'getPaymentByMonth');
        Route::get('student/payments/{code}', action: 'getStudentPayment');
    });

    Route::post('payment/make-payment/{code}',MakePaymentController::class);

    Route::controller(StudentCounterController::class)->group(function () {
        Route::get('student/count', 'countOldAndNewStudent');
        Route::get('student/count/section', action: 'countBySection');
        Route::get('student/count/class-room/{optionId}', 'countByClasseRoom');
        Route::get('student/options', 'getListOption');
    });

    Route::prefix('expense')->group(function () {
        Route::controller(ExpenseFeeController::class)->group(function () {
            Route::post('date/{date}', 'getExpenseByDate');
            Route::post('month/{month}', 'getExpenseByMonth');
        });
        Route::get('categories', ListCategoryExpenseController::class);
    });


});


Route::prefix('user')->group(function () {
    Route::post('/login', LoginController::class);
    Route::get('auth-user',AuthUserController::class)->middleware('auth:sanctum');
    Route::get('/logout', LogoutController::class)->middleware('auth:sanctum');
});
