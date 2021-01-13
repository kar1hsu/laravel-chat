<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $table = 'friend';
    protected $fillable = [
        'user_id',
        'friend_user_id',
        'created_at',
    ];
    public $timestamps = false;
}
