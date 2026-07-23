<?php

namespace App\Services;

use App\Http\Resources\ExpenseSplitResource;
use App\Http\Resources\GroupExpenseResource;
use App\Http\Resources\GroupExpensesDetailResource;
use App\Repositories\GroupExpenseRepository;
use App\Repositories\GroupRepository;
use App\Repositories\SettlementRequestRepository;
use Illuminate\Support\Facades\DB;

class GroupExpenseService
{
    public function __construct(
        private GroupExpenseRepository $expenseRepository,
        private GroupRepository $groupRepository,
        private SettlementRequestRepository $settlementRequestRepository,
        private NotificationService $notificationService
    ) {}

    public function getAllByGroup(string $groupId, string $userId)
    {
        if (! $this->groupRepository->isMember($groupId, $userId)) {
            throw new \Exception('you are not a member of this group', 403);
        }

         $expenses=$this->expenseRepository->getAllByGroup($groupId);
         return GroupExpenseResource::collection($expenses);
    }

    public function findById(string $id, string $userId)
    {
        $expense = $this->expenseRepository->findById($id);
        if (! $expense) {
            throw new \Exception('the expense not found', 404);
        }
        if (! $this->groupRepository->isMember($expense->group_id, $userId)) {
            throw new \Exception('you are not a member of this group', 403);
        }

        return new GroupExpensesDetailResource($expense);
    }

    public function create(array $data, string $userId)
    {
        if (! $this->groupRepository->isMember($data['group_id'], $userId)) {
            throw new \Exception('you are not a member', 403);
        }
        $data['paid_by'] = $userId;
        $this->validateSplitdata($data);

        return DB::transaction(function () use ($data) {
            $expense = $this->expenseRepository->create([
                'group_id' => $data['group_id'],
                'paid_by' => $data['paid_by'],
                'category_id' => $data['category_id'] ?? null,
                'amount' => $data['amount'],
                'description' => $data['description'] ?? null,
                'expense_date' => $data['expense_date'],
                'split_type' => $data['split_type'],

            ]);

            $splitsData = $this->calculateSplits($expense, $data);

            $expense->update([
            'include_payer' => $splitsData['include_payer']
            ]);
            //dd($splits);
            $this->expenseRepository->createSplits($expense->id, $splitsData['splits']);


            $expense=$this->expenseRepository->findById($expense->id);
            $this->notifyExpenseMembers($expense);
            return $expense;
        });
    }

    public function update(string $id, array $data, string $userId)
    {
        $expense = $this->expenseRepository->findById($id);
        if (! $expense) {
            throw new \Exception('expense not found', 404);
        }

        $isCreator = $expense->paid_by === $userId;
        $isAdmin = $this->groupRepository->isAdmin($expense->group_id, $userId);

        if (! $isCreator && ! $isAdmin) {
            throw new \Exception('only admin or creator can edit the group expense', 403);
        }
        $this->validateSplitData(array_merge([
            'group_id' => $expense->group_id,
        ], $data));

        return DB::transaction(function () use ($expense, $data) {
            $expense = $this->expenseRepository->update($expense, [
                'category_id' => $data['category_id'] ?? $expense->category_id,
                'amount' => $data['amount'] ?? $expense->amount,
                'description' => $data['description'] ?? $expense->description,
                'expense_date' => $data['expense_date'] ?? $expense->expense_date,
                'split_type' => $data['split_type'] ?? $expense->split_type,
                'include_payer' => $data['include_payer'] ?? $expense->include_payer,

            ]);
            $this->recalculateSplits($expense, $data);

            return $this->expenseRepository->findById($expense->id);

        });
    }

    public function delete(string $id, string $userId)
    {
        $expense = $this->expenseRepository->findById($id);
        if (! $expense) {
            throw new \Exception('the expense not found', 404);
        }
        $isCreator = $expense->paid_by === $userId;
        $isAdmin = $this->groupRepository->isAdmin($expense->group_id, $userId);

        if (! $isCreator && ! $isAdmin) {
            throw new \Exception('only creator or admin can delete', 403);
        }

        return $this->expenseRepository->delete($expense);
    }

    public function getSplitsByUser(string $userId)
    {
        $groups=$this->groupRepository->getAllByUser($userId);
        if($groups->isEmpty()) {
            throw new \Exception('you are not a member of any group', 403);
        }
        $splits = $this->expenseRepository->getSplitsByUser($userId);
        return ExpenseSplitResource::collection($splits);
    }

    // Split logic
    private function calculateSplits($expense, array $data): array
    {
        if ($data['split_type'] === 'custom') {
            return $this->buildCustomSplits($data['amount'], $data['splits'],$expense->paid_by);
        }

        return $this->buildEquallySplits(
            $expense->group_id,
            $data['amount'],
            $expense->paid_by,
            $data['include_payer'] ?? true
        );

    }

