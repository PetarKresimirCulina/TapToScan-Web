<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use App\Order;
use App\Tag;
use App\Plan;
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
		if(Auth::user()->blocked == 0) {
			if(!Auth::user()->isUserSetup())  {
				return view('auth.setup')->with('plans', $plans);
			}
			return redirect()->route('dashboard.home', App::getLocale());
		}
		return redirect()->route('dashboard.billing', App::getLocale());
	}
	
	public function setupUser(Request $request)
	{
		if ($request->isMethod('post')){
			
			$rules = ['first_name' => 'required|min:2',
					'last_name' => 'required|min:2',
					'name' => 'required|min:2',
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
			if(Auth::user()->blocked == 1)
			{
				Session::flash('danger', __('dashboardBilling.userBlocked') );
				return Redirect::back();
			}
			if(Auth::user()->setupUser($request)) {
				return redirect()->route('user.setup.plan', App::getLocale());
			}
		}
	}
	
	public function displayUserSetupPlans() {
		if(Auth::user()->blocked == 0) {
			if(!Auth::user()->isUserSetup()) {
				$plans = Plan::where('display', 1)->get();
				return view('auth.setupPlan')->with('plans', $plans);
			}
			return redirect()->route('dashboard.home', App::getLocale());
		}
		return redirect()->route('dashboard.billing', App::getLocale());
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
				Session::flash('success', 'User information has been updated.' );
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
		$invoices = Auth::user()->invoicesIncludingPending();
        return view('dashboard.billingHistory')->with('invoices', $invoices);
    }
	
	public function billingInvoice(Request $request)
    {
		if ($request->isMethod('post')){
			$invoiceId = $request['invoiceId'];

			//$pdf = App::make('snappy.pdf');
			
			$pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('emails.pdf', ['data' => $request]);
			

			return $pdf->download($invoiceId . '.pdf');
		}
    }
	
	public function billingChangePlanDisplayAll()
    {
		if(Auth::user()->blocked == 0) {
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
        return view('dashboard.ordertags');
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
