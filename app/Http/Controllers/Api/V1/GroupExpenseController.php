<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupExpense\ClaimPaymentRequest;
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

    public function showSplitsByUser(Request $request): JsonResponse
    {
        try {
            $splits = $this->groupExpenseService->getSplitsByUser($request->user()->id);

            return response()->json([
                'success' => true,
                'data' => $splits,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function ClaimPayment(ClaimPaymentRequest $request, string $splitId): JsonResponse
    {
        try{
            $settlementRequest=$this->groupExpenseService->claimPayment(
                $splitId,
                $request->validated('amount'),
                $request->user()->id
            );

            return response()->json([
                'success'=>true,
                'message'=>'Payment claim summited, waiting for comfirmation',
                'data'=>$settlementRequest

            ],201);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function confirmPayment(Request $request, string $requestId):JsonResponse
    {
        try{
            $confirmPayment=$this->groupExpenseService->comfirmPayment(
                $requestId,
                $request->user()->id
            );
            return response()->json([
                'success'=>true,
                'message'=>'Payment confirm successfully',
                'data'=>$confirmPayment,

            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function rejectPayment(Request $request, $requestId):JsonResponse
    {
        try{
        $settlementRequest=$this->groupExpenseService->rejectPayment($requestId,
        $request->user()->id);
        return response()->json([
            'success'=>true,
            'message'=>'Payment reject successfully',
            'data'=>$settlementRequest,
        ]);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }

    }


    public function groupBalance(Request $request, string $groupId): JsonResponse
    {
    try {
        $balance = $this->groupExpenseService->getGroupBalance($groupId, $request->user()->id);
        return response()->json([
            'success' => true,
             'data' => $balance
             ]);
        }  catch (\Exception $e) {


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

    public function balanceDetails(Request $request, string $groupId, string $userId): JsonResponse
    {
        try {
        $details = $this->groupExpenseService->getBalanceDetail($groupId, $userId, $request->user()->id);
        return response()->json([
            'success' => true,
            'data' => $details
             ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function balanceHistory(Request $request, string $groupId, string $userId): JsonResponse
    {
    try {
        $history = $this->groupExpenseService->getBalanceHistory($groupId, $userId, $request->user()->id);
        return response()->json([
            'success' => true,
            'data' => $history
            ]);
        } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], (int) $e->getCode() ?: 500);
        }
    }
}
