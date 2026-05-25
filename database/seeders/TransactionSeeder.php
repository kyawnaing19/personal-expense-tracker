<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Fetch a user and categories to link the transactions
    $user = \App\Models\User::first();
    $categories = \App\Models\Category::all();

    $transactions = [
        [
            'user_id' => $user->id,
            'category_id' => $categories->where('name', 'salary')->first()->id,
            'type' => 'income',
            'amount' => 5000000,
            'note' => 'Monthly Salary Payment',
            'transaction_date' => now()->format('Y-m-d'),
        ],
        [
            'user_id' => $user->id,
            'category_id' => $categories->where('name', 'food')->first()->id,
            'type' => 'expense',
            'amount' => 50000,
            'note' => 'Lunch at KFC',
            'transaction_date' => now()->subDay()->format('Y-m-d'),
        ],
        [
            'user_id' => $user->id,
            'category_id' => $categories->where('name', 'travel')->first()->id,
            'type' => 'expense',
            'amount' => 20000,
            'note' => 'Grab Ride',
            'transaction_date' => now()->subDays(2)->format('Y-m-d'),
        ],
        [
            'user_id' => $user->id,
            'category_id' => $categories->where('name', 'pocket-money')->first()->id,
            'type' => 'income',
            'amount' => 100000,
            'note' => 'Gift from Mom',
            'transaction_date' => now()->format('Y-m-d'),
        ],
        [
            'user_id' => $user->id,
            'category_id' => $categories->where('name', 'skin-care')->first()->id,
            'type' => 'expense',
            'amount' => 45000,
            'note' => 'Face Wash and Sunscreen',
            'transaction_date' => now()->subDays(3)->format('Y-m-d'),
        ],
        [
            'user_id' => $user->id,
            'category_id' => $categories->where('name', 'food')->first()->id,
            'type' => 'expense',
            'amount' => 120000,
            'note' => 'Weekly Groceries',
            'transaction_date' => now()->subDays(4)->format('Y-m-d'),
        ],
        [
            'user_id' => $user->id,
            'category_id' => $categories->where('name', 'bonus-money')->first()->id,
            'type' => 'income',
            'amount' => 500000,
            'note' => 'Project Bonus',
            'transaction_date' => now()->subDays(10)->format('Y-m-d'),
        ],
        [
            'user_id' => $user->id,
            'category_id' => $categories->where('name', 'travel')->first()->id,
            'type' => 'expense',
            'amount' => 15000,
            'note' => 'Bus Ticket',
            'transaction_date' => now()->subDays(5)->format('Y-m-d'),
        ],
        [
            'user_id' => $user->id,
            'category_id' => $categories->where('name', 'food')->first()->id,
            'type' => 'expense',
            'amount' => 35000,
            'note' => 'Dinner with friends',
            'transaction_date' => now()->subDays(6)->format('Y-m-d'),
        ],
        [
            'user_id' => $user->id,
            'category_id' => $categories->where('name', 'skin-care')->first()->id,
            'type' => 'expense',
            'amount' => 80000,
            'note' => 'Moisturizer refill',
            'transaction_date' => now()->subDays(7)->format('Y-m-d'),
        ],
    ];

    foreach ($transactions as $transaction) {
        \App\Models\Transaction::create($transaction);
    }
}

}
