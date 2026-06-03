<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Services\BudgetService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BudgetController extends Controller
{
    public function __construct(
        private BudgetService $budgetService
    ) {

    }
    public function index(Request $request):JsonResponse
    {
        $filters=$request->only(['month','year']);
        try
        {
            $budgets=$this->budgetService->getAllByUser(
                $request->user()->id,
                $filters);
            return response()->json([
                'success'=>true,
                'data'=>$budgets

            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage(),
            ],500);

        }

    }

    public function store(StoreBudgetRequest $request): JsonResponse
    {
        try {

            $budgets = $this->budgetService->upsert($request->validated(), $request->user()->id);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'amount created successfully',
                    'data' => $budgets,
                ],201
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],500

            );
        }
    }

    public function update(UpdateBudgetRequest $request, string $id): JsonResponse
    {
        try {
            $budgets = $this->budgetService->update($id, $request->validated(), $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'updated successfully',
                'data' => $budgets,
            ]);

        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ], (int) $e->getCode() ?: 500

            );

        }
    }
    public function destroy(Request $request,string $id):JsonResponse
    {
        try
        {
            $this->budgetService->delete(
                $id,
                $request->user()->id,

            );
            return response()->json(
                [
                    'success'=>true,
                    'message'=>'deleted successfully'
                ]
            );
        }
        catch(\Exception $e)
        {
            return response()->json(
                [
                    'success'=>false,
                    'message'=>$e->getMessage(),
                ],(int)$e->getCode()?:500
            );
        }

    }
}
