<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;

// class DashboardController extends Controller
// {
    public function __construct(
        private TransactionService $transactionService
    ) {}

    public function index(Request $request)
    {
        $userId = auth()->user()->id;


        $transactions = $this->transactionService->getAll($userId, [
            'month' => now()->month,
            'year' => now()->year,
        ]);


        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');

        return view('dashboard', [
            'transactions' => $transactions->take(6),
            'summary' => [
                'income' => $income,
                'expense' => $expense,
                'balance' => $income - $expense,
            ],
        ]);
    }
//}
