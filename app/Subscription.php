<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public function user() {
		$this->hasOne('App\User');
	}
}