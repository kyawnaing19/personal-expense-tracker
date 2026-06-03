<?php
namespace App\Repositories;

use App\Models\Budget;

class BudgetRepository
{
    public function getAllByUser(string $userId,array $filter=[])
    {
        $query=Budget::where('user_id',$userId);
        if(!empty($filter['month']))
            {
                $query->where('month',$filter['month']);
            }
        if(!empty($filter['year']))
            {
                $query->where('year',$filter['year']);
            }
        return $query->get();
    }
    public function findById(string $id,string $userId){
        return Budget::where('id',$id)->where('user_id',$userId)->first();

    }

    public function upsert(array $data):Budget
    {
        return Budget::updateOrCreate(
            ['user_id'=>$data['user_id'],
             'category_id'=>$data['category_id']??null,
             'month'=>$data['month'],
             'year'=>$data['year']
            ],
            ['amount'=>$data['amount'],
             'alert_percentage'=>$data['alert_percentage']??80,
            ]
        );
    }

    public function update(Budget $budget,array $data)
    {
        $budget->update($data);
        return $budget;
    }

    public function delete(Budget $budget)
    {
        $budget->delete();
        return true;
    }


}