    private function buildEquallySplits(string $groupId, int $amount, string $paidBy, bool $includePayer)
    {
        $group = $this->groupRepository->findById($groupId);
        $members = $group->members;

        $denominatorMembers = $includePayer
                        ? $members
                        : $members->reject(fn ($m) => $m->id == $paidBy);

        $count = $denominatorMembers->count();

        $perPerson = (int) round($amount / $count);

        $splitMembers = $members->reject(fn ($m) => $m->id === $paidBy);

        $formattedSplits = $splitMembers->map(fn ($member) => [
            'user_id' => $member->id,
            'amount_owed' => $perPerson,
        ])->values()->toArray();

        return [
        'splits' => $formattedSplits,
        'include_payer' => $includePayer,
        ];
    }

    // private function buildCustomSplits(int $totalAmount, array $splits, string $paidBy): array
    // {   $filterSplits= array_filter($splits, fn($split) => $split['user_id']!== $paidBy);

    //     $sum = array_sum(array_column($filterSplits, 'amount_owed'));
    //     if ($sum !== $totalAmount) {
    //         throw new \Exception("{$sum} must equal total amount {$totalAmount}", 422);
    //     }

    //     return array_map(fn ($split) => [
    //         'user_id' => $split['user_id'],
    //         'amount_owed' => $split['amount_owed'],
    //     ], $filterSplits);
    // }

    // private function buildCustomSplits(int $totalAmount, array $splits, string $paidBy): array
    // {

    // $totalSum = array_sum(array_column($splits, 'amount_owed'));


    // if ($totalSum !== $totalAmount) {
    //     throw new \Exception("Total splits sum ({$totalSum}) must equal total amount ({$totalAmount})", 422);
    // }


    // $otherSplits = array_filter($splits, fn($split) => $split['user_id'] !== $paidBy);

    // return array_map(fn ($split) => [
    //     'user_id' => $split['user_id'],
    //     'amount_owed' => $split['amount_owed'],
    // ], array_values($otherSplits));
    // }

    private function buildCustomSplits(int $totalAmount, array $splits, string $paidBy): array
    {

        $totalSum = array_sum(array_column($splits, 'amount_owed'));

        if ($totalSum !== $totalAmount) {
            throw new \Exception("Total splits sum ({$totalSum}) must equal total amount ({$totalAmount})", 422);
        }

        $payerSplit = array_filter($splits, fn($split) => $split['user_id'] === $paidBy);

        $payerSplit = current($payerSplit);

        $includePayer = $payerSplit && $payerSplit['amount_owed'] > 0;

        $otherSplits = array_filter($splits, fn($split) => $split['user_id'] !== $paidBy);

        $formattedSplits = array_map(fn ($split) => [
            'user_id' => $split['user_id'],
            'amount_owed' => $split['amount_owed'],
        ], array_values($otherSplits));

        return [
            'splits' => $formattedSplits,
            'include_payer' => $includePayer,
        ];
    }


    private function validateSplitData(array $data): void
    {
        if (($data['split_type'] ?? null) === 'custom') {
            if (empty($data['splits'])) {
                throw new \Exception('Custom split requires split data', 422);
            }
        foreach ($data['splits'] as $split){
            if(!isset($split['user_id']) || !isset($split['amount_owed'])){
                throw new \Exception('Each split must have user_id and amount_owed',422);
            }
        }
        }
    }

    // for update
    private function recalculateSplits($expense, array $data): void
{
    $splitsData = $this->calculateSplits($expense, array_merge([
        'split_type' => $expense->split_type,
        'include_payer' => $expense->include_payer,
    ], $data, ['amount' => $expense->amount]));

    // Update the parent expense record with the newly calculated include_payer flag
    $expense->update([
        'include_payer' => $splitsData['include_payer']
    ]);

    // 1. Update existing splits or create new ones
    foreach ($splitsData['splits'] as $newSplit) {
        $existing = $this->expenseRepository->findSplit($expense->id, $newSplit['user_id']);

        if ($existing) {
            $isSettled = $existing->amount_paid >= $newSplit['amount_owed'];
            $this->expenseRepository->updateSplit($existing, [
                'amount_owed' => $newSplit['amount_owed'],
                'is_settled' => $isSettled,
            ]);
        } else {
            $this->expenseRepository->createSplits($expense->id, [$newSplit]);
        }
    }

    // 2. Remove old members who are no longer included in the new splits list
    $currentUserIds = array_column($splitsData['splits'], 'user_id');
    $allSplits = $this->expenseRepository->getSplitsByExpense($expense->id);

    foreach ($allSplits as $split) {
        if (! in_array($split->user_id, $currentUserIds)) {
            $split->delete();
        }
    }
}


