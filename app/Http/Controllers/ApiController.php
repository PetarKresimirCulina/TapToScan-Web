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
use App\Invoice;
use App\InvoiceItem;
use App\Plan;
use Braintree_WebhookNotification;
use Storage;
use Mail;

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
			$user = User::where('id', $tag->user)->select('business_name', 'address', 'city', 'zip', 'country', 'blocked', 'canceled')->first();
			
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
					// none
					break;
				case Braintree_WebhookNotification::SUBSCRIPTION_CANCELED:
					$user = Subscription::where('braintree_id', $webhookNotification->subscription->id)->first()->user;
					if($user != null) {
						$user->cancel();
					}
					break;
				case Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY:
					$user = Subscription::where('braintree_id', $webhookNotification->subscription->id)->first()->user;
					if($user != null) {
						$user->unblock();
						
						
						// Code below handles just one invoice item - subscription
						$invoice = new Invoice();
						$invoice->create($user->id, $user->taxPercentage(), 0, 0, 1, 1);
						$price = $user->plan->price;
						$plan = Plan::where('id', $user->plan_id)->first();
						$desc = 'Pretplatnički paket/Subscription package ' . $plan->name;
						
						// Create new invoice item - just one in the case of subscription
						$it = new InvoiceItem();
						$it->create($invoice->id, $desc, $plan->price, $plan->getCurrency->code, 1);
						
						$invoice->totalNet = $plan->price;
						$invoice->totalWVat = $plan->price + ($plan->price * ($user->taxPercentage()/100));
						$invoice->save();
						
						
						$pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('emails.bill', ['invoice' => $invoice]);
			
						$date = \Carbon\Carbon::now();
						$result = $date->format('Y-m-d H-i-s');
						$filename = $result . '-' . $invoice->id  . '.pdf';
						
						Storage::disk('invoices')->put($filename, $pdf->output());
						
						Mail::send('emails.invoiceNotification', ['user' => $user], function($message) use($user, $pdf)
						{
							$message->from('no-reply@taptoscan.com', 'TapToScan.com');

							$message->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Your invoice is ready');

							$message->attachData($pdf->output(), "invoice.pdf");
						});
					}
					break;
				case Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_UNSUCCESSFULLY:
					// Do nothing atm, comment out the case
					$msg = "Subscription charge failed";
					break;
				case Braintree_WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE:
					$user = Subscription::where('braintree_id', $webhookNotification->subscription->id)->first()->user;
					if($user != null) {
						$user->block();
					}
					break;
				default:
					break;
			}
		}
	}
	
	public function test(){
		
		$sample_notification = \Braintree\WebhookTesting::sampleNotification(
			Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY,
			"6t8ztw"
		);
		
		
		
		$webhookNotification = Braintree_WebhookNotification::parse(
			$sample_notification["bt_signature"], $sample_notification["bt_payload"]
		);
		
		//var_dump($webhookNotification);
		$user = Subscription::where('braintree_id', $webhookNotification->subscription->id)->first()->user;
		if($user != null) {
			$user->unblock();
			
			$plan = Plan::where('id', $user->plan_id)->first();
			// Code below handles just one invoice item - subscription
			$invoice = new Invoice();
			$invoice->create($user->id, $user->taxPercentage(), 0, 0, 1, 1, $plan->currency);
			$price = $user->plan->price;
			$desc = 'Pretplatnički paket/Subscription package ' . $plan->name;
			
			// Create new invoice item - just one in the case of subscription
			$it = new InvoiceItem();
			$it->create($invoice->id, $desc, $plan->price, $plan->getCurrency->code, 1);
			
			$invoice->totalNet = $plan->price;
			$invoice->totalWVat = $plan->price + ($plan->price * ($user->taxPercentage()/100));
			$invoice->save();

			$pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('emails.bill', ['invoice' => $invoice]);
			
			$date = \Carbon\Carbon::now();
			$result = $date->format('Y-m-d H-i-s');
			$filename = $result . '-' . $invoice->id  . '.pdf';
			
			Storage::disk('invoices')->put($filename, $pdf->output());
			
			Mail::send('emails.invoiceNotification', ['user' => $user], function($message) use($user, $pdf)
			{
				$message->from('no-reply@taptoscan.com', 'TapToScan.com');

				$message->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Your invoice is ready');

				$message->attachData($pdf->output(), "invoice.pdf");
			});

		}
		
	}
}
