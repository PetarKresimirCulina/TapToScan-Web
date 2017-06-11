<div class="hidden-xs col-sm-3 col-md-2 sidebar">
	<ul class="nav margin-4 text-capitalize">
	
		@if(Auth::user()->admin == 1)
			<p class="small text-center">Admin</p>
			<li><a {{{ (Request::is('*/tags') ? 'class=active' : '') }}} href="{{ route('dashboard.tagsManagement', App::getLocale()) }}"><i class="material-icons">storage</i> @lang('navbar.tagsManagement')</a></li>
			<li><a {{{ (Request::is('*/users') ? 'class=active' : '') }}} href="{{ route('dashboard.usersManagement', App::getLocale()) }}"><i class="material-icons">people</i> @lang('navbar.usersManagement')</a></li>
			<li><a {{{ (Request::is('*/tagorders') ? 'class=active' : '') }}} href="{{ route('dashboard.tagOrdersManagement', App::getLocale()) }}"><i class="material-icons">shopping_cart</i> @lang('navbar.tagOrders')				
				@php $orders = \App\TagOrder::where('shipped', 0)->where('paid', 1)->count() @endphp
				@if($orders > 0)
					<span class="badge notification"> {{ $orders }} </span>
				@endif
			</a></li>
			
			<hr class="separator">
		@endif
		<li><a  {{{ (Request::is('*/home') ? 'class=active' : '') }}} href="{{ route('dashboard.home', App::getLocale()) }}"><i class="material-icons">receipt</i> @lang('navbar.orders') 
			@if(Auth::user()->orders()->where('status', 0)->count() > 0)
				<span class="badge notification"> {{ Auth::user()->orders()->where('status', 0)->count() }} </span>
			@else
				<span style="display: none;" class="badge notification"> {{ Auth::user()->orders()->where('status', 0)->count() }} </span>
			@endif
		</a></li>
		
		
		<li><a {{{ (Request::is('*/history*') && !Request::is('*ordertags*') ? 'class=active' : '') }}} href="{{ route('dashboard.history', App::getLocale()) }}"><i class="material-icons">history</i> @lang('navbar.history')</a></li>
		<li><a {{{ (Request::is('*/tables*') ? 'class=active' : '') }}} href="{{ route('dashboard.tables', App::getLocale()) }}"><i class="material-icons">event_seat</i> @lang('navbar.tables')</a></li>
		<li><a {{{ (Request::is('*/categories*') ? 'class=active' : '') }}} href="{{ route('dashboard.categories', App::getLocale()) }}"><i class="material-icons">free_breakfast</i> @lang('navbar.products')</a></li>
		<li><a {{{ (Request::is('*/billing*') ? 'class=active' : '') }}} href="{{ route('dashboard.billing', App::getLocale()) }}"><i class="material-icons">monetization_on</i> @lang('navbar.billing')</a></li>
		<li><a {{{ (Request::is('*/ordertags*') ? 'class=active' : '') }}} href="{{ route('dashboard.ordertags', App::getLocale()) }}"><i class="material-icons">nfc</i> @lang('navbar.order')</a></li>
		<li><a {{{ (Request::is('*/settings*') ? 'class=active' : '') }}} href="{{ route('dashboard.settings', App::getLocale()) }}"><i class="material-icons">tune</i> @lang('navbar.settings')</a></li>
		<li><a {{{ (Request::is('*/help') ? 'class=active' : '') }}} href="{{ route('dashboard.help', App::getLocale()) }}"><i class="material-icons">help</i> @lang('navbar.help')</a></li>
	</ul>

</div>