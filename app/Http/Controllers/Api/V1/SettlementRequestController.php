<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\SettlementRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettlementRequestController extends Controller
{
    public function __construct(
        private SettlementRequestService $settlementRequestService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'role'   => ['required', 'in:payer,claimant'],
            'status' => ['sometimes', 'in:pending,confirmed,rejected'],
        ]);

        try {
            $list = $this->settlementRequestService->getList(
                $request->user()->id,
                $request->query('role'),
                $request->query('status'),
            );

            return response()->json([
                'success' => true,
                'data'    => $list,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }
}
