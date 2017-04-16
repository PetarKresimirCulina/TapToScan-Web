<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    public function users() //bilo je orders prije pa vrati ako se sve sjebe
	{
		return $this->hasMany('App\User');
	}
}