    // ----Settlement Logic------
    public function claimPayment(string $splitId, int $amount, string $claimantId)
    {
        $split=$this->expenseRepository->findSplitById($splitId);
        if(!$split){
            throw new \Exception('Split record not found',404);
        }

        if($split->user_id !==$claimantId){
            throw new \Exception('you can pay only your own debt',403);
        }

        $existingPending=$this->settlementRequestRepository->findPendingByClaimant($splitId, $claimantId);
        if($existingPending)
            {
                throw new \Exception('you already have a pending payment claim for this debt',422);
            }

            $remainingOwed=$split->amount_owed - $split->amount_paid;
            if($amount > $remainingOwed){
                throw new \Exception(
                    "Claim amount exceeds remaining owed amount of '{$remainingOwed}'"
                ,422);
            }

            $settlementRequest = $this->settlementRequestRepository->create([
                'expense_split_id'=>$splitId,
                'claimed_by'      =>$claimantId,
                'amount'          =>$amount,
                'status'          =>'pending'
            ]);
            $this->notifySettlementRequest($split,$amount);
            return $settlementRequest;

    }

    public function comfirmPayment(string $requestId, String $confirmerId)
    {
        $settlementRequest=$this->settlementRequestRepository->findById($requestId);
        if(!$settlementRequest){
            throw new \Exception('Settlement request not found',404);
        }
        if($settlementRequest->status !== 'pending'){
            throw new \Exception('the request has already been processed',422);
        }

        $split=$settlementRequest->expenseSplit;
        $expense=$split->groupExpense;

        if($expense->paid_by !== $confirmerId){
            throw new \Exception('only the person who paid can confirm this payment',403);
        }

        return DB::transaction(function () use ($settlementRequest, $split, $confirmerId){
            $remainingOwed=$split->amount_owed - $split->amount_paid;
            if($settlementRequest->amount > $remainingOwed){
                throw new \Exception(
                    "Cannot Confirm: amount exceeds remaining owed amount of '{$remainingOwed}'"
                ,422);
            }
            $newAmountPaid=$split->amount_paid + $settlementRequest->amount;
            $isSettled=$newAmountPaid >= $split->amount_owed;
            $this->expenseRepository->updateSplit($split,[
                'amount_paid'=>$newAmountPaid,
                'is_settled'=>$isSettled,
                'settled_at'=>$isSettled ? now() :null,
            ]);

            $settlementRequest = $this->settlementRequestRepository->update($settlementRequest,[
                'status'=> 'confirmed',
                'confirmed_by'=>$confirmerId,
                'confirmed_at'=>now(),
            ]);

            $this->notifySettlementConfirmation($split, $settlementRequest->amount);
            return $settlementRequest;
        });

    }

    public function rejectPayment(string $requestId, string $confirmerId)
    {
        $settlementRequest=$this->settlementRequestRepository->findById($requestId);
        if(!$settlementRequest){
            throw new \Exception('Settlement request not found',404);
        }
        if($settlementRequest->status !== 'pending'){
            throw new \Exception('the request has already been processed',422);
        }

        $split=$settlementRequest->expenseSplit;
        $expense=$split->groupExpense;

        if($expense->paid_by !== $confirmerId){
            throw new \Exception('only the person who paid can reject this payment',403);
        }

        $settlementRequest = $this->settlementRequestRepository->update($settlementRequest,[
            'status'=>'rejected',
            'confirmed_by'=>$confirmerId,
            'confirmed_at'=>now(),
        ]);

        $this->notifySettlementRejection($split, $settlementRequest->amount);
        return $settlementRequest;
    }


    //getSummary for GpExpnese

    public function getGroupBalance(string $groupId, string $userId){
        if(!$this->groupRepository->isMember($groupId,$userId)){
            throw new \Exception('you are not a member of this group',403);
        }
        $group=$this->groupRepository->findById($groupId);

        $receivables=$this->expenseRepository->getReceivableSummary($groupId);
        $payables=$this->expenseRepository->getPayableSummary($groupId);

        return $group->members->map(function ($member) use ($receivables,$payables) {
            return[
                'user_id'=>$member->id,
                'name'=>$member->name,
                'avatar'=>$member->avatar,
                'total_receivable'=>$receivables->get($member->id)->total_receivable ?? 0,
                'total_payable'=>$payables->get($member->id)->total_payable ?? 0,

            ];
        })->values();
    }

