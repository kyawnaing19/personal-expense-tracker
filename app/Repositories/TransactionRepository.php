<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;

class TransactionRepository
{

    public function getAllByUser(string $userId, array $filters = [])
    {
        $query = Transaction::where('user_id', $userId)
                            ->where('status', 'confirmed');

        if (!empty($filters['from']) && !empty($filters['to'])) {
        $query->whereBetween('created_at', [
            $filters['from'] . ' 00:00:00',
            $filters['to'] . ' 23:59:59'
        ]);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getRecurringTransactionsByUser(string $userID)
    {
        return Transaction::where('user_id', $userID)
                        ->where('status', 'pending')
                        ->with('category')
                        ->get();
    }
    public function findById(string $id,string $userId)
    {
        return Transaction::where('id',$id)
                            ->where('user_id',$userId)
                            ->first();
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function update(Transaction $transaction,array $data)
    {
        $transaction->update($data);
        return $transaction;
    }

    public function delete(Transaction $transaction)
    {
        $transaction->delete();
        return true;
    }
}
