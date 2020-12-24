<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    

    protected $table = 'products';
    protected $fillable = [
       'code' , 'name', 'category_id','exempted_id','tax_type_id','tax_percent',
       'cess_percent','quantity','purchase_cost','mrp','hsn_code','uom',
       'description','status'
      ];
}
