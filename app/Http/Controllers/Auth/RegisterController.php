<?php
namespace App\Http\Controllers\Auth;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
// custom
use App\Countries;
use App;
use App\Notifications\SendEmailVerification;
use App\Confirmation;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$this->redirectTo = route('dashboard.home');
        $this->middleware('guest');
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
			'country' => 'required|max:2|',
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'email' => $data['email'],
			'country' => $data['country'],
            'password' => bcrypt($data['password']),
        ]);
		$conf = new Confirmation();
		$conf->code = str_random(40);
		$user->confirmation()->save($conf);
		$user->notify(new SendEmailVerification($conf->code));
		return $user;
    }
	
	public function redirectTo() {
		return route('dashboard.verify', App::getLocale());
	}
	
	// custom functions
	public function showRegistrationForm()
	{
		$country_codes = Countries::getCodes();
		return view('auth.register')->with('codes', $country_codes);
	}
	
	public function sendVerificationEmail()
	{
		
	}
}