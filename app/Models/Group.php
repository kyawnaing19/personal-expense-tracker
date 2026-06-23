<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasUlids;
    protected $fillable = [
        'name',
        'created_by',
        'join_code',
        'join_code_expires_at',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function members()
    {
        return $this->belongsToMany(User::class, 'group_user')
                    ->withPivot('role', 'joined_at')
                    ->withTimestamps();
    }

    public function groupUsers()
    {
        return $this->hasMany(GroupUser::class);
    }

    // public function expenses()
    // {
    //     return $this->hasMany(GroupExpense::class);
    // }

}
