<?php
namespace App\Repositories;

use App\Models\ExpenseSplit;
use App\Models\GroupExpense;

 class GroupExpenseRepository
 {
    public function getAllByGroup(string $groupId)
    {
        return GroupExpense::where('group_id',$groupId)
                            ->with(['payer','category','splits.user'])
                            ->orderBy('expense_date','desc')
                            ->get();
    }

    public function findById(string $id): ?GroupExpense
    {
        return GroupExpense::with(['payer','category','splits.user'])->find($id);
    }

    public function create(array $data):GroupExpense
    {
        return GroupExpense::create($data);
    }

    public function update(GroupExpense $expense, array $data)
    {
        $expense->update($data);
        return $expense;


    }
    public function delete(GroupExpense $expense): bool
    {
        $expense->delete();
        return true;
    }

    public function createSplits(string $expenseId, array $splits)
    {
        foreach($splits as $split)
        ExpenseSplit::create([
            'group_expense_id'=>$expenseId,
            'user_id'=>$split['user_id'],
            'amount_owed'=>$split['amount_owed']
        ]);
    }

    public function deleteSplits(string $expenseId): void
    {
        ExpenseSplit::where('group_expense_id',$expenseId)->delete();
    }

    public function findSplit(string $expenseId, string $userId):?ExpenseSplit
    {
       return ExpenseSplit::where('group_expense_id',$expenseId)
                            ->where('user_id',$userId)
                            ->first();
    }

    public function findSplitById(string $splitId): ?ExpenseSplit
    {
        return ExpenseSplit::find($splitId);
    }

    public function getSplitsByExpense(string $expenseId)
    {
        return ExpenseSplit::where('group_Expense_id', $expenseId)->get();
    }

    public function updateSplit(ExpenseSplit $expense, array $data): ExpenseSplit
    {
        $expense->update($data);
        return $expense;
    }

 }
