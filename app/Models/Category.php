<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasUlids;
    protected $fillable = [
        'user_id','name','type','icon','color','is_default',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function recurringTransactions()
    {
        return $this->hasMany(RecurringTransaction::class);
    }
    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
}
