<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallLogs extends Model
{
	protected $table = '';
    protected $fillable = [
    	'mobile',
    	'call_duration',
    	'recieved_call',
    	'rejected_call',
    	'missed_call',
    	'contacts',
    	'created_at',
    	'updated_at',
    ];
}
