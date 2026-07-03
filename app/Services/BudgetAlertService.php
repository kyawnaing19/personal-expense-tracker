<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Transaction;
use App\Models\User;

class BudgetAlertService
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function checkAfterTransaction(string $userId, string $categoryId, int $month, int $year): void
    {
        \log::info('budgetAlert check started',[
            'user_id'=>$userId,
            'category_id'=>$categoryId,
            'month'=>$month,
            'year'=>$year

        ]);
        // to check this category has budget for this month
        $budget = Budget::query()
                        ->where('user_id', $userId)
                        ->where('category_id', $categoryId)
                        ->where('month', $month)
                        ->where('year', $year)
                        ->first();
        \Log::info('budget found',['budget'=>$budget]);

        if (!$budget) {
            \Log::info('no budget found');
            return;
        }


        // to calculate toltal amount of this category's transactions (status=='confirmed') for this month
        $spent = Transaction::query()
                            ->where('user_id', $userId)
                            ->where('category_id', $categoryId)
                            ->where('type', 'expense')
                            ->where('status', 'confirmed')
                            ->whereMonth('transaction_date', $month)
                            ->whereYear('transaction_date', $year)
                            ->sum('amount');
        \Log::info('Spent amount',['spent'=>$spent,'amount'=>$spent->amount]);

        $percentage = ($spent / $budget->amount) * 100;
        \Log::info('Budget Percentage',['percentage'=>$percentage]);
        $alertThreshold = $budget->alert_percentage ?? 80;

        $user = User::query()->find($userId);

        // 3. Notification logic to mobile
        if ($percentage >= 100) {
            $this->notificationService->sendToUser(
                $user,
                '⚠️ Budget Exceeded!',
                "You have exceeded your budget for this category. Spent: {$spent} / {$budget->amount} Ks",
                [
                    'type'        => 'budget_exceeded',
                    'category_id' => $categoryId,
                    'spent'       => (string) $spent,
                    'budget'      => (string) $budget->amount,
                ]
            );
        } elseif ($percentage >= $alertThreshold) {
            $remaining = $budget->amount - $spent;
            $this->notificationService->sendToUser(
                $user,
                '💰 Budget Warning',
                "You've used " . round($percentage, 1) . "% of your budget. Remaining: {$remaining} Ks",
                [
                    'type'        => 'budget_warning',
                    'category_id' => $categoryId,
                    'percentage'  => (string) round($percentage, 1),
                    'remaining'   => (string) $remaining,
                ]
            );
        }
    }
}
