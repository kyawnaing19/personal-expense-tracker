<?php
namespace App\Services;

use App\Repositories\GroupExpenseRepository;
use App\Repositories\GroupRepository;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class GroupExpenseService
{
    public function __construct(
        private GroupExpenseRepository $expenseRepository,
        private GroupRepository $groupRepository
    )
    {

    }

    public function getAllByGroup(string $groupId, string $userId)
    {
        if(!$this->groupRepository->isMember($groupId,$userId))
            {
                throw new \Exception('you are not a member of this group',403);
            }
        return $this->expenseRepository->getAllByGroup($groupId);
    }

    public function findById(string $id,string $userId)
    {
        $expense=$this->expenseRepository->findById($id);
        if(!$expense)
            {
                throw new \Exception('the expense not found',404);
            }
        if(!$this->groupRepository->isMember($expense->group_id,$userId))
            {
                throw new \Exception('you are not a member of this group',403);
            }
        return $expense;
    }

    public function create(array $data, string $userId)
    {
        if(!$this->groupRepository->isMember($data['group_id'],$userId))
            {
                 throw new \Exception('you are not a member',403);
            }
        $data['paid_by']=$userId;
        $this->validateSplitdata($data);
       return DB::transaction(function () use ($data){
        $expense=$this->expenseRepository->create([
            'group_id'      => $data['group_id'],
            'paid_by'       => $data['paid_by'],
            'category_id'   => $data['category_id'] ??  null,
            'amount'        => $data['amount'],
            'description'   => $data['description'] ?? null,
            'expense_date'  => $data['expense_date'],
            'split_type'    => $data['split_type'],
            'include_payer' => $data['include_payer'] ?? true,
        ]);

       $splits=$this->calculateSplits($expense,$data);
       $this->expenseRepository->createSplits($expense->id,$splits);
       return $this->expenseRepository->findById($expense->id);
       });
    }

    public function update(string $id, array $data, string $userId)
    {
        $expense=$this->expenseRepository->findById($id);
        if(!$expense)
            {
                throw new \Exception('expense not found',404);
            }

        $isCreator=$expense->paid_by === $userId;
        $isAdmin=$this->groupRepository->isAdmin($expense->group_id,$userId);

        if(!$isCreator && !$isAdmin)
            {
                throw new \Exception('only admin or creator can edit the group expense',403);
            }
        $this->validateSplitData(array_merge([
            'group_id' => $expense->group_id,
        ],$data));
        return DB::transaction(function () use ($expense,$data) {
            $expense=$this->expenseRepository->update($expense,[
                'category_id'   => $data['category_id'] ?? $expense->category_id,
                'amount'        => $data['amount']     ?? $expense->amount,
                'description'   => $data['description'] ?? $expense->description,
                'expense_date'  => $data['expense_date'] ?? $expense->expense_date,
                'split_type'    => $data['split_type'] ?? $expense->split_type,
                'include_payer' => $data['include_payer'] ?? $expense->include_payer

            ]);
            $this->recalculateSplits($expense,$data);
            return $this->expenseRepository->findById($expense->id);

        });
    }

    public function delete(string $id,string $userId)
    {
        $expense=$this->expenseRepository->findById($id);
        if(!$expense)
            {
                throw new \Exception('the expense not found',404);
            }
        $isCreator=$expense->paid_by === $userId;
        $isAdmin=$this->groupRepository->isAdmin($expense->group_id,$userId);

        if(!$isCreator && !$isAdmin){
            throw new \Exception('only creator or admin can delete',403);
        }

        return $this->expenseRepository->delete($expense);
    }


    //Split logic
    private function calculateSplits($expense, array $data): array
    {
        if($data['split_type'] === 'custom'){
            return $this->buildCustomSplits($data['amount'],$data['splits']);
        }
        return $this->buildEquallySplits(
            $expense->group_id,
            $data['amount'],
            $expense->paid_by,
            $data['include_payer'] ?? true
        );

    }
    private function buildEquallySplits(string $groupId, int $amount, string $paidBy, bool $includePayer )
    {
        $group = $this->groupRepository->findById($groupId);
        $members= $group->members;
        $splitMembers=$includePayer
                    ? $members
                    :$members->reject(fn($m) => $m->id === $paidBy);
        $count=$splitMembers->count();
        $perPerson=(int) round($amount/$count);
        return $splitMembers->map(fn($member)=>[
            'user_id'   => $member->id,
            'amount_owed'=> $perPerson
        ])->values()->toArray();
    }


    private function buildCustomSplits(int $totalAmount, array $splits):array
    {   $sum = array_sum(array_column($splits, 'amount_owed'));
    if($sum !== $totalAmount){
        throw new \Exception("{$sum} must equal total amount {$totalAmount}",422);
    }
        return array_map(fn($split)=>[
            'user_id'   => $split['user_id'],
            'amount_owed'  =>$split['amount_owed']
        ],$splits);
    }

    private function validateSplitData(array $data):void
    {
        if(($data['split_type']?? null) === 'custom' ){
            if(empty($data['splits'])){
            throw new \Exception('Custom split requires split data',422);

            }
            $sum=array_sum(array_column($data['splits'],'amount'));
            if($sum !== (int) $data['amount']){
            throw new \Exception(
                "Split amounts ({$sum}) must equal total amount ({$data['amount']})",
                422
            );
            }
        }
    }

    //for update
    private function recalculateSplits($expense,array $data):void
    {
        $newSplits=$this->calculateSplits($expense,array_merge([
            'split_type'    => $expense->split_type,
            'include_payer' => $expense->include_payer,
        ], $data,['amount' => $expense->amount]));

        foreach ($newSplits as $newSplit) {
            $existing = $this->expenseRepository->findSplit($expense->id,$newSplit['user_id']);
            if($existing){
                $isSettled=$existing->amount_paid >= $newSplit['amount_owed'];
                $this->expenseRepository->updateSplit($existing,[
                    'amount_owed' => $newSplit['amount_owed'],
                    'is_settled'  => $isSettled
                ]);
            }else{
                //for new member (or changing include_payer)
                $this->expenseRepository->createSplits($expense->id,[$newSplit]);
            }

        }

        //in inital state, the user's split has in list. Now, not include in list(to delete split that not include in newSPlits)

        $currentUserIds=array_column($newSplits, 'user_id');
        $allSplits= $this->expenseRepository->getSplitsByExpense($expense->id);
        foreach($allSplits as $split){
            if(!in_array($split->user_id, $currentUserIds)){
                $split->delete();
            }
        }
    }

    //----Settlement Logic------
    public function settle(string $splitId, int $amount, string $requesterId)
    {
        $split=$this->expenseRepository->findSplitById($splitId);
        if(!$split){
            throw new \Exception('Split record not found',404);
        }
        if($split->user_id !== $requesterId){
            throw new \Exception('you can only settle your own debt',403);
        }

        $remainingOwed=$split->amount_owed - $split->amount_paid;
        if($amount > $remainingOwed){
            throw new \Exception("payment amount exceeds remaining owed amount {$remainingOwed}",422);
        }

        $newAmountPaid =$split->amount_paid + $amount;
        $isSettled = $newAmountPaid >= $split->amount_owed;

        return $this->expenseRepository->updateSplit($split,[
            'amount_paid'   => $newAmountPaid,
            'is_settled'    => $isSettled,
            'settled_at'    => $isSettled ? now():null,
        ]);
    }
}
