<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Order;
use App\Tag;
use App\Plan;
use App\Country;
use App\Currency;
use App\Invoice;
use App\InvoiceItem;
use App\TagOrder;
use Auth;
use emailVerification;
use Carbon\Carbon;
use Session;
use Validator;
use Illuminate\Support\Facades\Input;
use Response;

use Redirect;
use Lang;
use App\Notifications\SendEmailVerification;
use Braintree_Subscription;
use Braintree_SubscriptionSearch;
use Storage;
use Mail;
use Hash;


/**
 *  HomeController class is called from routes. It is used on routes that are available in the user dashboard after the user logs in, such as Order History, Billing etc.
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return return view('dashboard.home')
     */
    public function index()
    {
		$orders = Auth::user()->orders()->where('status', 0)->get();
        return view('dashboard.home')->with('orders', $orders);
    }
	
	public function verifyEmail($lang, $token)
	{
		Auth::user()->verified($token);
		return redirect()->route('dashboard.home', App::getLocale());
	}
	
	public function displayEmailVerification() {
		return view('auth.emailVerify');
	}
	
	public function displayUserSetup() {
		$country = Auth::user()->country;
		return view('auth.setup')->with('country', $country);
	}
	
	public function setupUser(Request $request)
	{
		if ($request->isMethod('post')){
			
			if(Auth::user()->getCountry->eu == 1) {
				$rules = ['first_name' => 'required|min:2',
						'last_name' => 'required|min:2',
						'legalName' => 'required|min:5',
						'name' => 'required|min:2',
						'vatID' => 'required',
						'address' => 'required|min:2',
						'zip' => 'required|numeric',
						'countryCode' => 'required',
						'city' => 'required|min:2',];
			}
			else {
				$rules = ['first_name' => 'required|min:2',
					'last_name' => 'required|min:2',
					'legalName' => 'required|min:5',
					'name' => 'required|min:2',
					'address' => 'required|min:2',
					'zip' => 'required|numeric',
					'city' => 'required|min:2',];
			}
				
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}

			if(Auth::user()->setupUser($request)) {
				return redirect()->route('user.setup.plan', App::getLocale());
			}
		}
	}
	
	public function displayUserSetupPlans() {
		if(Auth::user()->country == 'HR') {
			$plans = Plan::where('display', 1)->where('currency', 28)->get();
		} else {
			$plans = Plan::where('display', 1)->where('currency', 38)->get();
		}
		return view('auth.setupPlan')->with('plans', $plans);
	}
	
	public function resendVerification()
	{
		$user = Auth::user();
		$user->notify(new SendEmailVerification($user->confirmation->code));
		return Redirect::back();
	}
	
	public function updatePassword(Request $request)
	{
		if ($request->isMethod('post')){
			
			$rules = ['password' => 'required|min:5',
					'password_new' => 'required|min:5|confirmed',
					'password_new_confirmation' => 'required|min:5'];
				
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			$user = Auth::user();
			
			if (Hash::check($request['password'], $user->password)) {
				
				if($user->updatePassword(bcrypt($request['password_new'])))
				{
					Session::flash('success', __('dashboardSettings.passwordChangedSuccess') );
					return Redirect::back();
				}
			}
			return Redirect::back()->withErrors(['message' => __('dashboardSettings.passwordIncorrect')]);
		}
		return Redirect::back()->withErrors(['message' => __('dashboardSettings.failedToUpdateUserInfo')]);
	}
	
	public function updateUserInformation(Request $request)
	{
		if ($request->isMethod('post')){
			
			$rules = ['name' => 'required|min:2',
					'address' => 'required|min:2',
					'zip' => 'required|numeric',
					'city' => 'required|min:2',];
				
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			$user = Auth::user();
			
			if($user->updateInfo($request['name'], $request['address'], $request['city'], $request['zip']))
			{	
				Session::flash('alert-success', 'User information has been updated.' );
				return Redirect::back();
			}
			else {
				return Redirect::back()->withErrors(['message' => 'Failed to update user info.']);
			}
		}
		return Redirect::back()->withErrors(['message' => __('dashboardSettings.failedToUpdateUserInfo')]);
	}
	
	/**
     * Show orders history for the user
     *
	* @param Request $request
     * @return view('dashboard.history')
     */
	public function history(Request $request)
    {
		$period = $request->input('period');

		/**
		 * Show orders based on the selected time period filter (30 days, 90 days, all, today)
		 */
		switch ($period) {
			case '30':
				$date= Carbon::now()->subDays(30)->format('Y-m-d H:i:s'); 
				$orders = Auth::user()->orders()->where('status', 1)->where('created_at', '>=', $date)->paginate(10);
				$listFilter = $period;
				$period = Lang::get('dashboardHistory.30days');
				break;
			case '90':
				$date= Carbon::now()->subDays(90)->format('Y-m-d H:i:s'); 
				$orders = Auth::user()->orders()->where('status', 1)->where('created_at', '>=', $date)->paginate(10);
				$listFilter = $period;
				$period = Lang::get('dashboardHistory.90days');
				break;
			case 'all':
				$orders = Auth::user()->orders()->where('status', 1)->paginate(10);
				$listFilter = $period;
				$period = Lang::get('dashboardHistory.allTime');
				break;
			default: //today
				$today = Carbon::today();
				$orders = Auth::user()->orders()->where('status', 1)->where('created_at', '>=', $today)->paginate(10);
				$listFilter = 'today';
				$period =Lang::get('dashboardHistory.today');
				break;
		}
		
        return view('dashboard.history')->with('orders', $orders)->with('period', $period)->with('listFilter', $listFilter);
    }
	
	/**
		* Show user's billing area
		*
		* @return view('dashboard.billing');
	*/
	public function billing()
    {
		$subscription = Braintree_Subscription::find(Auth::user()->subscription('main')->braintree_id);
        return view('dashboard.billing')->with('subscription', $subscription);
    }
	
	public function billingHistory()
    {
		$invoices = Auth::user()->getInvoices;
        return view('dashboard.billingHistory')->with('invoices', $invoices);
    }
	
	public function billingInvoice(Request $request)
    {
		if ($request->isMethod('post')){
			$invoiceId = $request['invoiceId'];

			//$pdf = App::make('snappy.pdf');
			
			$footer = view('emails.footer');
			
			$invoice = Invoice::where('id', $invoiceId)->first();

			$pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('emails.bill', ['invoice' => $invoice]);
			
			$pdf->setOption('footer-html', view('emails.footer'));
			$pdf->setPaper('a4');
			

			return $pdf->download($invoiceId . '.pdf');
		}
    }
	
	public function billingChangePlanDisplayAll()
    {
		if(Auth::user()->blocked == 0) {
			if(Auth::user()->country == 'HR') {
				$plans = Plan::where('display', 1)->where('currency', 28)->get();
				return view('dashboard.billingPlans')->with('plans', $plans);
			}
			$plans = Plan::where('display', 1)->get();
			return view('dashboard.billingPlans')->with('plans', $plans);
		}
		return redirect()->route('dashboard.billing', App::getLocale());
    }
	
	/**
		* Show a view where user can order NFC tags
		*
		* @return view('dashboard.ordertags');
	*/
	public function ordertags()
    {
		if(Auth::user()->getCountry->id == 'HR') {
			
			$price = 8.16;
			$shipping = 33.35;
			$code = 'HRK';
			$symbol = 'kn';
		}
		else {
			
			$price = 1.10;
			$shipping = 4.50;
			$code = 'EUR';
			$symbol = '€';
			
		}

        return view('dashboard.ordertags')->with('price', $price)->with('shipping', $shipping)->with('code', $code)->with('symbol', $symbol);
    }
	
	public function ordertagsCheckout(Request $request)
    {
		if(Auth::user()->getCountry->id == 'HR') {
			
			$price = 8.16;
			$shipping = 33.35;
			$code = 'HRK';
			$symbol = 'kn';
		}
		else {
			
			$price = 1.10;
			$shipping = 4.50;
			$code = 'EUR';
			$symbol = '€';
			
		}

        
		if ($request->isMethod('post')){
			
			$rules = ['address' => 'required|min:2',
					'zip' => 'required|numeric',
					'city' => 'required|min:2',
					'numOfTags' => 'required|min:1|max:100'];
				
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			$user = Auth::user();
			
			if($user->country == 'HR') { $currency = 'HRK'; $merchant = 'taptoscancomHRK'; }
			else { $currency = 'EUR'; $merchant = 'taptoscan'; }
			
			$total = (($price * intval($request['numOfTags'])) + floatval($shipping));
			//return $total;
			
			try {
				$response = $user->charge($total, ['taxAmount' => round(($total*$user->taxPercentage())/100, 2),
												'merchantAccountId' => $merchant,
												'billing' => ['firstName' => $user->first_name,
																'lastName' => $user->last_name,
																'company' => $user->legalName,
																'streetAddress' => $user->address,
																'locality' => $user->city,
																'postalCode' => $user->zip,
																'countryCodeAlpha2' => $user->country],
												'shipping' => ['firstName' => $user->first_name,
																'lastName' => $user->last_name,
																'company' => $user->legalName,
																'streetAddress' => $user->address,
																'locality' => $user->city,
																'postalCode' => $user->zip,
																'countryCodeAlpha2' => $user->country]]);
			}
			catch(\Exception $e) {
				var_dump($e);
				if(strpos($e, 'Credit card type is not accepted by this merchant account') !== false) {
					return Redirect::back()->withErrors(['message' => Lang::get('dashboardBilling.creditCardErrorNotAccepted')]);
				}
				else {
					return Redirect::back()->withErrors(['message' => Lang::get('dashboardBilling.chargeFail')]);
				}
			}
			
			$invoice = new Invoice();
			$c = Currency::where('code', $currency)->first();
			$invoice->create($user->id, $user->taxPercentage(), 0, 0, 1, 1, $c->id);
			$invoice->braintree_id = $response->transaction->id;
			$invoice->save();
						
			$desc = 'NFC oznake + naljepnice/NFC tags + stickers';
						
			// Create new invoice item - just one in the case of subscription
			$it = new InvoiceItem();
			$it->create($invoice->id, $desc, $price, $currency, intval($request['numOfTags']));
						
			$it = new InvoiceItem();
			$desc = 'Poštarina/Shipping';
			$it->create($invoice->id, $desc, floatval($shipping), $currency, 1);
						
			$invoice->totalNet = $total;
			$invoice->totalWVat = $total + ($total * ($user->taxPercentage()/100));
			$invoice->card_brand = $user->card_brand;
			$invoice->card_last_four = $user->card_last_four;
			$invoice->save();
			
			// Create an order in the database for tag_orders table
			
			$tagOrder = new TagOrder();
			if(!$tagOrder->createOrder($user->id, $user->address, $user->zip, $user->city, $user->getCountry->value, intval($request['numOfTags']), 1, 0, null, $user->first_name . ' ' . $user->last_name, $invoice->id)) {
				return Redirect::back()->withErrors(['message' => Lang::get('dashboardBilling.failedToPlaceOrderButPaid')]);
			}
			
			
						
						
			$pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('emails.bill', ['invoice' => $invoice]);
			$pdf->setOption('footer-html', view('emails.footer'));
			$pdf->setPaper('a4');
			
			
			$date = \Carbon\Carbon::now();
			$result = $date->format('Y-m-d H-i-s');
			$filename = $result . '-' . $invoice->id  . '.pdf';
						
			Storage::disk('invoices')->put($filename, $pdf->output());
						
			Mail::send('emails.invoiceNotificationTagsOrder', ['user' => $user], function($message) use($user, $pdf)
			{
				$message->from('no-reply@taptoscan.com', 'TapToScan.com');

				$message->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Your order has been received');

				$message->attachData($pdf->output(), "invoice.pdf");
			});
				
			Session::flash('alert-success', 'Your order has been successfuly placed and your credit card has been charged. You will receive an email with the receipt soon and will be notified once the order has been shiped to your address.' );
			return Redirect::route('dashboard.ordertagsHistory', App::getLocale());
		}
		return Redirect::back()->withErrors(['message' => Lang::get('dashboardBilling.chargeFail')]);
		
		
    }
	
	public function ordertagsHistory() {
		
		$orders = Auth::user()->tagOrders()->where('paid', 1)->orderBy('id', 'desc')->paginate(10);
		return view('dashboard.ordertagsHistory')->with('orders', $orders);
	}
	
	/**
		* Show user's settings
		*
		* @return view('dashboard.settings');
	*/
	public function settings()
    {
        return view('dashboard.settings');
    }
	
	public function settingsPanel2()
    {
        return view('dashboard.settingsPanel2');
    }
	
	/**
		* Show help documentation
		*
		* @return view('dashboard.help');
	*/
	public function help()
    {
        return view('dashboard.help');
    }
	
	/**
		* Method that is called by an AJAX post request once the user marks a certain order as served
		*
		* @return boolean $order->updateStatus($id, Auth::id());
	*/
	public function orderServed(Request $data)
    {
		if ($data->isMethod('post')){
			$id = $data['id'];
			$order = new Order();
			return $order->updateStatus($id, Auth::id());
        }
    }
}
