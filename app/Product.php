<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Icon;
use App\Currency;
use App\Category;

/**
     * Product
	* Model that handles individual products.
     */
class Product extends Model
{
	
	/**
     * category
	* Retrieves a category that this product belongs to. Used by Laravel eloquent model.
     */
	public function category()
    {
        return $this->belongsTo('App\Category');
    }
	
	/**
     * category
	* Retrieves a currency that this product has price in (ie. USD, EUR). Used by Laravel eloquent model.
     */
	public function currency()
    {
        return $this->belongsTo('App\Currency');
    }
	
	/**
     * formatCurrency
	* Formats the currency format to the set currency.
     */
	/*public function formatCurrency($val = null)
	{
		if($val == null)
		{
			return number_format($this->price, 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
		}
		else
		{
			return number_format($val, 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
		}
		
	}*/
	
	public function formatCurrency($locale, $value)
	{
		
		switch ($locale) {
			case 'en':
				return $this->currency->code . number_format($value, 2, '.', ',');
				break;
			case 'hr':
				return number_format($value, 2, ',', '.') . ' ' . $this->currency->symbol;;
				break;
			default:
				//en
				return $this->currency->code . number_format($value, 2, '.', ',');
				break;
		}
	}
	
	/**
     * add
	* Adds a new product to the user's category.
	* @param String $name, Float(10,2) $price, int $currency, int $cat, int $user
	* $return boolean
     */
	public function add($name, $price, $currency, $cat, $user)
	{
		$c = new Category();
		$c = Category::where('user', $user)->where('id', $cat)->first();
		if($c)
		{
			$this->name = $name;
			$this->price = $price;
			$this->currency_id = $currency;
			$this->category_id  = $cat;
			return $this->save();
		}
		return false;
	}
	
	/**
     * deleteProduct
	* Deletes the product from the user's category.
	* @param int $id, String $cat, id $user
	* $return boolean
     */
	public function deleteProduct($id, $cat, $user)
    {
		$c = Category::where('id', $cat)->where('user', $user)->first();
		if($c)
		{
			return $c->products->where('category_id', $cat)->find($id)->delete();
		}
		return false;
    }
	
	/**
     * edit
	* Deletes the product from the user's category.
	* @param int $id, int $cat, String $name, Float(10,2) $price, int $currency, int $user
	* $return boolean
     */
	public function edit($id, $cat, $name, $price, $currency, $user)
	{
		$p = Category::where('user', $user)->where('id', $cat)->first()->products->find($id);
		if($p)
		{
			$p->name = $name;
			$p->price = $price;
			$p->currency_id = $currency;
			$p->category_id  = $cat;
			return $p->save();
		}
		return false;
	}
}
