<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    use HasUlids;
    protected $table = 'group_user';
    protected $fillable = [
        'group_id',
        'user_id',
        'role',
        'joined_at',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
