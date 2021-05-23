<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionPermissionModel extends Model
{
    protected $fillable = [
        'user',
        'tiquet'
    ];
}
