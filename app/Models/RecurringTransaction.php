<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class RecurringTransaction extends Model
{
    use HasUlids;
    protected $fillable = [
        'user_id','category_id','amount','type','note','start_date','end_date','frequency','next_run_date','is_active',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'recurring_id');
    }
}
