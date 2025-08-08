<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectedShop extends Model
{
    protected $fillable = ['user_id','shop','access_token'];

    protected $casts = [
        'access_token' => 'encrypted',
    ];
}
