<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
     * ProductOrder
	* Model that handles individual orders of an item
     */
class ProductOrder extends Model
{
	/**
     * createProductOrder
	* Creates a new order for an individual product. Gets called by API.
	* @param Array $products[id, quantity], Order $order
	* $return Tag Collection
     */
	public function createProductOrder($products, $order)
	{
		foreach($products as $product)
		{
			$ord = new ProductOrder();
			$ord->product_id = $product['id']; //id
			$ord->order_id = $order;
			$ord->quantity = $product['quantity'];
			$ord->save();
		}
		return true;
	}
}
