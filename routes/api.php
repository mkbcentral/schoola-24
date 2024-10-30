<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\PaymentRepportPaymentController;
use App\Http\Controllers\Api\StudentCounterController;
use App\Http\Controllers\Api\StudentPaymentStatusController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MakePaymentController;
use App\Http\Controllers\Api\AuthUserController;

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(StudentPaymentStatusController::class)->group(function () {
        Route::get('payment/status/{code}/{categoryFeeId}/{month}', 'checkStudentHasPaid');
        Route::get('fee/categories', 'getListCategoryFee');
        Route::get('fee/scolar', 'getScolarFeesByCategory');
    });

    Route::controller(PaymentRepportPaymentController::class)->group(function () {
        Route::get('payment/report/date/{date}', 'getPaymentbyDate');
        Route::get('payment/report/month/{month}', 'getPaymentbyMonth');
        Route::get('student/payments/{code}', action: 'getStudentPayment');
    });

    Route::post('payment/make-payment/{code}',MakePaymentController::class);

    Route::controller(StudentCounterController::class)->group(function () {
        Route::get('student/count', 'countOldAndNewStudent');
        Route::get('student/count/section', action: 'countBySection');
        Route::get('student/count/class-room/{optionId}', 'countByClasseRoom');
        Route::get('student/options', 'getListOption');
    });


});


Route::prefix('user')->group(function () {
    Route::post('/login', LoginController::class);
    Route::get('auth-user',AuthUserController::class)->middleware('auth:sanctum');
    Route::get('/logout', LogoutController::class)->middleware('auth:sanctum');
});
