<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupExpense\SettleSplitRequest;
use App\Http\Requests\GroupExpense\StoreGroupExpenseRequest;
use App\Http\Requests\GroupExpense\UpdateGroupExpenseRequest;
use App\Services\GroupExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupExpenseController extends Controller
{
    public function __construct(
        private GroupExpenseService $groupExpenseService
    ) {}

    public function index(Request $request, string $groupId): JsonResponse
    {
        try {
            $expenses = $this->groupExpenseService->getAllByGroup(
                $groupId,
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'data' => $expenses,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function show(Request $request, string $id): JsonResponse
    {
        try {
            $expense = $this->groupExpenseService->findById($id, $request->user()->id);

            return response()->json([
                'success' => true,
                'data' => $expense,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function store(StoreGroupExpenseRequest $request): JsonResponse
    {
        try {
            $expense = $this->groupExpenseService->create(
                $request->validated(),
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Expense logged successfully',
                'data' => $expense,
            ], 201);
        } catch (\Exception $e) {


            $statusCode = (int) $e->getCode();

                if ($statusCode < 200 || $statusCode >= 600) {
                $statusCode = 500;
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }

    public function update(UpdateGroupExpenseRequest $request, string $id): JsonResponse
    {
        try {
            $expense = $this->groupExpenseService->update(
                $id,
                $request->validated(),
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Expense updated successfully',
                'data' => $expense,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        try {
            $this->groupExpenseService->delete($id, $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function settle(SettleSplitRequest $request, string $splitId): JsonResponse
    {
        try {
            $split = $this->groupExpenseService->settle(
                $splitId,
                $request->validated('amount'),
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'data' => $split,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }
}
