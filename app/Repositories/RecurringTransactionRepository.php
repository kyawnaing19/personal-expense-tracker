<?php
namespace App\Repositories;
use App\Models\RecurringTransaction;
use Carbon\Carbon;

class RecurringTransactionRepository
{
    public function getAllByUser(string $userId,array $filter=[]){
        $query=RecurringTransaction::where('user_id',$userId);

        if(!empty($filter['category_id']))
            {
                $query->where('category_id',$filter['category_id']);
            }
        if(!empty($filter['type']))
            {
                $query->where('type',$filter['type']);
            }
        if(!empty($filter['is_active']))
            {
                $query->where('is_active',$filter['is_active']);
            }
        return $query->orderBy('next_run_date', 'desc')->get();
    }
    public function findById(string $id,string $userId)
    {
        return RecurringTransaction::where('id',$id)
                            ->where('user_id',$userId)
                            ->first();
    }
    public function create(array $data)
    {
        return RecurringTransaction::create($data);
    }

    public function update(RecurringTransaction $recurringTransaction, array $data)
    {
        $recurringTransaction->update($data);
        return $recurringTransaction;
    }

    public function delete(RecurringTransaction $recurringTransaction)
    {
        return $recurringTransaction->delete();
    }

    public function getDueRecurringTransactions()
    {
        return RecurringTransaction::where('is_active', true)
                                    ->where('next_run_date', '<=', Carbon::today())
                                    ->get();
    }
}
