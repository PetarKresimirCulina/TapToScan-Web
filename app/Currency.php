<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
     * Currency
	* Currency model that is used for product prices
	*/
class Currency extends Model
{
	/**
     * product
	* Used by Laravel Eloquent model
     */
    public function product()
	{
		$this->hasMany('App\Product', 'currency_id');
	}
}
