<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseSplitResource extends JsonResource
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
            'group_expense_id' => $this->group_expense_id,
            'user_id' => $this->user_id,
            'amount_owed' => $this->amount_owed,
            'amount_paid' => $this->amount_paid,
            'remaining_amount' => $this->amount_owed - $this->amount_paid,
            'is_settled' => (bool) $this->is_settled,
            'settled_at' => $this->settled_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Flattening and shaping the user data
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'avatar' => $this->user->avatar ?? null,
                'email' => $this->user->email ?? null,
            ],

            // Flattening and shaping the group_expense data
            'group_expense' => [
                'id' => $this->groupExpense->id ?? null,
                'group_id' => $this->groupExpense->group_id ?? null,
                'description' => $this->groupExpense->description ?? null,
                'group_name' => $this->groupExpense->group->name ?? null, // Fetches the group name
                'payer' => [
                    'id' => $this->groupExpense->payer->id ?? null,
                    'name' => $this->groupExpense->payer->name ?? null,
                    'avatar' => $this->groupExpense->payer->avatar ?? null,
                    'email' => $this->groupExpense->payer->email ?? null,
                ],
            ],
        ];
    }
}
