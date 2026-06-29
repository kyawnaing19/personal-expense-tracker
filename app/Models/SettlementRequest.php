<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class SettlementRequest extends Model
{
    use HasUlids;
    protected $fillable = [
        'expense_split_id','claimed_by','amount',
        'status','comfirmed_by','comfirmed_at'
    ];
    public function expenseSplit()
    {
        return $this->belongsTo(ExpenseSplit::class);
    }

    public function cliamant()
    {
        return $this->belongsTo(User::class,'claimed_by');

    }

    public function comfirmer()
    {
        return $this->belongsTo(User::class, 'comfirmed_by');
    }
}
