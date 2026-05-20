<?php
namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAllByUser(int $userId)
    {
        return Category::where('user_id',$userId)
                        ->orWhereNull('user_id')
                        ->get();
    }

    public function findById(int $id, int $userId)
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
        return Category::create(
            [
                'name'=>$data['name'],
                'type'=>$data['type'],
                'icon'=>$data['icon'],
                'color'=>$data['color'],
            ]
        );
    }

    public function update(Category $category, array $data)
    {
        $category->update(
            [
                'name'=>$data['name'],
                'type'=>$data['type'],
                'icon'=>$data['icon'],
                'color'=>$data['color'],
            ]

        );
        return $category;
    }

    public function delete(Category $category)
    {

        // if ($category->transactions()->exists()) {
        // throw new \Exception();
        // }
        $category->delete();
        return true;
    }

}
