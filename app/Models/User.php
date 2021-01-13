<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';
    protected $fillable = [
        'uuid',
        'name',
        'avatar',
        'email',
        'password',
        'created_at',
        'updated_at',
    ];
    public $timestamps = true;
}
