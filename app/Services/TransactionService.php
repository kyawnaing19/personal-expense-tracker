<?php
namespace App\Services;

use App\Repositories\TransactionRepository;

class TransactionService
{
    public function __construct(
        private TransactionRepository $transactionRepository
    )
    {}
    public function getAll(string $userId)
    {
        return $this->transactionRepository->getAllByUser($userId);
    }

    public function create(array $data, string $userId, string $categoryId)
    {

    }
}
