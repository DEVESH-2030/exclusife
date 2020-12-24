<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taxtype extends Model
{
    protected $table = 'taxtypes';
    protected $fillable = [
       'taxtype','status'
      ];
}
