<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Mail;
use Session;
use Validator;
use Redirect;
use Lang;
use App;

/**
     * MailController
	* Used to handle email sending from forms and certain user visible parts of the app
     */
class MailController extends Controller
{
	/**
     * postContact
	* Sends a contact us email (from a form on taptoscan.com/contact
	* @param Request $request
	* @return redirect
     */
    public function postContact(Request $request)
	{
		$rules = ['email' => 'required|email',
			'message' => 'min:10',
			'name' => 'min:3'];
			
		$validator = Validator::make($request->all(), $rules);
		
		if ($validator->fails())
		{
			$messages = $validator->messages();
			Session::flash('fail', $messages);
			return Redirect::back()->withErrors($validator->messages());;
			// The given data did not pass validation
		}
		
		$data = array(
			'email' => $request->email,
			'name' => $request->name,
			'bodyMessage' => $request->message,
			'subject' => 'Contact request from ' . $request->name . ' (' . $request->email . ')'
		);
		
		Mail::send('emails.contact', $data, function($message) use ($data){
			$message->from('contact@taptoscan.com');
			$message->replyTo($data['email'], $data['name']);
			$message->to('contact@taptoscan.com');
			$message->subject($data['subject']);
		});

		Session::flash('success', Lang::get('pages/contactus.success'));
		
		return redirect()->route('page.contact', App::getLocale());
	}
	
}
