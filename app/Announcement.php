<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
	protected $table = 'announcements';
    protected $fillable = [
    	'category_id',
    	'announce_title',
    	'type',
    	'only_customer',
    	'only_whitelisted',
    	'text_area',
    	'name',
    	'stat_date',
    	'end_date',
    ];
}
