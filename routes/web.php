<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function()
	{
		return View::make('emails/bill');
	});

Route::group(['prefix' => '{lang?}', 'middleware' => 'localize'], function () {
	/* ======================================================
	   Public routes
	   
	*/
	Route::get('/', 'PagesController@indexEmpty')->name('page.indexE');
	Route::get('/index', 'PagesController@index')->name('page.index');
	Route::get('/about', 'PagesController@about')->name('page.about');
	Route::get('/contact', 'PagesController@contact')->name('page.contact');
	Route::post('/contact', 'MailController@postContact')->name('contact.send');
	Route::get('/business', 'PagesController@business')->name('page.business');
	Route::get('/features', 'PagesController@features')->name('page.features');
	
	/* Footer routes */
	Route::get('/terms', 'PagesController@terms')->name('page.terms');;
	Route::get('/privacy', 'PagesController@privacy')->name('page.privacy');;
	/* auth routes */
	Auth::routes();
	
	/* ======================================================
	   Private routes
	   Check if email and data are verified
	   
	*/
	Route::group(['middleware' => ['emailVerify', 'userSetup']], function () {
		/* home */
		Route::get('/home', 'HomeController@index')->name('dashboard.home');;
		Route::post('/home/served', 'HomeController@orderServed')->name('home.served');
		
		/* history */
		Route::get('/history', 'HomeController@history')->name('dashboard.history');;
		
		/* tables */
		Route::get('/tables', 'TagsController@tables')->name('dashboard.tables');;
		Route::post('/tables/add', 'TagsController@tablesAdd')->name('tags.add');
		Route::post('/tables/status', 'TagsController@tablesChangeStatus')->name('tags.changeStatus');
		Route::post('/tables/delete', 'TagsController@tablesDelete')->name('tags.deleteTable');
		Route::post('/tables/edit', 'TagsController@tablesEdit')->name('tags.edit');

		/* categories */
		Route::get('/categories', 'ProductsController@categories')->name('dashboard.categories');;
		Route::post('/categories/delete', 'ProductsController@deleteCategory')->name('category.delete');
		Route::post('/categories/add', 'ProductsController@addCategory')->name('category.add');
		Route::post('/categories/edit', 'ProductsController@editCategory')->name('category.edit');

		/* categories - products */
		Route::get('/categories/view/{catID}', 'ProductsController@products')->name('products.view');;
		Route::post('/categories/products/add', 'ProductsController@addProduct')->name('products.add');
		Route::post('/categories/products/edit', 'ProductsController@editProduct')->name('products.edit');
		Route::post('/categories/products/delete', 'ProductsController@deleteProduct')->name('products.delete');

		/* billing */
		Route::get('/billing', 'HomeController@billing')->name('dashboard.billing');
		Route::get('/billing/invoices', 'HomeController@billingHistory')->name('dashboard.billing.history');
		Route::get('/billing/plans', 'HomeController@billingChangePlanDisplayAll')->name('dashboard.billing.changePlan');
		Route::post('/billing/plans/change', 'HomeController@billingChangePlan')->name('dashboard.billing.changePlanRequest');
		/* retry payment in subscription */
		Route::get('/billing/plans/charge', 'PaymentsController@billingRetryCharge')->name('dashboard.billing.retryCharge');
		/* invoices */
		Route::post('/billing/invoice', 'HomeController@billingInvoice')->name('dashboard.billing.invoice');

		/* ordertags */
		Route::get('/ordertags', 'HomeController@ordertags')->name('dashboard.ordertags');

		/* settings */
		Route::get('/settings', 'HomeController@settings')->name('dashboard.settings');
		Route::get('/settings/password', 'HomeController@settingsPanel2')->name('dashboard.settingsPanel2');

		/* help */
		Route::get('/help', 'HomeController@help')->name('dashboard.help');
		
		/* user settings */
		Route::post('/settings/information/update', 'HomeController@updateUserInformation')->name('dashboard.updateUserInformation');
		Route::post('/settings/password/update', 'HomeController@updatePassword')->name('dashboard.updatePassword');
		
	});
	
	
	/* email verification */
	Route::get('/user/verify', 'HomeController@displayEmailVerification')->name('dashboard.verify');
	Route::get('/user/verify/resend', 'HomeController@resendVerification')->name('dashboard.resendVerification');
	Route::get('/user/verify/update/{token}', 'HomeController@verifyEmail')->name('dashboard.verifyEmail');
	
	/* user setup on first launch */
	Route::get('/user/setup', 'HomeController@displayUserSetup')->name('user.displaySetup')->middleware('userBlockedOrSetup');
	Route::post('/user/setup/update', 'HomeController@setupUser')->name('user.setup.update')->middleware('userBlockedOrSetup');
	Route::get('/user/setup/plan', 'HomeController@displayUserSetupPlans')->name('user.setup.plan')->middleware('userBlockedOrSetup');
	
	/* payment routes */
	Route::post('/checkout/subscribe', 'PaymentsController@subscribe')->name('subscription.subscribe');
	Route::post('/checkout/changePlan', 'PaymentsController@changeSubscriptionPlan')->name('subscription.change');
	Route::post('/checkout/changePaymentMethod', 'PaymentsController@updateCreditCard')->name('subscription.editCC');
	Route::get('/braintree/token', 'PaymentsController@token')->name('braintree.generateToken');;
	
	/* logout */
	Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('dashboard.logout');
	
});

	Route::post('/pusher/auth', 'PusherAuth@auth')->name('pusher.auth');
	
	
