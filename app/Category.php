<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
     * Category
	* Model that controls user's categories.
     */
class Category extends Model
{
	/**
     * icon
	* Gets the icon of the category
	* @return Icon
     */
    public function icon()
    {
        return $this->hasOne('App\Icon', 'id', 'icon_id');
    }
	
	public function getIconRes()
    {
        return $this->icon->icon_res;
    }
	/**
     * products
	* Gets the products of the category
	* @return Product
     */
	public function products()
    {
        return $this->hasMany('App\Product')->orderBy('name');
    }
	
	/**
     * deleteCategory
	* Deletes a certain user's category
	* @params int $id, int $user
	* @return boolean on fail or Order on success
     */
	public function deleteCategory($id, $user)
    {
        $cat = $this->where('id', $id)->where('user', $user)->first();
		if($cat)
		{
			$cat->products()->delete();
			$cat->delete();
			return true;
		}
		return false;
    }
	
	/**
     * add
	* Adds a new category for a user
	* @params String $name, int $icon, int $user
	* @return boolean
     */
	public function add($name, $icon, $user)
	{
		$this->name = $name;
		$this->icon_id = $icon;
		$this->user = $user;
		return $this->save();
	}
	
	/**
     * edit
	* Edits user's category
	* @params int $id, String $name, int $icon, int $user
	* @return boolean
     */
	public function edit($id, $name, $icon, $user)
    {
        $cat = $this->where('id', $id)->where('user', $user)->first();
		if($cat)
		{
			$cat->name = $name;
			$cat->icon_id = $icon;
			$cat->save();
			return true;
		}
		return false;
    }
}