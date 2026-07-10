<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecurringTransactionResource extends JsonResource
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
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'type' => $this->type,
            'amount' => $this->amount,
            'note' => $this->note,
            'transaction_date' => $this->transaction_date,
            'receipt_path' => $this->receipt_path,
            'recurring_id' => $this->recurring_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,


            'category' => $this->when($this->relationLoaded('category') && $this->category, function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,

                ];
            }),
        ];
    }
}
