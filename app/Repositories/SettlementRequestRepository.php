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

    //User(Debtor)'s confirm-setlled history
    public function getConfirmedAsDebtor(string $userId,string $groupId)
    {
        return SettlementRequest::where('claimed_by',$userId)
                                ->where('status','confirmed')
                                ->whereHas('expenseSplit.groupExpense', function($query) use ($groupId) {
                                $query->where('group_id', $groupId);
                                })
                                ->with(['expenseSplit.groupExpense.payer','expenseSplit.groupExpense.category'])
                                ->orderBy('confirmed_at','desc')
                                ->get();
    }

    //User(payer) comfirm-history from user(debtor)' setlle Request
    public function getConfirmedAsPayer(string $userId,string $groupId)
    {
        return SettlementRequest::whereHas('expenseSplit.groupExpense', function ($query) use ($userId, $groupId) {
                $query->where('paid_by', $userId)
                      ->where('group_id', $groupId);
                })
                ->where('status','confirmed')
                ->with(['expenseSplit.user','expenseSplit.groupExpense'])
                ->orderBy('confirmed_at','desc')
                ->get();
    }

    //(paid_by)  requests
    public function getIncomingRequests(string $userId, string $status = null)
    {
        return SettlementRequest::whereHas('expenseSplit.groupExpense', function ($query) use ($userId) {
            $query->where('paid_by', $userId);
        })
        ->when($status, fn($q) => $q->where('status', $status))
        ->with([
            'claimant',
            'expenseSplit.groupExpense.group',
            'expenseSplit.groupExpense.category',
        ])
        ->orderBy('created_at', 'desc')
        ->get();
    }

    //  (claimant) requests
    public function getOutgoingRequests(string $userId, string $status = null)
    {
        return SettlementRequest::where('claimed_by', $userId)
        ->when($status, fn($q) => $q->where('status', $status))
        ->with([
            'expenseSplit.groupExpense.payer',
            'expenseSplit.groupExpense.group',
            'expenseSplit.groupExpense.category',
        ])
        ->orderBy('created_at', 'desc')
        ->get();
    }

}
