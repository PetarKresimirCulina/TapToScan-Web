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
	
	public function plan()
	{
		$this->hasMany('App\Plan', 'currency');
	}

	public function formatCurrency($locale, $value, $code, $symbol)
	{
		
		switch ($locale) {
			case 'en':
				return $code . number_format($value, 2, '.', ',');
				break;
			case 'hr':
				return number_format($value, 2, ',', '.') . $symbol;
				break;
			default:
				//en
				return $code . number_format($value, 2, '.', ',');
				break;
		}
	}
	
	public function convertToHRK($amount) {
		$hnb = file_get_contents('http://www.hnb.hr/tecajn/htecajn.htm');
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $hnb) as $line){
			// do stuff with $line
	
			$parts = preg_split('/\s+/', $line);
			if($parts[0] == '978EUR001') {
				//number_format($parts, 2, ',', '.');

				$parts[2] = str_replace(',', '.', $parts[2]);
				return $amount * floatval($parts[2]);
			}
		}

	}
}
