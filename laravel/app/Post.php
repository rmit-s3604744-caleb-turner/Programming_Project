<?php

namespace MovieBuffs;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //	Table Name
	protected $table = 'posts';
	
	// PK 
	public $primaryKey = 'id';
	
	// Timestamps
	public $timestamps = true;
	
	
	public function user(){
		return $this->belongsTo('MovieBuffs\User');
	}
}
