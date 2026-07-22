<?php
namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryRepository
{
    public function getAllByUser(string $userId)
    {
        return Category::where('user_id',$userId)
                        ->orWhereNull('user_id')
                        ->get();
    }

    public function findById(string $id, string $userId)
    {
        return Category::where('id',$id)
                        // ->where('user_id',$userId)
                        // ->orWhereNull('user_id')->orWhereNull('user_id')
                        ->where(function($query)use ($userId)
                        {
                            $query->where('user_id',$userId)
                                    ->orWhereNull('user_id');
                        })
                        ->first();
    }

    public function create(array $data)
    {
       // Log::info('XXXXXXXXXxx',$data);
        return Category::create($data);
    }

    public function update(Category $category, array $data)
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category)
    {
        $category->delete();
        return true;
    }

}
