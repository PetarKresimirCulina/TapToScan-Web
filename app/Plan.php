<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public function users() //bilo je orders prije pa vrati ako se sve sjebe
	{
		return $this->hasMany('App\User');
	}
}
