<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Laravel\Cashier\Billable;


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
	
	public function changePlan($plan) {
		$this->plan_id  = $plan;
		return $this->save();
	}
	
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
