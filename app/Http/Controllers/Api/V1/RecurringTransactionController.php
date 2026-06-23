<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecurringTransactionRequest;
use App\Http\Requests\UpdateRecurringTransactionRequest;
use App\Services\RecurringTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecurringTransactionController extends Controller
{
    public function __construct(
        private RecurringTransactionService $recurringTransactionService,
    ) {}

    public function index(Request $request):JsonResponse
    {
        try {
            $filters = $request->only([ 'category_id', 'type']);
            $recurringTransactions = $this->recurringTransactionService->getAllByUser(
                $request->user()->id, $filters);

            return response()->json([
                'success' => true,
                'data' => $recurringTransactions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],(int) $e->getCode() ?: 500);
        }
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $recurringTransaction = $this->recurringTransactionService->findById($id, $request->user()->id);
        if (! $recurringTransaction) {
            return response()->json([
                'success' => false,
                'message' => 'Recurring transaction not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $recurringTransaction,
        ]);
    }

    public function store(StoreRecurringTransactionRequest $request): JsonResponse
    {
        try {
            $recurringTransaction = $this->recurringTransactionService->create(
                $request->validated(), $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Recurring transaction created successfully',
                'data' => $recurringTransaction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],(int) $e->getCode() ?: 500);
        }
    }
    public function update(UpdateRecurringTransactionRequest $request, string $id): JsonResponse
    {
        try {
            $recurringTransaction = $this->recurringTransactionService->update(
                $id, $request->validated(), $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Recurring transaction updated successfully',
                'data' => $recurringTransaction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],(int) $e->getCode() ?: 500);
        }
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        try {
            $this->recurringTransactionService->delete($id, $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Recurring transaction deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ],(int) $e->getCode() ?: 500);
        }
    }
}
