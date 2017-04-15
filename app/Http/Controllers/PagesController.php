<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Countries;
use Illuminate\Support\Facades\Input;
use App;

class PagesController extends Controller
{
	public function indexEmpty()
    {
		if(\Request::path() == '/')
		{
			$locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			if(!array_key_exists($locale, config('app.locales'))) {
				$locale = 'en';
			}
			App::setLocale($locale);
			return redirect($locale . '/');
		}
		return view('pages.index');
    }
	
    public function index()
    {
        return view('pages.index');
    }
	
	public function about()
    {
        return view('pages.about');
    }
	
	public function contact()
    {
        return view('pages.contact');
    }
	
	public function business()
    {
        return view('pages.business');
    }
	
	public function features()
    {
        return view('pages.features');
    }
	
	public function terms()
    {
        return view('pages.terms');
    }
	
	public function privacy()
    {
        return view('pages.privacy');
    }
}
