<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginUser extends Model
{
    protected $table = "login_users";
    protected $fillable = [
        'mobile', 'password','active'
    ];
}
