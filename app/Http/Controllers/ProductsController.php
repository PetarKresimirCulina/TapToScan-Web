<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Icon;
use App\Product;
use App\Currency;
use Redirect;
use Session;
use Validator;

	/**
     * ProductsController
	* Controls products
     */
class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	/**
     * categories
	* Lists user's categories. Uses Auth::id() for user ID.
	* @return view
     */
	public function categories()
	{
		$cat = new Category();
		$cat = Category::with('Icon')->where('user', Auth::id())->orderBy('name')->paginate(10);
		$icons = Icon::all();
        return view('dashboard.categories')->with('categories', $cat)->with('icons', $icons);
	}
	
	/**
     * deleteCategory
	* Deletes a current category
	* @param Request $data ['id' => 'required|numeric'];
	* @return redirect
     */
	public function deleteCategory(Request $data)
	{
		if ($data->isMethod('post')){
			
			$rules = ['id' => 'required|numeric'];
				
			
			$validator = Validator::make($data->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			
			$id = $data['id'];
			$cat = new Category();
			if($cat->deleteCategory($id, Auth::id()))
			{
				return Redirect::back();
			}
			return Redirect::back()->withErrors(['message' => 'Failed to delete the category.']);
		}
		return $this->categories();
	}
	
	/**
     * addCategory
	* Adds a new category
	* @param Request $data ['name' => 'required|min:3|max:60', 'icon' => 'required|numeric'];
	* @return redirect
     */
	public function addCategory(Request $data)
	{
		if ($data->isMethod('post')){
			
			$rules = ['name' => 'required|min:3|max:60',
					'icon' => 'required|numeric'];
				
			
			$validator = Validator::make($data->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			
			$name = $data['name'];
			$icon = $data['icon'];
			$cat = new Category();
			if($cat->add($name, $icon, Auth::id()))
			{
				return Redirect::back();
			}
			return Redirect::back()->withErrors(['message' => 'Failed to add the category.']);
		}
		return $this->categories();
	}
	
	/**
     * editCategory
	* Edits a selected category
	* @param Request $data ['id' => 'required|numeric', 'name' => 'required|min:3|max:60', 'icon' => 'required|numeric'];
	* @return redirect
     */
	public function editCategory(Request $data)
	{
		if ($data->isMethod('post')){
			
			$rules = ['id' => 'required|numeric',
					'name' => 'required|min:3|max:60',
					'icon' => 'required|numeric'];
				
			
			$validator = Validator::make($data->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			
			$id = $data['id'];
			$name = $data['name'];
			$icon = $data['icon'];
			$cat = new Category();
			if($cat->edit($id, $name, $icon, Auth::id()))
			{
				return Redirect::back();
			}
			return Redirect::back()->withErrors(['message' => 'Failed to edit the category.']);
		}
		return $this->categories();
	}
	
	/**
     * products
	* Lists the products of a certain category
	* @param int $lang int $cat
	* @return view
     */
	public function products($lang, $cat)
	{
		$category = new Category();
		$category = Category::where('id', $cat)->where('user', Auth::id())->first();
		if($category)
		{
			$products = $category->products()->orderBy('name')->get();
			//$category = Category::with('Products', 'Products.Currency')->where('id',$cat)->where('user', Auth::id())->first();
			$currencies = Currency::all();
			return view('dashboard.products')->with('category', $category)->with('products', $products)->with('currencies', $currencies);
		}
		return $this->categories();
		
	}
	
	/**
     * addProduct
	* Adds a product to category
	* @param Request $data ['name' => 'required|min:3|max:60', 'price' => 'required|numeric|max:2147483646|min:0', 'currency' => 'required|numeric'];
	* @return redirect
     */
	public function addProduct(Request $data)
	{
		if ($data->isMethod('post')){
			
			$rules = ['name' => 'required|min:3|max:60',
					'price' => 'required|numeric|max:2147483646|min:0',
					'currency' => 'required|numeric'];
				
			
			$validator = Validator::make($data->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			
			$name = $data['name'];
			$price = $data['price'];
			$currency = $data['currency'];
			$cat = $data['cat'];
			
			$product = new Product();
			if($product->add($name, $price, $currency, $cat, Auth::id()))
			{
				return Redirect::back();
			}
			return Redirect::back()->withErrors(['message' => 'Failed to add a product.']);
		}
		return $this->categories();
	}
	
	/**
     * deleteProduct
	* Deletes a product from a category
	* @param Request $data ['id' => 'required|numeric', 'cat' => 'required|numeric'];
	* @return redirect
     */
	public function deleteProduct(Request $data)
	{
		if ($data->isMethod('post')){
			
			$rules = ['id' => 'required|numeric',
					'cat' => 'required|numeric'];
				
			
			$validator = Validator::make($data->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			
			$id = $data['id'];
			$cat = $data['cat'];
			
			$product = new Product();
			if($product->deleteProduct($id, $cat, Auth::id()))
			{
				return Redirect::back();
			}
			return Redirect::back()->withErrors(['message' => 'Failed to delete the product.']);
		}
		return $this->categories();
	}
	
	/**
     * editProduct
	* Edots a certain product in a category
	* @param Request $data ['cat' => 'required|numeric', 'id' => 'required|numeric', 'name' => 'required|min:3|max:60', 'price' => 'required|numeric|max:2147483646|min:0', 'currency' => 'required|numeric'];
	* @return redirect
     */
	public function editProduct(Request $data)
	{
		if ($data->isMethod('post')){
			
			$rules = ['cat' => 'required|numeric',
					'id' => 'required|numeric',
					'name' => 'required|min:3|max:60',
					'price' => 'required|numeric|max:2147483646|min:0',
					'currency' => 'required|numeric'];
				
			
			$validator = Validator::make($data->all(), $rules);
			
			if ($validator->fails())
			{
				$messages = $validator->messages();
				Session::flash('fail', $messages);
				return Redirect::back()->withErrors($validator->messages());
			}
			
			$id = $data['id'];
			$cat = $data['cat'];
			$name = $data['name'];
			$price = $data['price'];
			$currency = $data['currency'];
			
			$product = new Product();
			if($product->edit($id, $cat, $name, $price, $currency, Auth::id()))
			{
				return Redirect::back();
			}
			return Redirect::back()->withErrors(['message' => 'Failed to edit the product.']);
		}
		return $this->categories();
	}
}
