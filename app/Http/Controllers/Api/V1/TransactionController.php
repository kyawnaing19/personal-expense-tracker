<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Services\RecurringTransactionService;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $transactionService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['filter', 'from', 'to', 'category_id', 'type']);
        $transactions = $this->transactionService->getAll($request->user()->id, $filters);

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $transaction = $this->transactionService->findById($id, $request->user()->id);
        if (! $transaction) {
            return response()->json([
                'success' => false,
                'message' => 'transaction not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction,
        ]);
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {
            $transaction = $this->transactionService->create($request->validated(), $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully',
                'data' => $transaction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }

    }

    public function uploadReceipt(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'receipt' => ['required', 'image', 'max:2048'],
        ]);
        $transaction = $this->transactionService->findById($id, $request->user()->id);
        if (! $transaction) {
            return response()->json([
                'success' => false,
                'message' => 'transaction not found',
            ], 404);
        }

        if ($transaction->receipt_path) {
            Storage::delete($transaction->receipt_path);
        }
        $path = $request->file('receipt')->store('receipt', 'public');

        try {
            $transaction = $this->transactionService->update(
                $id,
                ['receipt_path' => $path],
                $request->user()->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Receipt uploaded successfully',
                'data' => ['receipt_url' => asset('storage/'.$path)],

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ]);
        }
    }

    public function deleteReceipt(string $id, Request $request):JsonResponse
    {
        $transaction=$this->transactionService->findById($id,$request->user()->id);
        if(!$transaction){
            return response()->json([
                'success'=>false,
                'message'=>'transaction not found'
            ],404);
        }
        if($transaction->receipt_path)
            {
                Storage::delete($transaction->receipt_path);
            }
        $transaction=$this->transactionService->update(
            $id,
            ['receipt_path'=>null],
            $request->user()->id
        );
        return response()->json([
            'success'=>true,
            'message'=>'Receipt deleted successfully'
        ]);
    }

    public function update(EditTransactionRequest $request, string $id)
    {
        try {
            $result = $this->transactionService->update(
                $id,
                $request->validated(),
                $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => $result,
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
            $result = $this->transactionService->delete(
                $id,
                $request->user()->id,
            );

            return response()->json([
                'success' => true,
                'message' => 'deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }

    }

    public function getRecurringTransactions(Request $request): JsonResponse
    {
        try {

            $result = $this->transactionService->getRecurringTransactions(
                $request->user()->id,
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }


    public function accept(string $id, Request $request): JsonResponse
    {
        try {
            $result = $this->transactionService->accept(
                $id,
                $request->user()->id,
            );

            return response()->json([
                'success' => true,
                'message' => 'accepted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }

    public function reject(string $id, Request $request): JsonResponse
    {
        try {
            $result = $this->transactionService->reject(
                $id,
                $request->user()->id,
            );

            return response()->json([
                'success' => true,
                'message' => 'rejected successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 'false',
                'message' => $e->getMessage(),
            ], (int) $e->getCode() ?: 500);
        }
    }
}
