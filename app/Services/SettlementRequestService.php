<?php

namespace App\Services;

use App\Repositories\SettlementRequestRepository;

class SettlementRequestService
{
    public function __construct(
        private SettlementRequestRepository $settlementRepository
    ) {}

    public function getList(string $userId, string $role, ?string $status): array
    {
        // status validation
        $validStatuses = ['pending', 'confirmed', 'rejected', null];
        if (!in_array($status, $validStatuses)) {
            throw new \Exception('Invalid status filter', 422);
        }

        if ($role === 'payer') {
            $requests = $this->settlementRepository->getIncomingRequests($userId, $status);

            return $requests->map(function ($request) {
                return [
                    'id'           => $request->id,
                    'group'        => $request->expenseSplit->groupExpense->group->name,
                    'expense'      => $request->expenseSplit->groupExpense->description,
                    'claimed_by'   => $request->claimant->name,
                    'amount'       => $request->amount,
                    'status'       => $request->status,
                    'created_at'   => $request->created_at,
                ];
            })->values()->toArray();
        }

        if ($role === 'claimant') {
            $requests = $this->settlementRepository->getOutgoingRequests($userId, $status);

            return $requests->map(function ($request) {
                return [
                    'id'         => $request->id,
                    'group'      => $request->expenseSplit->groupExpense->group->name,
                    'expense'    => $request->expenseSplit->groupExpense->description,
                    'paid_to'    => $request->expenseSplit->groupExpense->payer->name,
                    'amount'     => $request->amount,
                    'status'     => $request->status,
                    'created_at' => $request->created_at,
                ];
            })->values()->toArray();
        }

        throw new \Exception('Invalid role. Use payer or claimant', 422);
    }
}
