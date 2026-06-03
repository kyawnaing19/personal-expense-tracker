<?php
namespace App\Services;

use App\Repositories\BudgetRepository;

class BudgetService
{
    public function __construct(
        private BudgetRepository $budgetRepository
    )
    {
    }
    public function getAllByUser(string $userId,array $filter=[])
    {
        return $this->budgetRepository->getAllByUser($userId,$filter);
    }


    public function upsert(array $data, string $userId)
    {
        $data['user_id']=$userId;
        return $this->budgetRepository->upsert($data);
    }

    public function update(string $id,array $data, string $userId)
    {
        $budget=$this->budgetRepository->findById($id,$userId);
        if(!$budget)
            {
                throw new \Exception('budget not found',404);
            }
        return $this->budgetRepository->update($budget,$data);
    }

    public function delete(string $id,string $userId)
    {
        $budget=$this->budgetRepository->findById($id,$userId);
        if(!$budget)
            {
                throw new \Exception('budget not found',404);
            }
        return $this->budgetRepository->delete($budget);

    }

}
