<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App;
use App\User;
use App\Plan;
use Braintree_ClientToken;
use Validator;
use Session;
use Lang;
use Redirect;


class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	public function changeSubscriptionPlan(Request $request)
    {
		if ($request->isMethod('post')){
			
			$rules = ['planID' => 'numeric'];
				
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			if(Auth::user()->changePlan($request)) {
				Session::flash('alert-success', Lang::get('dashboardBilling.planChangeSuccess'));
				return redirect()->route('dashboard.billing', App::getLocale());
			}
		}
		
        return redirect()->route('dashboard.billing', App::getLocale());
    }
	
	public function subscribe(Request $request)
    {
		if ($request->isMethod('post')){
			
			$rules = ['planID' => 'numeric'];
				
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			try {
				if(Auth::user()->blocked == 1)
				{
					Session::flash('danger', __('dashboardBilling.userBlocked') );
					return Redirect::back();
				}
				Auth::user()->updateCreditCard($request);
				Auth::user()->createFirstSubscription($request);
				return redirect()->route('dashboard.home', App::getLocale());
			}
			catch(\Exception $e) {
				if(strpos($e, 'Credit card type is not accepted by this merchant account') !== false) {
					Session::flash('alert-danger', Lang::get('dashboardBilling.creditCardErrorNotAccepted'));
				}
				else {
					Session::flash('alert-danger', Lang::get('dashboardBilling.creditCardError'));
				}
				return Redirect::back();
			}
		}
		
       return redirect()->route('dashboard.billing', App::getLocale());
    }
	
	public function updateCreditCard(Request $request)
    {
		if ($request->isMethod('post')){
			try {
				$res = Auth::user()->updateCreditCard($request);
				Session::flash('alert-success', Lang::get('dashboardBilling.creditCardChanged'));
				return redirect()->route('dashboard.billing', App::getLocale());
			}
			catch(\Exception $e) {
				if(strpos($e, 'Credit card type is not accepted by this merchant account') !== false) {
					Session::flash('alert-danger', Lang::get('dashboardBilling.creditCardErrorNotAccepted'));
				}
				else {
					Session::flash('alert-danger', Lang::get('dashboardBilling.creditCardError'));
				}
				return redirect()->route('dashboard.billing', App::getLocale());
			}
			
			
			/*if() {
				Session::flash('alert-success', Lang::get('dashboardBilling.creditCardChanged'));
				return redirect()->route('dashboard.billing', App::getLocale());
			}*/
		}
		
        return redirect()->route('dashboard.billing', App::getLocale());
    }
	
	public function token()
    {
        return response()->json([
            'data' => [
                'token' => Braintree_ClientToken::generate(),
            ]
        ]);
    }

}
