<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Laravel\Cashier\Billable;
use Illuminate\Http\Request;
use App\Plan;
use Braintree_Customer;
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
		if($this->plan_id == null) {
			return false;
		}
		return true;
	}
	
	public static function create($request)
	{
		$user = new User;
		
		$user->email = $request['email'];
		$user->password = $request['password'];
		$user->country = $request['country'];
		$user->braintree_id = $user->createBraintreeUser();
		
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
			'firstName' => 'Saurabh',
			'lastName' => 'Sharma',
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
		$this->newSubscription('main', strval($plan->id))->trialDays(30)->create();
		$this->trial_ends_at = Carbon::now()->addDays(30);

		$this->plan_id  = $plan->id;
		return $this->save();
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
