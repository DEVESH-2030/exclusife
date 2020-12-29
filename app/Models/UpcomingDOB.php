<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpcomingDOB extends Model
{
	protected $table = 'upcoming_dobs';
    protected $fillable = [
    	'user_id',
    	'name',
    	'today_date',
    	'upcomingdate_date',
    	'created_at',
    	'updated_at',
    ];
}
