<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exempted extends Model
{
    protected $table = 'exempteds';
    protected $fillable = [
       'exempted','status'
      ];
}
