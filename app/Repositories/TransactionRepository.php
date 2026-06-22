<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;

class TransactionRepository
{

    public function getAllByUser(string $userId,array $filter=[]){
        $query=Transaction::where('user_id',$userId);
        if(!empty($filter['month']))
            {
                $query->whereMonth('transaction_date',$filter['month']);
            }
        if(!empty($filter['year']))
            {
                $query->whereYear('transaction_date',$filter['year']);
            }
        if(!empty($filter['category_id']))
            {
                $query->where('category_id',$filter['category_id']);
            }
        if(!empty($filter['type']))
            {
                $query->where('type',$filter['type']);
            }
        return $query->orderBy('transaction_date', 'desc')->get();


    }
    public function findById(string $id,string $userId)
    {
        $transaction=Transaction::where('id',$id)
                            ->where('user_id',$userId)
                            ->first();

        return ['category_name' => $transaction->category->name,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'transaction_date' => $transaction->transaction_date,
                'description' => $transaction->note,
                'recurring_id' => $transaction->recurring_id,
                'id' => $transaction->id,
                'receipt_path' => $transaction->receipt_path,
        ];


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
