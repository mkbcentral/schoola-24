<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\PaymentRepportPaymentController;
use App\Http\Controllers\Api\StudentCounterController;
use App\Http\Controllers\Api\StudentPaymentStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(StudentPaymentStatusController::class)->group(function () {
        Route::get('payment/status/{code}/{categoryFeeId}/{month}', 'checkStudentHasPaied');
        Route::get('fee/categories', 'getListCategoryFee');
    });

    Route::controller(PaymentRepportPaymentController::class)->group(function () {
        Route::get('payment/repport/date/{date}', 'getPaymentbyDate');
        Route::get('payment/repport/month/{month}', 'getPaymentbyMonth');
    });

    Route::controller(StudentCounterController::class)->group(function () {
        Route::get('student/count', 'countOldAndNewStudent');
        Route::get('student/count/section', action: 'countBySection');
        Route::get('student/count/class-room/{optionId}', 'countByClasseRoom');
        Route::get('student/options', 'getListOption');
    });
});


Route::prefix('user')->group(function () {
    Route::post('/login', LoginController::class);
    Route::get('/logout', LogoutController::class)->middleware('auth:sanctum');
});
