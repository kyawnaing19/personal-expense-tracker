<?php
namespace App\Services;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Log;

class CategoryService
{

    public function __construct(
        private CategoryRepository $categoryRepository
    )
    {}

    public function getAll(int $userId)
    {
        return $this->categoryRepository->getAllByUser($userId);
    }

    public function findById(int $id, int $userId,)
    {
        return $this->categoryRepository->findById($id, $userId);

    }

    public function create(array $data, int $userId)
    {
        $data['user_id']=$userId;
        //Log::info('category data',$data);
        return $this->categoryRepository->create($data);

    }

    public function update(int $id,array $data, int $userId)
    {
       $category=$this->categoryRepository->findById($id, $userId);
        if (!$category) {
            throw new \Exception('category not found!',404);
        }
        if($category->is_default)
            {
                throw new \Exception('default category cannot update!',403);
            };

        return $this->categoryRepository->update($category,$data);

    }

    public function delete(int $id,int $userId)
    {
           $category=$this->categoryRepository->findById($id, $userId);
        if (!$category)
            {
            throw new \Exception('category not found!',404);
            }
        if($category->is_default)
            {
                throw new \Exception('default category cannot delete!',403);
            };

        return $this->categoryRepository->delete($category);

    }


}
