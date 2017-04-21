<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Laravel\Cashier\Billable;
use Illuminate\Http\Request;
use App\Plan;
use Braintree_Customer;
use Braintree_PaymentMethod;
use Carbon\Carbon;


/**
     * User
	* Model that controls user data
     */
class User extends Authenticatable
{
    use Notifiable;
	use Billable;
	
	 
	public $timestamps = false;
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password'
    ];
	
	/**
     * create
	* Creates a new user. Laravel default.
	* @param $request
	* @return User
     */
	
	public function confirmation()
	{
		return $this->hasOne('App\Confirmation');
	}
	
	public function plan()
	{
		return $this->belongsTo('App\Plan');
	}
	
	public function orders()
	{
		return $this->hasMany('App\Order', 'userID');
	}
	
	public function tags()
	{
		return $this->hasMany('App\Tag', 'user')->where('deleted', 0);
	}
	
	public function tagsActive()
	{
		return $this->hasMany('App\Tag', 'user')->where('deleted', 0)->where('active', 1);
	}
	
	public function isUserVerified() {
		if($this->email_verified == 1) {
			return true;
		}
		return false;
	}
	
	public function isUserSetup() {
		if($this->plan_id == null || $this->first_name == null || $this->last_name == null || $this->business_name == null || $this->address == null || $this->city == null || $this->zip == null) {
			return false;
		}
		return true;
	}
	
	// first time only - after email verification
	public function setupUser($request) {
		$this->first_name = $request['first_name'];
		$this->last_name = $request['last_name'];
		$this->business_name = $request['name'];
		$this->address = $request['address'];
		$this->city = $request['city'];
		$this->zip = $request['zip'];
		$this->braintree_id = $this->createBraintreeUser();
		return $this->save();
	}
	
	
	public static function create($request)
	{
		$user = new User;
		
		$user->email = $request['email'];
		$user->password = $request['password'];
		$user->country = $request['country'];
		
		if($user->save())
		{
			return $user;
		}
	}
	
	public function verified($token)
	{
		if($token == $this->confirmation->code)
		{
			$this->email_verified = 1;
			return $this->save();
		}
		return false;
	}
	
	public function updateInfo($name, $address, $city, $zip)
	{
		$this->business_name = $name;
		$this->address = $address;
		$this->city = $city;
		$this->zip = $zip;
		return $this->save();
	}
	
	public function updatePassword($pass)
	{
		$this->password = $pass;
		return $this->save();
	}
	
	// Braintree integration
	
	public function createBraintreeUser() {
		$result = Braintree_Customer::create(array(
			'firstName' => $this->first_name,
			'lastName' => $this->last_name,
			'email' => $this->email,
			'company' => $this->business_name,
		));
		if ($result->success) {
			return $result->customer->id;
		}
	}
	
	public function createFirstSubscription(Request $request) {
		$plan = Plan::findOrFail($request['planID']);
		  
		// subscribe the user
		// non-existing user
		// $this->newSubscription('main', strval($plan->id))->trialDays(30)->create($request['payment_method_nonce']);
			
		// existing user
		// add first payment method
		
		//if ($result->success) {
			// subscribe
			if($this->updateCreditCard($request)) {
				$this->newSubscription('main', strval($plan->id))->trialDays(30)->create();
				$this->trial_ends_at = Carbon::now()->addDays(30);

				$this->plan_id  = $plan->id;
				return $this->save();
			}
			return false;
	/*	}
		return false;*/
	}
	
	public function updateCreditCard(Request $request) {
		$this->updateCard($request['payment_method_nonce']);
		return true;
	}
	
	public function changePlan(Request $request) {
		// get the plan after submitting the form
		$plan = Plan::findOrFail($request['planID']);
		
		  
		// subscribe the user
		// non-existing user
		//$this->newSubscription('main', strval($plan->id))->trialDays(30)->create($request['payment_method_nonce']);
			
		// existing user
		$this->subscription('main')->swap(strval($plan->id));
		
		$this->plan_id  = $plan->id;
		return $this->save();
	}
	
	/* ################################################## */
	
	/**
     * sendPasswordResetNotification
	* Custom implementation for password reset email
	* @param $token
     */
	public function sendPasswordResetNotification($token)
    {
        // Your your own implementation.
        $this->notify(new ResetPasswordNotification($token));
    }
	
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	
}
