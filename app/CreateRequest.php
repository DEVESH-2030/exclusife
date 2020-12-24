<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreateRequest extends Model
{
    protected $table="create_requests";
    //using guarded insted of fillable becz each attributes are mass assignable.
    protected $guarded = [ ];
}
