<?php
namespace App\Services;

use App\Repositories\GroupExpenseRepository;
use App\Repositories\GroupRepository;
use Illuminate\Support\Facades\DB;

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
                throw new \Exception('the expense not found',403);
            }
        if(!$this->groupRepository->isMember($expense->group_id,$userId))
            {
                throw new \Exception('you are not a member');
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
            'category_id'   => $data['category_id'],
            'amount'        => $data['amount'],
            'description'   => $data['description'],
            'split_type'    => $data['split_type'],
            'include_payer' => $data['include_payer']
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

    }
}
