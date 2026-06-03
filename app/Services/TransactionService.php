<?php
namespace App\Services;

use App\Models\Category;
use App\Models\Transaction;
use App\Repositories\CategoryRepository;
use App\Repositories\TransactionRepository;

class TransactionService
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private CategoryRepository $categoryRepository
    )
    {}
    public function getAll(string $userId,array $filters=[])
    {
        return $this->transactionRepository->getAllByUser($userId,$filters);
    }
    public function findById(string $id,string $userId)
    {
        return $this->transactionRepository->findById($id,$userId);

    }

    public function create(array $data, string $userId):Transaction
    {   $category=$this->categoryRepository->findById($data['category_id'], $userId);
        if (!$category) {
            throw new \Exception('category not found!',404);
        }

        $data['type']=$category->type;
        $data['user_id']=$userId;
        $data['transaction_date']=now()->format('Y-m-d');
        return $this->transactionRepository->create($data);
    }

    public function update(string $id, array $data,string $userId)
    {
        $transaction=$this->transactionRepository->findById($id,$userId);
        if (!$transaction) {
            throw new \Exception('transaction not found!',404);
        }
        return $this->transactionRepository->update($transaction,$data);

    }

    public function delete(string $id, string $userId)
    {
        $transaction=$this->transactionRepository->findById($id,$userId);
        if (!$transaction) {
            throw new \Exception('transaction not found!',404);
        }
        return $this->transactionRepository->delete($transaction);
    }
}
