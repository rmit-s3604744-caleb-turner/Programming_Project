<?php

namespace MovieBuffs;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public function user1(){
		return $this->hasOne('MovieBuffs\User');
	}
	public function user2(){
		return $this->hasOne('MovieBuffs\User');
	}
}
