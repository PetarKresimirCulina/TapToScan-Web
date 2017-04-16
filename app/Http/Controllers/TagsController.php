<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Tag;
use App\User;
use Auth;
use Carbon\Carbon;
use Session;
use Validator;
use Redirect;
use Lang;
/**
     * TagsController
	* Controls the NFC tags (tables)
     */
class TagsController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	/**
     * tablesAdd
	* Called when the user adds a new table (NFC tag)
	* @param Request $data ['tag' => 'required|min:8|max:8', 'name' => 'required|min:3|max:60', 'status' => 'numeric|between:0,1']
	* @return redirect
     */
    public function tablesAdd(Request $data)
    {
		if ($data->isMethod('post')){
			
			
			$rules = ['tag' => 'required|min:8|max:8',
			'name' => 'required|min:3|max:60',
			'status' => 'numeric|between:0,1'];
			
			
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
				$name = $data['name'];
				$active = $data['status'];
				
				$tag = new Tag();
				if($tag->add($id, $name, $active, Auth::id()))
				{
					return Redirect::back();
				}
				 return Redirect::back()->withErrors(array('message' => Lang::get('dashboardTables.errorInvalidTag')));
			}
        }
        return Redirect::back()->withErrors(array('message' => Lang::get('dashboardTables.errorInvalidRequest')));
    }
	
	/**
     * tablesChangeStatus
	* Called by AJAX POST request, changes the status of a table
	* @param Request $data ['tag' => 'required|min:8|max:8', 'status' => 'numeric|between:0,1'];
	* @return String
     */
	public function tablesChangeStatus(Request $data)
    {
		$rules = ['tag' => 'required|min:8|max:8',
			'status' => 'numeric|between:0,1'];
			
		$validator = Validator::make($data->all(), $rules);
			
		if ($validator->fails())
		{
			$messages = $validator->messages();
			return 'Validation failed';
		}
		else
		{
			$id = $data['tag'];
			$active = $data['status'];
			$tag = Tag::find($id);
			if($tag->userData->plan->tags_limit > $tag->userData->tagsActive->count() || $active == 0) {
				if($tag->changeStatus($tag, $active, Auth::id()))
				{
					return 'Success';
				}
			} else {
				return 'Tag limit reached';
			}
			
		}
		return 'Failed to modify the table. Please refresh the page.';
	}
	
	/**
     * tablesDelete
	* Called by a POST request once a user clicks on the delete button
	* @param Request $data ['tag' => 'required|min:8|max:8'];
	* @return redirect
     */
	public function tablesDelete(Request $data)
    {
		$rules = ['tag' => 'required|min:8|max:8'];
			
		$validator = Validator::make($data->all(), $rules);
			
		if ($validator->fails())
		{
			$messages = $validator->messages();
			return Redirect::back()->withErrors($validator->messages());
		}
		else
		{
			$id = $data['tag'];
			$tag = new Tag();
			if($tag->deleteTag($id, Auth::id()))
			{
				return Redirect::back();
			}
		}
		return Redirect::back()->withErrors(array('message' => Lang::get('dashboardTables.errorFailedDelete')));
	}
	
	/**
     * tablesEdit
	* Edits a table data once called by a PSOT request from a form
	* @param Request $data ['tag' => 'required|min:8|max:8', 'editTagName' => 'required|min:3|max:60'];
	* @return redirect
     */
	public function tablesEdit(Request $data)
    {
		$rules = ['tag' => 'required|min:8|max:8', 
				'editTagName' => 'required|min:3|max:60'];
			
		$validator = Validator::make($data->all(), $rules);
			
		if ($validator->fails())
		{
			$messages = $validator->messages();
			return Redirect::back()->withErrors($validator->messages());
		}
		else
		{
			$id = $data['tag'];
			$name = $data['editTagName'];
			$table = new Tag();
			if($table->editTag( $id, $name, Auth::id()))
			{
				return Redirect::back();
			}
			return Redirect::back()->withErrors(array(Lang::get('dashboardTables.errorFailedUpdate' . ' ' . $name .'.'))); 
		}
		return Redirect::back()->withErrors(array('message' => Lang::get('dashboard.errorFailedEdit')));
	}
	
	/**
     * tables
	* Called from routes/web.php route, lists all tables for a current user. Uses Auth::id() to get the user
	* @return redirect
     */
	public function tables()
    {
		$tags = new Tag();
        return view('dashboard.tables')->with('tags', $tags->userTags(Auth::id()));
    }
}
