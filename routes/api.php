<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\StudentPaymentStatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(StudentPaymentStatusController::class)->group(function () {
        Route::get('payment/status', 'checkStudentHasPaied');
        Route::get('fee/categories', 'getListCategoryFee');
    });
});


Route::prefix('user')->group(function () {
    Route::post('/login', LoginController::class);
});
