<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class GroupExpense extends Model
{
    use HasUlids;
    protected $fillable = ['group_id','paid_by','category_id','amount','description',
                        'expense_date','split_type','include_payer'];

    protected $casts = [
        'include_payer'=>'boolean',
        'expense_date'=>'date',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class,'paid_by');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function splits()
    {
        return $this->hasMany(ExpenseSplit::class);
    }
}
