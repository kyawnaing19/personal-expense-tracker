<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    )
    {}
    public function getSummary(Request $request):JsonResponse
    {
        $result=$request->only(['filter','from','to']);

        try{
            $summaries=$this->reportService->getSummary($request->user()->id,$result);
            return response()->json([
            'success'=>true,
            'data'=>$summaries,

        ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ],(int) $e->getCode());
        }

    }

    public function getCategoryBreakdown(Request $request):JsonResponse
    {
        $result=$request->only(['filter','from','to']);

        try
        {
            $categories_breakdown=$this->reportService->getCategoryBreakdown($request->user()->id,$result);
            return response()->json([
                'success'=>true,
                'data'=>$categories_breakdown,
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ]);

        }
    }

    public function getAnnualSummary(Request $request):JsonResponse
    {
        $result=$request->only(['filter','from','to']);
        try
        {
            $annual_summary=$this->reportService->getAnnualSummary($request->user()->id,$result);
            return response()->json([
                'success'=>true,
                'data'=>$annual_summary
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ]);
        }
    }

    public function getBudgetOverview(Request $request):JsonResponse
    {
        $userId=$request->user()->id;
        $filters=$request->only(['month','year']);
       try
       {
            $budget_overview=$this->reportService->getBudgetOverview($userId,$filters);
            return response()->json([
                'success'=>true,
                'data'=>$budget_overview
            ]);
       }
       catch(\Exception $e)
       {
            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage()
            ]);
       }
    }
}
