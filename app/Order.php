<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Item;
use App\Tag;

/**
     * Order
	* Model that handles orders data
     */
class Order extends Model
{
	//protected $hidden = ['pivot'];
	protected $table = 'orders';
	
	/**
     * products
	* Retrieves a category that this product belongs to. Used by Laravel eloquent model.
     */
	public function products()
	{
		return $this->belongsToMany('App\Product', 'product_orders', 'order_id', 'product_id')->withPivot('quantity');
	}
	
	/**
     * updateStatus
	* Updates status from 0-not server to 1-served
	* @params int $id, int $user
	* @return String response
     */
	public function updateStatus($id, $user)
	{
		$order = $this->where('id', $id)->where('userID', $user)->where('status', 0)->first();
		if($order)
		{
			$order->status = 1;
			if($order->save())
			{
				return 'Success';
			}
			return 'Failed to update';
		}
		return 'No records found';
	}
	
	/**
     * getTableName
	* Gets a tag's table name, calls getTableName from Tag model
	* @params int $id
	* @return String Tag->name
     */
	public function getTableName($id)
	{
		$tag = new Tag();
		return $tag->getTableName($id);
	}
	
	/**
     * createOrder
	* Creates a new order
	* @params int $user, int $tag
	* @return boolean on fail or Order on success
     */
	public function createOrder($user, $tag)
	{
		$userDb = User::find($user);
		$tagDb = Tag::find($tag);
		if($userDb && $tagDb)
		{
			$this->userID = $user;
			$this->tagID = $tag;
			if($this->save())
			{
				return $this;
			}
		}
		return false;
	}
}