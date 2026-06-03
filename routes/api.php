<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BudgetController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // Public
    Route::post('auth/google', [AuthController::class, 'googleLogin']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/fcm-token', [AuthController::class, 'updateFcmToken']);
        // Category
        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('categories/{id}', [CategoryController::class, 'show']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::post('categories', [CategoryController::class, 'store']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

        // Transactions
        Route::controller(TransactionController::class)->group(function () {
            Route::get('transactions', 'index');
            Route::post('transactions', 'store');
            Route::get('transactions/{id}', 'show');
            Route::put('transactions/{id}', 'update');
            Route::delete('transactions/{id}', 'destroy');
        });

        Route::controller(BudgetController::class)->group(function(){
            Route::get('budgets','index');
            Route::post('budgets','store');
            Route::delete('budgets/{id}', 'destroy');
            Route::put('budgets/{id}','update');

        });

    });

});
