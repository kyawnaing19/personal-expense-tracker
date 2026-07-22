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

    public function getAll(string $userId)
    {
        return $this->categoryRepository->getAllByUser($userId);
    }

    public function findById(string $id, string $userId,)
    {
        return $this->categoryRepository->findById($id, $userId);

    }

    public function create(array $data, string $userId)
    {
        $data['user_id']=$userId;
        //Log::info('category data',$data);
        return $this->categoryRepository->create($data);

    }

    public function update(string $id,array $data, string $userId)
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

    public function delete(string $id,string $userId)
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
        if ($category->transactions()->exists()) {
        throw new \Exception('Cannot delete category with associated transactions!');
        }
        if($category->recurringTransactions()->exists()){
            throw new \Exception('Cannot delete category with associated recurring transactions!');
        }
        if($category->budgets()->exists()){
            throw new \Exception('Cannot delete category with associated budgets!');
        }

        return $this->categoryRepository->delete($category);

    }


}
