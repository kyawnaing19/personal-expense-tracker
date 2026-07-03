<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    use HasUlids;
    protected $fillable = [
        'user_id','category_id','amount','type','note','transaction_date','receipt_path','recurring_id','status'
    ];

    protected $attributes = ['status'=>'confirmed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function recurringTransaction()
    {
        return $this->belongsTo(RecurringTransaction::class, 'recurring_id');
    }
}
