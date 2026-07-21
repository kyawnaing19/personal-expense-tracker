<?php
namespace App\Services;

use App\Http\Resources\RecurringTransactionResource;
use App\Models\Category;
use App\Models\Transaction;
use App\Repositories\CategoryRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;

class TransactionService
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private CategoryRepository $categoryRepository,
        private BudgetAlertService $budgetAlertService

    )
    {}

    private function getDateRange(array $filters = []): array
    {
        $filter = $filters['filter'] ?? 'this_month';

        return match($filter) {
            'last_three_month' => [
                'from' => Carbon::now()->subMonths(3)->startOfMonth()->toDateString(),
                'to'   => Carbon::now()->toDateString()
            ],
            'this_month' => [
                'from' => Carbon::now()->startOfMonth()->toDateString(),
                'to'   => Carbon::now()->endOfMonth()->toDateString()
            ],
            'last_month' => [
                'from' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
                'to'   => Carbon::now()->subMonth()->endOfMonth()->toDateString()
            ],
            'last_year' => [
                'from' => Carbon::now()->subYear()->startOfYear()->toDateString(),
                'to'   => Carbon::now()->subYear()->endOfYear()->toDateString()
            ],
            'custom' => [
                'from' => $filters['from'] ?? Carbon::now()->startOfMonth()->toDateString(),
                'to'   => $filters['to'] ?? Carbon::now()->endOfMonth()->toDateString()
            ],
            default => [
                'from' => Carbon::now()->startOfMonth()->toDateString(),
                'to'   => Carbon::now()->endOfMonth()->toDateString()
            ]
        };
    }


   public function getAll(string $userId, array $filters = [])
    {

        $dateRange = $this->getDateRange($filters);


        $refinedFilters = [
            'from'        => $dateRange['from'],
            'to'          => $dateRange['to'],
            'category_id' => $filters['category_id'] ?? null,
            'type'        => $filters['type'] ?? null,
        ];

        return $this->transactionRepository->getAllByUser($userId, $refinedFilters);
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
        //$data['transaction_date']=now()->format('Y-m-d');
        $transaction=$this->transactionRepository->create($data);

        //budget Alert Check
        if ($transaction->type === 'expense' && $transaction->status === 'confirmed') {

            $this->budgetAlertService->checkAfterTransaction(
                $userId, $transaction->category_id,
                 (int) date('n', strtotime($transaction->transaction_date)),
                 (int) date('Y', strtotime($transaction->transaction_date))
                );
        }
        return $transaction;


    }

    public function update(string $id, array $data,string $userId)
    {
        $transaction=$this->transactionRepository->findById($id,$userId);
        if (!$transaction) {
            throw new \Exception('transaction not found!',404);
        }
        if($transaction->status==='rejected'){
            throw new \Exception('cannot update rejected transaction!',422);
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

    public function getRecurringTransactions(string $userId)
    {
        $transaction= $this->transactionRepository->getRecurringTransactionsByUser($userId);
        return RecurringTransactionResource::collection($transaction);
    }

    public function accept(string $id, string $userId)
    {
        $transaction=$this->transactionRepository->findById($id,$userId);
        if (!$transaction) {
            throw new \Exception('transaction not found!',404);
        }
        if($transaction->status!=='pending'){
            throw new \Exception('only pending transactions can be accepted!',422);
        }

        $updated=$this->transactionRepository->update($transaction,[
            'status'=>'confirmed',
            'created_at' => now(),
            ]);

        if ($updated->type === 'expense') {

             $date = Carbon::parse($updated->transaction_date);
             $this->budgetAlertService->checkAfterTransaction(
                 $userId, $updated->category_id,
                 (int) $date->format('n'),
                 (int) $date->format('Y')
                );
        }

        return $updated;
    }

    public function reject(string $id, string $userId)
    {
        $transaction=$this->transactionRepository->findById($id,$userId);
        if (!$transaction) {
            throw new \Exception('transaction not found!',404);
        }
        if($transaction->status!=='pending'){
            throw new \Exception('only pending transactions can be rejected!',422);
        }
        return $this->transactionRepository->update($transaction,['status'=>'rejected']);
    }
}
