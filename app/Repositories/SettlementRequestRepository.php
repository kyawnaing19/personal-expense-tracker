<?php
namespace App\Repositories;

use App\Models\SettlementRequest;

class SettlementRequestRepository
{
    public function create(array $data): SettlementRequest
    {
        return SettlementRequest::create($data);
    }
    public function findById(string $id): SettlementRequest
    {
        return SettlementRequest::with(['expenseSplit','claimant'])->find($id);
    }

    public function findPendingByClaimant(string $splitId, string $claimBy): ?SettlementRequest
    {
        return SettlementRequest::where('expense_split_id', $splitId)
                                ->where('claimed_by',$claimBy)
                                ->where('status','pending')
                                ->first();
    }

    public function update(SettlementRequest $request, array $data): SettlementRequest
    {
        $request->update($data);
        return $request;
    }

    public function getPendingByExpense(string $expenseId)
    {
        return SettlementRequest::whereHas('expenseSplit', function($query) use($expenseId){
            $query->where('group_expense_id', $expenseId)
            ->where('status','pending')
            ->with('claimant')
            ->get();
        });
    }

}
