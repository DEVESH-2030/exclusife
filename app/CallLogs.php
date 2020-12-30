<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallLogs extends Model
{
	protected $table = 'call_logs';
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



