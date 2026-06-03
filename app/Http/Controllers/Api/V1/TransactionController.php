<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Services\TransactionService;
use Exception;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $transactionService
    )
    {}
     public function index(Request $request):JsonResponse
     {   $filters=$request->only(['month','year','category_id','type']);
         $transactions=$this->transactionService->getAll($request->user()->id,$filters);
         return response()->json([
            'success'=>true,
            'data'=>$transactions,
         ]);
     }
     public function show(Request $request,string $id):JsonResponse
     {
        $transaction=$this->transactionService->findById($id,$request->user()->id);
        if(!$transaction)
            {
                return response()->json([
                'success'=>false,
                'message'=>'transaction not found'
                ],404);
            }
        return response()->json([
        'success'=>true,
        'data'=>$transaction,
        ]);
     }

     public function store(StoreTransactionRequest $request):JsonResponse
     {  try
        {
        $transaction=$this->transactionService->create($request->validated(),$request->user()->id);
        return response()->json([
        'success'=>true,
        'message'=>'Transaction created successfully',
        'data'=>$transaction,
        ]);
        }catch(\Exception $e){
            return response()->json([
            'success'=>false,
            'message'=>$e->getMessage()
            ],(int)$e->getCode()?:500);
        }

     }
     public function update(EditTransactionRequest $request,string $id)
     {
        try{
            $result=$this->transactionService->update(
                $id,
                $request->validated(),
                $request->user()->id);

            return response()->json([
            'success'=>true,
            'message'=>'updated successfully',
            'data'=>$result,
            ]);

        }catch(\Exception $e){
               return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int)$e->getCode() ?: 500);
        }

     }

     public function destroy(Request $request, string $id):JsonResponse
     {
        try
        {
        $result=$this->transactionService->delete(
        $id,
        $request->user()->id,
        );
        return response()->json([
        'success'=>true,
        'message'=>'deleted successfully'
        ]);
        }
        catch(\Exception $e){
            return response()->json([
            'success'=>'false',
            'message'=>$e->getMessage()
            ],(int)$e->getCode() ?: 500 );
        }




     }


}