    public function getBalanceDetail(string $groupId,string $tergetUserId,string $requesterId)
    {
        if(!$this->groupRepository->isMember($groupId,$requesterId)){
            throw new \Exception('you are not a member of this group',403);
        }
        $debtorsDetails=$this->expenseRepository->getActiveDebtorDetail($tergetUserId, $groupId);
        $payerDetails=$this->expenseRepository->getActivePayerDetails($tergetUserId, $groupId);

        return [
            'owed_to_others' => $debtorsDetails->map(function ($split){
            return[
                'split_id'=>$split->id,
                'expense'=> $split->groupExpense?->description ?? 'N/A',
                'paid_to'=>$split->groupExpense->payer->name,
                'amount_owed'=>$split->amount_owed,
                'amount_paid'=>$split->amount_paid,
                'remaining'=>$split->amount_owed - $split->amount_paid
            ];

        })->values(),

        'owed_by_others' => $payerDetails->map(function ($split){
            return[
                'split_id'=>$split->id,
                'expense'=> $split->groupExpense?->description ?? 'N/A',
                'owed_by'=>$split->user->name,
                'amount_owed'=>$split->amount_owed,
                'amount_paid'=>$split->amount_paid,
                'remaining'=>$split->amount_owed - $split->amount_paid,
            ];
        })->values(),
        ];
    }

    public function getBalanceHistory(string $groupId, string $tergetUserId, string $requesterId)
    {
        if(!$this->groupRepository->isMember($groupId,$requesterId)){
            throw new \Exception('you are not a member of this group',403);
        }

        $debtorHistory=$this->settlementRequestRepository->getConfirmedAsDebtor($tergetUserId,$groupId);
        $payerHistory=$this->settlementRequestRepository->getConfirmedAsPayer($tergetUserId,$groupId);

        return [
            'paid_to_others'=>$debtorHistory->map(function ($request){
                return[
                'expense'=> $request->expenseSplit->groupExpense?->description ?? 'N/A',
                'paid_to'=>$request->expenseSplit->groupExpense->payer->name,
                'amount'=> $request->amount,
                'confirmed_at'=>$request->confirmed_at
                ];
            })->values(),

            'received_by_others'=>$payerHistory->map(function($request){
                return[
                    'expense'=>$request->expenseSplit->groupExpense?->description ?? 'N/A',
                    'received_from'=>$request->expenseSplit->user->name,
                    'amount'=>$request->amount,
                    'confirmed_at'=>$request->confirmed_at
                ];
            })->values(),
        ];


    }


    //for gp expense notification
    private function notifyExpenseMembers($expense): void
    {
    $payer       = $expense->payer;
    $description = $expense->description ?? 'Expense';
    $amount      = number_format($expense->amount);

        foreach ($expense->splits as $split) {
            if ($split->user_id === $expense->paid_by) {
                continue;
            }

            $this->notificationService->sendToUser(
                $split->user,
                '💸 New Group Expense',
                "{$payer->name} paid {$amount} Ks for \"{$description}\". You owe {$split->amount_owed} Ks.",
                [
                    'type'       => 'group_expense_created',
                    'expense_id' => $expense->id,
                    'group_id'   => $expense->group_id,
                    'amount_owed' => (string) $split->amount_owed,
                ]
            );
        }
    }

    //for settlement request notification
    public function notifySettlementRequest($split,int $amount): void
    {
       $payerUser = $split->groupExpense?->payer;

        if (!$payerUser) {
            return;
        }

        $claimantName = $split->user?->name ?? 'A user';

        $title = "New Payment Claim";
        $body  = "{$claimantName} claimed a payment of {$amount} for your shared expense.";

        $payload = [
            'type'             => 'payment_claim',
            'expense_split_id' => (string) $split->id,
            'amount'           => (string) $amount,
        ];

        $this->notificationService->sendToUser($payerUser, $title, $body, $payload);
    }

    private function notifySettlementConfirmation($split, int $amount): void
    {
        $claimantUser = $split->user;

        if (!$claimantUser) {
            return;
        }

        $payerName = $split->groupExpense?->payer->name ?? 'A user';

        $title = "Payment Confirmed";
        $body  = "{$payerName} confirmed your payment of {$amount} for the shared expense.";

        $payload = [
            'type'             => 'payment_confirmed',
            'expense_split_id' => (string) $split->id,
            'amount'           => (string) $amount,
        ];

        $this->notificationService->sendToUser($claimantUser, $title, $body, $payload);
    }

    private function notifySettlementRejection($split, int $amount): void
    {
        $claimantUser = $split->user;

        if (!$claimantUser) {
            return;
        }

        $payerName = $split->groupExpense?->payer->name ?? 'A user';

        $title = "Payment Rejected";
        $body  = "{$payerName} rejected your payment of {$amount} for the shared expense.";

        $payload = [
            'type'             => 'payment_rejected',
            'expense_split_id' => (string) $split->id,
            'amount'           => (string) $amount,
        ];

        $this->notificationService->sendToUser($claimantUser, $title, $body, $payload);
    }


}
