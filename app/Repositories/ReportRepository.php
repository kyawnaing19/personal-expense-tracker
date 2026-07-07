<?php
namespace App\Repositories;

use App\Models\Budget;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class ReportRepository
{
    public function getSummary(string $userId, array $datarange):array
    {
        $result=Transaction::where('user_id',$userId)
                            ->whereBetween('transaction_date',[
                                $datarange['from'],$datarange['to']
                            ])
                            ->where('status','confirmed')
                            ->selectRaw('
                            SUM(CASE WHEN type="income" THEN amount ELSE 0 END) as total_income,
                            SUM(CASE WHEN type="expense" THEN amount ELSE 0 END) as total_expense
                            ')
                            ->first();
        return [
            'total_income'=>$result->total_income ?? 0,
            'total_expense'=>$result->total_expense ??0,
            'balance'=>($result->total_income ?? 0)- ($result->total_expense ?? 0),
            'datarange'=>$datarange,
        ];
    }

    public function getCategoryBreakdown(string $userId,array $datarange):EloquentCollection
    {
        return Transaction::where('transactions.user_id',$userId)
                            ->whereBetween('transaction_date',[
                                $datarange['from'],
                                $datarange['to']
                            ])
                            ->where('transactions.status','confirmed')
                            ->join('categories',
                            'transactions.category_id','=','categories.id'
                            )
                            ->selectRaw('categories.name,
                                categories.color,
                                transactions.type,
                                SUM(transactions.amount) as total'
                            )
                            ->groupBy(
                                'transactions.category_id',
                                'transactions.type',
                                'categories.name',
                                'categories.color'
                                )
                            ->get();

    }

    //to get total income and expense per month
    public function getAnnualSummary(string $userId, array $datarange):EloquentCollection
    {
        return Transaction::where('user_id', $userId)
            ->whereBetween('transaction_date', [$datarange['from'], $datarange['to']])
            ->where('status', 'confirmed')
            ->selectRaw('
                YEAR(transaction_date) as year,
                MONTH(transaction_date) as month,
                SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense
                ')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }

    public function getBudgetOverview(string $userId, int $month, int $year):array
    {
        $budget=Budget::where('user_id',$userId)
                            ->where('month',$month)
                            ->where('year', $year)
                            ->with('category')
                            ->get();

        $categoryIds=$budget->pluck('category_id');
        $spentData=Transaction::where('user_id',$userId)
                                ->whereIn('category_id',$categoryIds)
                                ->whereMonth('transaction_date',$month)
                                ->whereYear('transaction_date',$year)
                                ->where('type','expense')
                                ->where('status','confirmed')
                                ->groupBy('category_id')
                                ->selectRaw('category_id,SUM(amount) as total')
                                ->get()
                                ->keyBy('category_id');

        return $budget->map(function ($budget) use ($spentData){


            $spent=$spentData->get($budget->category_id)->total ?? 0;
            $remaining=$budget->amount-$spent;

            return [
                'id'=>$budget->id,
                'category'=>$budget->category?->name,
                'budget'=>$budget->amount,
                'spent'=>$spent,
                'remaining'=>$remaining,
                'expense_percentage'=>$budget->amount > 0
                    ?round(($spent/$budget->amount)*100,1):0,
                'alert_percentage'=>$budget->alert_percentage,

            ];
        })->toArray();
    }
}
