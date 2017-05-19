<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Order;
use App\User;
use Auth;
use Carbon\Carbon;
use Session;
use Validator;
use Redirect;
use Lang;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller
{
	public function tags() {
		
		$query = Tag::query();
		if(Input::has('serial')) {
			$query->where('id', Input::get('serial'));
		}
		if(Input::has('user')) {
			$user = User::where('email', Input::get('user'))->first();
			if($user) {
				$query->where('user', $user->id);
			} else {
				$query->where('user', 0);
			}
		}
		
		
		$tags = $query->where('deleted', 0)->paginate(10);
		return view('dashboard.tags')->with('tags', $tags);
	}
	
	public function users() {
		$query = User::query();
		if(Input::has('email')) {
			$query->where('email', Input::get('email'));
		}
		$users = $query->paginate(10);
		return view('dashboard.users')->with('users', $users);
	}
	
	public function tagsAdd(Request $data)
    {
		if ($data->isMethod('post')){
			
			
			$rules = ['tag' => 'required|min:8|max:8'];
			
			
			$validator = Validator::make($data->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			else
			{
				$id = strtoupper($data['tag']);
				
				$tag = new Tag();
				if($tag->addAdmin($id))
				{
					return Redirect::back();
				}
				 return Redirect::back()->withErrors(array('message' => Lang::get('dashboardTables.errorInvalidTag')));
			}
        }
        return Redirect::back()->withErrors(array('message' => Lang::get('dashboardTables.errorInvalidRequest')));
    }
	
	public function tagsBulkAdd(Request $data) {
		if ($data->isMethod('post')){
			if(Auth::user()->admin != 1) {
				return false;
			}
			
			$resultSet = array();
			
			for($i = 0; $i < intval($data['no']); $i++) {
				$notPresent = false;
				
				do {
					$alpha = substr(str_shuffle(str_repeat($x='ABCDEFGHIJKLMNPQRSTUVWXYZ', ceil(4/strlen($x)) )),1,4);
					$number = rand(1000, 9999);
					$serial = $alpha . $number;
					
					$tag = Tag::where('id', $serial)->first();
					if($tag) {
						$notPresent = false;
					} else {
						$notPresent = true;
					}
					
				} while(!$notPresent);
				$tag = new Tag();
				$tag->id = $serial;
				$tag->save();
				array_push($resultSet, $serial);
			}
			Session::flash('alert-success', Lang::get('dashboardTags.tagsGenerated'));

			return redirect()->back()->with('results', $resultSet);
		}
	}
	
	public function userBanChangeStatus(Request $data)
    {
		$rules = ['user' => 'required|numeric|min:1',
			'status' => 'numeric|between:0,1'];
			
		$validator = Validator::make($data->all(), $rules);
			
		if ($validator->fails())
		{
			$messages = $validator->messages();
			return 'Validation failed';
		}
		else
		{
			$id = $data['user'];
			$active = $data['status'];
			$user = User::find($id);
			if(Auth::user()->admin == 1 && $user)
			{
				if($user->banChangeStatus())
				{
					return 'Success';
				}
			}
		}
		return 'Failed to modify the user. Please refresh the page.';
	}
	public function userBlockChangeStatus(Request $data)
    {
		$rules = ['user' => 'required|numeric|min:1',
			'status' => 'numeric|between:0,1'];
			
		$validator = Validator::make($data->all(), $rules);
			
		if ($validator->fails())
		{
			$messages = $validator->messages();
			return 'Validation failed';
		}
		else
		{
			$id = $data['user'];
			$active = $data['status'];
			$user = User::find($id);
			if(Auth::user()->admin == 1 && $user)
			{
				if($user->blockChangeStatus())
				{
					return 'Success';
				}
			}
		}
		return 'Failed to modify the user. Please refresh the page.';
	}
	
	public function userDelete(Request $data){
		$rules = ['user' => 'required|numeric|min:1'];
			
		$validator = Validator::make($data->all(), $rules);
			
		if ($validator->fails())
		{
			$messages = $validator->messages();
			return 'Validation failed';
		}
		else
		{
			$id = $data['user'];
			$user = User::find($id);
			if(Auth::user()->admin == 1 && $user)
			{
				if($user->delete())
				{
					Session::flash('alert-success', Lang::get('dashboardUsers.userDeleted'));
					return redirect()->back();
				}
			}
		}
		return 'Failed to modify the user. Please refresh the page.';
	}

}
