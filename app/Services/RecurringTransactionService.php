<?php
namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\RecurringTransactionRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;

class RecurringTransactionService
{

   public function __construct(
     private RecurringTransactionRepository $recurringTransactionRepository,
     private CategoryRepository $categoryRepository,
     private TransactionRepository $transactionRepository
    )
    {
    }

    public function getAllByUser(string $userId,array $filter=[])
    {
        return $this->recurringTransactionRepository->getAllByUser($userId,$filter);
    }

    public function findById(string $id,string $userId)
    {
        return $this->recurringTransactionRepository->findById($id,$userId);
    }

    public function create(array $data, string $userId)
    {
        $category=$this->categoryRepository->findById($data['category_id'], $userId);
        if (!$category) {
            throw new \Exception('category not found!',404);
        }
        $data['user_id']=$userId;
        $data['type']=$category->type;
        $data['next_run_date']=$data['start_date'];

        return $this->recurringTransactionRepository->create($data);
    }

 public function update(string $id, array $data, string $userId)
{
    $recurringTransaction = $this->findById($id, $userId);
    if (!$recurringTransaction) {
        throw new \Exception('Recurring transaction not found!', 404);
    }

    // 1. If category is being updated, validate & update the type
    if (isset($data['category_id'])) {
        $category = $this->categoryRepository->findById($data['category_id'], $userId);
        if (!$category) {
            throw new \Exception('Category not found!', 404);
        }
        $data['type'] = $category->type;
    }

    // 2. If start date is updated, reset next_run_date accordingly
    if (isset($data['start_date'])) {
        $data['next_run_date'] = $data['start_date'];
    }

    return $this->recurringTransactionRepository->update($recurringTransaction, $data);
}

    public function delete(string $id, string $userId)
    {
        $recurringTransaction = $this->findById($id, $userId);
        if (!$recurringTransaction) {
            throw new \Exception('Recurring transaction not found!',404);
        }
        return $this->recurringTransactionRepository->delete($recurringTransaction);
    }

    public function processRecurring()
    {
        $dueRecurring=$this->recurringTransactionRepository->getDueRecurringTransactions();
        foreach ($dueRecurring as $recurring)
        {
            $transactionData=[
                'amount'=>$recurring->amount,
                'type'=>$recurring->type,
                'category_id'=>$recurring->category_id,
                'note'=>$recurring->note,
                'user_id'=>$recurring->user_id,
                'transaction_date'=>now()->format('Y-m-d'),
                'recurring_id'=>$recurring->id,
                'status'=>'pending'
            ];
            $this->transactionRepository->create($transactionData);

            // Update next run date
            $nextRunDate = match ($recurring->frequency) {
                'daily' => now()->addDay(),
                'weekly' => now()->addWeek(),
                'monthly' => now()->addMonth(),
                default => null,
            };
            if ($nextRunDate) {
                $this->recurringTransactionRepository->update($recurring, ['next_run_date' => $nextRunDate->format('Y-m-d')]);
            }
            if ($recurring->end_date && now()->greaterThan(Carbon::parse($recurring->end_date))) {
                $this->recurringTransactionRepository->update($recurring, ['is_active' => false]);
            }
        }

    }
}
