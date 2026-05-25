<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $transactionService
    )
    {}
     public function index(Request $request):JsonResponse
     {
         $transactions=$this->transactionService->getAll($request->user()->id);
         return response()->json([
            'success'=>true,
            'data'=>$transactions,
         ]);
     }

}
