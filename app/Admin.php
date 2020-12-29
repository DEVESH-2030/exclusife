<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
	protected $table = 'admins';
    protected $fillable = [
    	'name', 
    	'mobile',
    	'email', 
    	'password',
    	'otp', 
    	'verify_otp', 
    	'deleted_at',
     	'created_at', 
   	 	'updated_at'
   	];
}
