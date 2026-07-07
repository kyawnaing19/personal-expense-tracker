<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'group_id'      => $this->group_id,
            'group_name'    => $this->group?->name,
            'paid_by'       => $this->paid_by,
            'category_id'   => $this->category_id,
            'amount'        => (float) $this->amount,
            'description'   => $this->description,
            'expense_date'  => $this->expense_date,
            'split_type'    => $this->split_type,
            'include_payer' => (bool) $this->include_payer,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,


            'payer' => $this->payer ? [
                'id'        => $this->payer->id,
                'name'      => $this->payer->name,
                'google_id' => $this->payer->google_id,
                'avatar'    => $this->payer->avatar,
            ] : null,

            'category' => $this->category,


            'splits' => $this->splits ? $this->splits->map(fn($split) => [
                // 'id'          => $split->id,
                // 'amount_owed' => (float) $split->amount_owed,
                // 'amount_paid' => (float) $split->amount_paid,
                // 'is_settled'  => (bool) $split->is_settled,
                // 'settled_at'  => $split->settled_at,
                'user' => $split->user ? [
                    'id'        => $split->user->id,
                    'name'      => $split->user->name,
                    'google_id' => $split->user->google_id,
                    'avatar'    => $split->user->avatar,
                ] : null,
            ])->values()->all() : [],
        ];
    }
}
