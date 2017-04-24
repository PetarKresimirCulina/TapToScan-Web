<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\ProductOrder;
use App\Tag;
use App\User;
use App\Currency;
use App\Order;
use App\Subscription;
use Validator;
use Response;
use Auth;

use Braintree_WebhookNotification;

/**
     * API Controller
     */
class ApiController extends Controller
{
	/**
     * getUserData
	* @param Request $request ['tagID' => 'required|min:8|max:8'];
	* @return JSON response
     */
    public function getUserData(Request $request) //accepts only TagID!
	{
		if ($request->isMethod('post'))
		{
			$rules = ['tagID' => 'required|min:8|max:8'];
			
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails())
			{
				return Response::json(array(
							'status'  => '500',
							'message'  => 'Invalid data provided.'
				));
			}
			$tagID = $request['tagID'];
			if(!$tagID)
			{
				return Response::json(array(
							'status'  => '500',
							'message'  => 'Tag data missing.'
				));
			}
			$tag = Tag::where('id', $tagID)->first();
			if(!$tag)
			{
				return Response::json(array(
							'status'  => '500',
							'message'  => 'Tag not found.'
				));
			}
			$user = User::where('id', $tag->user)->select('business_name', 'address', 'city', 'zip', 'country')->first();
			
			if(!$user)
			{
				return Response::json(array(
							'status'  => '500',
							'message'  => 'User not fount.'
				));
			}
			
			$categories = Category::where('user', $tag->user)->orderBy('name')->get(['id', 'icon_id', 'user', 'name']);
			if(!$categories)
			{
				return Response::json(array(
							'status'  => '500',
							'message'  => 'No categories found.'
				));
			}

			foreach($categories as $category)
			{
				$category->icon_res = $category->getIconRes();
				unset($category->icon);
				$products = $category->products;
				
				foreach($products as $product)
				{
					$product->symbol = Currency::where('id', $product->currency_id)->first();
				}
			}
			
			return Response::json(array(
							'status'  => 'ok',
							'message'  => 'success',
							'tag' => $tag,
							'user' => $user,
							'categories' => $categories
			));
		}	
	}
	/**
     * addOrder
	* @param Request $request ['userID' => 'required|numeric', 'tagID' => 'required|max:8|min:8', 'productOrders' => 'required'];
	* @return JSON response
     */
	 public function addOrder(Request $request) //accepts userID, tagID, productOrders array sa 2 podlevela - ProductID i Quantity
	{
		if ($request->isMethod('post'))
		{
			$rules = ['userID' => 'required|numeric',
					'tagID' => 'required|max:8|min:8',
					'productOrders' => 'required'];
			
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails())
			{
				return Response::json(array(
							'status'  => '500',
							'message'  => 'Invalid data provided. Validate ' . $request->all(),
				));
			}
			
			$user = $request['userID'];
			$tag = Tag::where('id', $request['tagID'])->where('user', $user)->first();
			$productOrders = $request['productOrders'];
			// check if valid tag has been given
			if($tag)
			{
				$order = new Order();
				$tableName = $tag->getTableName($tag->id);
				if($order->createOrder($user, $tag->id))
				{
					$po = new ProductOrder();
					if($po->createProductOrder($productOrders, $order->id))
					{
						$i = 0;
						foreach($productOrders as $product)
						{
							$productOrders[$i]['name'] = Product::where('id', $product['id'])->pluck('name')->first();
							$i++;
						}

						event(new \App\Events\OrderPlaced(['order' => $order,
										'table' => $tableName,
										'productOrders' => $productOrders,
						]));
						
						return Response::json(array(
								'status'  => '200',
								'message'  => 'success',
						));
						
						
					}
				}
			}
			
			
			return Response::json(array(
				'status'  => '500',
				'message'  => 'Failed to place an order.'
			));
		}
	}
	
	public function handleBraintreeWebhook(Request $request) {
		if(isset($_POST["bt_signature"]) && isset($_POST["bt_payload"])) {
			$webhookNotification = Braintree_WebhookNotification::parse(
				$_POST["bt_signature"], $_POST["bt_payload"]
			);

			switch($webhookNotification->kind) {
				case Braintree_WebhookNotification::CHECK:
					$msg = "Test notification";
					break;
				case Braintree_WebhookNotification::SUBSCRIPTION_CANCELED:
					$user = Subscription::where('braintree_id', $webhookNotification->subscription->id)->first()->user;
					if($user != null) {
						//$user->subscriptionCanceled();
					}
					break;
				case Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY:
					// Send email receipt
					// Set blocked = 0
					// Also check on subscription create if user blocked
					
					$msg = "Subscription charged successfuly";
					break;
				case Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_UNSUCCESSFULLY:
					// Do nothing atm, comment out the case
					$msg = "Subscription charge failed";
					break;
				case Braintree_WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE:
					// set blocked = 1 for user
					// TO-DO: Add notification in dashboard
					$msg = "Subscription went past due";
					break;
				default:
					break;
			}
		}
	}
}
