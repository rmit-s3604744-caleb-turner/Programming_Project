<?php

namespace MovieBuffs;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cmgmyr\Messenger\Traits\Messagable;
use Hootlex\Friendships\Traits\Friendable;

class User extends Authenticatable
{
    use Notifiable;
	use Messagable;
	use Friendable;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'preferenceSet'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	
	public function posts(){
		return $this->hasMany('MovieBuffs\Post');
	}
	
	
	public function details(){
		return $this->hasOne('MovieBuffs\UserDetail');
	}
	
	
	public function preferences(){
		return $this->hasOne('MovieBuffs\UserPreference');
	}
	
}
