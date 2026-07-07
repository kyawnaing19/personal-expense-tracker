<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnualSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'month'   => $this->year . '-' . str_pad($this->month, 2, '0', STR_PAD_LEFT),
            'income'  => (float) $this->total_income,
            'expense' => (float) $this->total_expense,
        ];
    }
}
