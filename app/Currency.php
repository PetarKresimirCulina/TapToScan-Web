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

	public function formatCurrency($locale, $value, $code, $symbol)
	{
		
		switch ($locale) {
			case 'en':
				return $code . number_format($value, 2, '.', ',');
				break;
			case 'hr':
				return number_format($value, 2, ',', '.') . ' ' . $symbol;
				break;
			default:
				//en
				return $code . number_format($value, 2, '.', ',');
				break;
		}
	}
}
