<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'uid', 'fid'
    ];
}
