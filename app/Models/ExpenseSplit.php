<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use League\OAuth1\Client\Server\Tumblr;

class ExpenseSplit extends Model
{
    use HasUlids;
    protected $fillable = [
        'group_expense_id','user_id','amount_owed','amount_paid','is_settled','settled_at'];
    protected $casts = [
        'is_settled'=>'boolean',
        'setteld_at'=>'datetime'
    ];

    public function groupExpense(){
        return $this->belongsTo(GroupExpense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function settlementRequests(){
        return $this->hasMany(SettlementRequest::class);
    }

}
