@extends('layouts.app')

@section('content')

    <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500">Total Income</p>
            <p class="text-2xl font-bold text-green-500">
                {{ number_format($summary['income']) }} Ks
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500">Total Expense</p>
            <p class="text-2xl font-bold text-red-500">
                {{ number_format($summary['expense']) }} Ks
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500">Balance</p>
            <p class="text-2xl font-bold text-indigo-500">
                {{ number_format($summary['balance']) }} Ks
            </p>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold mb-4">Recent Transactions</h3>
        <table class="w-full">
            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="pb-3">Date</th>
                    <th class="pb-3">Category</th>
                    <th class="pb-3">Note</th>
                    <th class="pb-3">Amount</th>
                    <th class="pb-3">Type</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3">{{ $transaction->transaction_date }}</td>
                    <td class="py-3">{{ $transaction->category->name }}</td>
                    <td class="py-3">{{ $transaction->note ?? '-' }}</td>
                    <td class="py-3">{{ number_format($transaction->amount) }} Ks</td>
                    <td class="py-3">
                        <span class="px-2 py-1 rounded text-sm
                            {{ $transaction->type === 'income'
                                ? 'bg-green-100 text-green-600'
                                : 'bg-red-100 text-red-600' }}">
                            {{ $transaction->type }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-400">
                        No transactions yet
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
