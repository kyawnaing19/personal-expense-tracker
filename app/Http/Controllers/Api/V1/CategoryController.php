<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryService $categoryService
    )
    {}

    public function index(Request $request):JsonResponse
    {
        $categories=$this->categoryService->getAll($request->user()->id);
         return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    public function show(Request $request,int $id):JsonResponse
    {
        $category=$this->categoryService->findById($id, $request->user()->id);
        if(!$category){
            return response()->json([
            'success'=>false,
            'message'=>'category not found',
            ],404);
        }
        return response()->json([
            'success'=>true,
            'data'=>$category,
        ]);
    }

    public function store(StoreCategoryRequest $request):JsonResponse
    {
        $category=$this->categoryService->create(
            $request->validated(),
            $request->user()->id
        );
        return response()->json([
            'success'=>true,
            'message'=>'new category created successfully',
            'data'=>$category,

        ]);


    }

    public function update(UpdateCategoryRequest $request,int $id):JsonResponse
    {
        try{
            $result=$this->categoryService->update(
                $id,
                $request->validated(),
                $request->user()->id,

            );
            return response()->json([
                'success'=>true,
                'message'=>"updated successfully",
                'data'=>$result,
            ]);
        }
        catch(\Exception $e){
               return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int)$e->getCode() ?: 500);
        }
    }

    public function destory(Request $request,int $id):JsonResponse{
        try{
            $result=$this->categoryService->delete(
                $id,
                $request->user()->id,
            );
            return response()->json([
                'succcess'=>true,
                'mesaage'=>'deleted successfully',
            ]);

        }
        catch(\Exception $e){
              return response()->json([
                 'success' => false,
                'message' => $e->getMessage(),
            ], (int)$e->getCode() ?: 500);

        }
    }




}
