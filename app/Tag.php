<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

	/**
     * Tag
	* Model that controls the NFC tags data (tables)
     */
class Tag extends Model
{
	public $incrementing = false;
	
	/**
     * userTags
	* Gets all tags of a certain user. Should be replaced with eloquent model in the future
	* @param int $userID
	* $return Tag Collection
     */
    public function userTags($userID)
	{
		 return $this->where('user', $userID)->where('deleted', 0)->orderBy('name')->paginate(10);
	}
	
	public function userData() //bilo je orders prije pa vrati ako se sve sjebe
	{
		return $this->belongsTo('App\User', 'user');
	}
	
	/**
     * add
	* Adds a new NFC tag (table) for the user. Check if the tag exists in the database and if it's not assigned to any user, it gets assigned to the current user.
	* @param int $id, String $name, int $active, int $user
	* $return boolean
     */
	public function add($id, $name, $active, $user)
	{
		
		$tag = $this->where('id', $id)->where('user', null)->first();
		
		if($tag)
		{
			$tag->name = $name;
			$tag->active = $active;
			$tag->user = $user;
			$tag->save();
			return true;
		}
		return false;
	}
	
	/**
     * changeStatus
	* Changes the tags status 0=inactive 1=active. Only active tags can be scanned by mobile phone aoo
	* @param int $id, int $active, int $user
	* $return boolean
     */
	public function changeStatus($tag, $active, $user)
	{
		
		if($tag->userData->id == $user)
		{			
			$tag->active = $active;
			$tag->save();
			return true;
		}
		return false;
	}
	
	/**
     * deleteTag
	* Deletes a tag that belongs to the user.
	* @param int $id, int $user
	* $return boolean
     */
	public function deleteTag($id, $user)
	{
		
		$tag = $this->where('id', $id)->where('user', $user)->first();

		if($tag)
		{
			$tag->deleted = 1;
			$tag->save();
			return true;
		}
		return false;
	}
	
	/**
     * editTag
	* Edits a certain tag that belongs to the user
	* @param int $id, int $name, int $user
	* $return boolean
     */
	public function editTag($id, $name, $user)
	{
		
		$tag = $this->where('id', $id)->where('user', $user)->first();
		
		

		if($tag)
		{
			$tag->name = $name;
			$tag->save();
			return true;
			
		}
		return $tag;
	}
	
	/**
     * getTableName
	* Gets a name column of a certain tag.
	* @param int $id
	* $return Tag->name
     */
	public function getTableName($id)
	{
		return $this->where('id', $id)->pluck('name')->first();
	}
}
