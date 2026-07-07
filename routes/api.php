<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BudgetController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\GroupController;
use App\Http\Controllers\Api\V1\GroupExpenseController;
use App\Http\Controllers\Api\V1\RecurringTransactionController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\TransactionController;
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
            Route::post('transactions/{id}/receipt','uploadReceipt');
            Route::delete('transactions/{id}/receipt','deleteReceipt');
            //Accept to recurring transaction
            Route::post('transactions/{id}/accept','accept');
            Route::post('transactions/{id}/reject','reject');
        });

        //recurring transactions
        Route::controller(RecurringTransactionController::class)->group(function(){
            Route::get('recurring-transactions','index');
            Route::get('recurring-transactions/{id}','show');
            Route::post('recurring-transactions','store');
            Route::put('recurring-transactions/{id}','update');
            Route::delete('recurring-transactions/{id}','destroy');

        });

        //budget
        Route::controller(BudgetController::class)->group(function(){
            Route::get('budgets','index');
            Route::post('budgets','store');
            Route::delete('budgets/{id}', 'destroy');
            Route::put('budgets/{id}','update');

        });

        //report
        Route::get('reports/summaries',[ReportController::class,'getSummary']);
        Route::get('reports/category-breakdown',[ReportController::class,'getCategoryBreakdown']);
        Route::get('reports/annual-summary',[ReportController::class,'getAnnualSummary']);
        Route::get('reports/budgets-overview',[ReportController::class,'getBudgetOverview']);


        //group
        Route::get('groups', [GroupController::class, 'index']);
        Route::post('groups', [GroupController::class, 'store']);
        Route::get('groups/{id}', [GroupController::class, 'show']);
        Route::put('groups/{id}', [GroupController::class, 'update']);
        Route::delete('groups/{id}', [GroupController::class, 'destroy']);
        Route::post('groups/{id}/members', [GroupController::class, 'addMember']);
        Route::delete('groups/{id}/members/{userId}', [GroupController::class, 'removeMember']);
        Route::post('groups/{id}/join-code', [GroupController::class, 'generateJoinCode']);
        Route::post('groups/join', [GroupController::class, 'joinByCode']);


        //groupExpense && Split
        Route::get('groups/{groupId}/expenses', [GroupExpenseController::class, 'index']);
        Route::get('groups/expenses/splits', [GroupExpenseController::class, 'showSplitsByUser']);//to settle
        Route::post('group-expenses', [GroupExpenseController::class, 'store']);
        Route::get('group-expenses/{id}', [GroupExpenseController::class, 'show']);
        Route::put('group-expenses/{id}', [GroupExpenseController::class, 'update']);
        Route::delete('group-expenses/{id}', [GroupExpenseController::class, 'destroy']);
        //Settlement
        Route::post('expense-splits/{splitId}/claim-payment', [GroupExpenseController::class, 'claimPayment']);
        Route::post('settlement-requests/{requestId}/confirm', [GroupExpenseController::class, 'confirmPayment']);
        Route::post('settlement-requests/{requestId}/reject', [GroupExpenseController::class, 'rejectPayment']);

        //groupExpense Summary
        Route::get('groups/{groupId}/balance', [GroupExpenseController::class, 'groupBalance']);
        Route::get('groups/{groupId}/balance/{userId}/details', [GroupExpenseController::class, 'balanceDetails']);
        Route::get('groups/{groupId}/balance/{userId}/history', [GroupExpenseController::class, 'balanceHistory']);

    });

});
