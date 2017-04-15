<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

/**
     * PusherAuth
	* Used by Pusher client side API
     */
class PusherAuth extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	
	/**
     * auth
	* Called by the Pusher's client side code to authenticate the user for a private channel
	* @return echo
     */
    public function auth()
	{
		if (Auth::check()) {
			$pusher = new \Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'));
			echo $pusher->socket_auth(request()->input('channel_name'), request()->input('socket_id'));
			return;
		}else {
			header('', true, 403);
			echo "Forbidden";
			return;
		}
	}
}
