<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
     * Icon
	* Icon model that is used for category icons
     */
class Icon extends Model
{
	/**
     * category
	* Used by Laravel Eloquent model
     */
	public function category()
    {
        return $this->belongsTo('App\Category');
    }
	
}
