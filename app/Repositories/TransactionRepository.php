<?php
namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;

class TransactionRepository
{
    public function getAllByUser(string $userId)
    {
        return Transaction::where('user_id',$userId)->get();

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
