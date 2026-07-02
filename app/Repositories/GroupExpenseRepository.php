<?php
namespace App\Repositories;

use App\Models\ExpenseSplit;
use App\Models\GroupExpense;
use App\Models\SettlementRequest;
use Hamcrest\Core\Set;

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
        return ExpenseSplit::where('group_expense_id', $expenseId)->get();
    }

    public function updateSplit(ExpenseSplit $expense, array $data): ExpenseSplit
    {
        $expense->update($data);
        return $expense;
    }

    public function getReceivableSummary(String $groupId)
    {
        return ExpenseSplit::join('group_expenses','expense_splits.group_expense_id','=','group_expenses.id')
                            ->where('group_expenses.group_id',$groupId)
                            ->selectRaw('group_expenses.paid_by as user_id,
                             SUM(expense_splits.amount_owed - expense_splits.amount_paid) as total_receivable')
                            ->groupBy('group_expenses.paid_by')
                            ->get()
                            ->keyBy('user_id');

    }

    public function getPayableSummary(string $groupId)
    {
        return ExpenseSplit::join('group_expenses','expense_splits.group_expense_id','=','group_expenses.id')
                            ->where('group_expenses.group_id',$groupId)
                            ->selectRaw('expense_splits.user_id,
                             SUM(expense_splits.amount_owed - expense_splits.amount_paid) as total_payable')
                            ->groupBy('expense_splits.user_id')
                            ->get()
                            ->keyBy('user_id');
    }
    //tu myar ko pyan pay ya mal a kyay detail
    public function getActiveDebtorDetail(string $userId)
    {
        return ExpenseSplit::where('user_id',$userId)
                            ->where('is_settled',false)
                            ->with(['groupExpense.payer','groupExpense.category'])
                            ->get();
    }
    //thu pay htar p thu pyan ya mal a kyay detail
    public function getActivePayerDetails(string $userId)
    {
        return ExpenseSplit::whereHas('groupExpense', function($query) use ($userId){
                $query->where('paid_by',$userId);
        })
        ->where('is_settled',false)
        ->with(['user','groupExpense'])
        ->get();
    }

 }
