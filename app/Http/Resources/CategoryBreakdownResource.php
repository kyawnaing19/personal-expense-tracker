<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryBreakdownResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $income = $this->where('type', 'income');
        $expense = $this->where('type', 'expense');

        $totalIncome = $income->sum('total');
        $totalExpense = $expense->sum('total');

        return [
            'income' => $income->map(fn($item) => [
                'category'   => $item->name,
                'color'      => $item->color,
                'amount'     => $item->total,
                'percentage' => $totalIncome > 0 ? round(($item->total / $totalIncome) * 100, 1) : 0,
            ])->values()->all(),

            'expense' => $expense->map(fn($item) => [
                'category'   => $item->name,
                'color'      => $item->color,
                'amount'     => $item->total,
                'percentage' => $totalExpense > 0 ? round(($item->total / $totalExpense) * 100, 1) : 0,
            ])->values()->all(),
        ];
    }
}
