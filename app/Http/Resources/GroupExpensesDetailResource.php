<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupExpensesDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [

            'id' => $this->id,
            'group_id' => $this->group_id,
            'paid_by' => $this->paid_by,
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'description' => $this->description,
            'expense_date' => $this->expense_date,
            'split_type' => $this->split_type,
            'include_payer' => (bool) $this->include_payer,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,


            'payer_name' => $this->payer->name ?? null,

            // Mapping the splits array to include only specific fields
            'splits' => collect($this->splits)->map(function ($split) {
                return [
                    'amount_owed' => $split->amount_owed,
                    'is_settled' => (bool) $split->is_settled,
                    'user_name' => $split->user->name ?? null,
                ];
            })->all(),
        ];
    }
}
