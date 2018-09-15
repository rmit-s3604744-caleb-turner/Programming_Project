<?php

namespace MovieBuffs;

use Illuminate\Database\Eloquent\Model;
use Laravelista\Comments\Commentable;
use willvincent\Rateable\Rateable;
class Profile extends Model
{
    use Commentable;
	use Rateable;
	
	
}
