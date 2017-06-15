
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand navbar-logo" href="{{ route('page.index', App::getLocale()) }}"></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="text-uppercase"><a href="{{ route('page.index', App::getLocale()) }}">@lang('navbar.home')</a></li>
			<li class="text-uppercase"><a href="{{ route('page.business', App::getLocale()) }}">@lang('navbar.forBarsAndPubs')</a></li>
            <!--<li class="text-uppercase"><a href="{{ route('page.features', App::getLocale()) }}">@lang('navbar.features')</a></li> -->
			<li class="text-uppercase"><a href="{{ route('page.about', App::getLocale()) }}">@lang('navbar.about')</a></li>
			<li class="text-uppercase"><a href="{{ route('page.contact', App::getLocale()) }}">@lang('navbar.contact')</a></li>
		</ul>
		
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
					<a href="#" class="dropdown-toggle text-uppercase" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> 
					
						@if(empty(App::getLocale()))
							{{ App::setLocale('en') }}
						@endif
						
						{{ App::getLocale() }}
					 
					<span class="caret"></span></a>
					<ul class="dropdown-menu">
						@foreach(config('app.locales') as $key => $locale)
							<li><a class="text-capitalize" href="{{ (Request::segment(2)) ? '/' . $key . str_replace(Request::segment(1), '', Request::path()) : '/' . $key . '/'}}"><span class="text-uppercase">{{ $key }} -</span> {{$locale}}</a></li>
						@endforeach
					</ul>
				</li>
			</ul>
		@if (Auth::check())
			<ul class="nav navbar-nav navbar-right hidden-xs">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="material-icons profile-icon">account_circle</i> {{ Auth::user()->email }} <span class="caret"></span></a>
					<ul class="dropdown-menu">
						@if(Auth::user()->admin == 1)
							<li class="text-uppercase"><a {{{ (Request::is('*/tags') ? 'class=active' : '') }}} href="{{ route('dashboard.tagsManagement', App::getLocale()) }}"><i class="material-icons">storage</i> @lang('navbar.tagsManagement')</a></li>
							<li class="text-uppercase"><a {{{ (Request::is('*/users') ? 'class=active' : '') }}} href="{{ route('dashboard.usersManagement', App::getLocale()) }}"><i class="material-icons">people</i> @lang('navbar.usersManagement')</a></li>		
							<li><a {{{ (Request::is('*/tagorders') ? 'class=active' : '') }}} href="{{ route('dashboard.tagOrdersManagement', App::getLocale()) }}"><i class="material-icons">shopping_cart</i> @lang('navbar.tagOrders')				
								@php $orders = \App\TagOrder::where('shipped', 0)->where('paid', 1)->count() @endphp
								@if($orders > 0)
									<span class="badge notification"> {{ $orders }} </span>
								@endif
							</a></li>
							<li role="separator" class="divider"></li>
						@endif
						<li class="text-uppercase"><a  {{{ (Request::is('*/home') ? 'class=active' : '') }}} href="{{ route('dashboard.home', App::getLocale()) }}"><i class="material-icons">receipt</i> @lang('navbar.orders')
							@if(Auth::user()->orders()->where('status', 0)->count() > 0)
								<span class="badge notification"> {{ Auth::user()->orders()->where('status', 0)->count() }} </span>
							@else
								<span style="display: none;" class="badge notification"> {{ Auth::user()->orders()->where('status', 0)->count() }} </span>
							@endif
						</a></li>
						<li class="text-uppercase"><a {{{ (Request::is('*/history*') && !Request::is('*ordertags*') ? 'class=active' : '') }}} href="{{ route('dashboard.history', App::getLocale()) }}"><i class="material-icons">history</i> @lang('navbar.history')</a></li>
						<li class="text-uppercase"><a {{{ (Request::is('*/tables*') ? 'class=active' : '') }}} href="{{ route('dashboard.tables', App::getLocale()) }}"><i class="material-icons">event_seat</i> @lang('navbar.tables')</a></li>
						<li class="text-uppercase"><a {{{ (Request::is('*/categories*') ? 'class=active' : '') }}} href="{{ route('dashboard.categories', App::getLocale()) }}"><i class="material-icons">free_breakfast</i> @lang('navbar.products')</a></li>
						<li class="text-uppercase"><a {{{ (Request::is('*/billing*') ? 'class=active' : '') }}} href="{{ route('dashboard.billing', App::getLocale()) }}"><i class="material-icons">monetization_on</i> @lang('navbar.billing')</a></li>
						<li class="text-uppercase"><a {{{ (Request::is('*/ordertags*') ? 'class=active' : '') }}} href="{{ route('dashboard.ordertags', App::getLocale()) }}"><i class="material-icons">nfc</i> @lang('navbar.order')</a></li>
						<li class="text-uppercase"><a {{{ (Request::is('*/settings*') ? 'class=active' : '') }}} href="{{ route('dashboard.settings', App::getLocale()) }}"><i class="material-icons">tune</i> @lang('navbar.settings')</a></li>
						<li class="text-uppercase"><a {{{ (Request::is('*/help') ? 'class=active' : '') }}} href="{{ route('dashboard.help', App::getLocale()) }}"><i class="material-icons">help</i> @lang('navbar.help')</a></li>
						<li role="separator" class="divider"></li>
						<li class="text-uppercase"><a href="{{ route('dashboard.logout', App::getLocale()) }}"><i class="material-icons">exit_to_app</i> {{ __('navbar.logout') }}</a></li>
					</ul>
				</li>
			</ul>
			
			<ul class="nav navbar-nav hidden-sm hidden-md hidden-lg">
				@if(Auth::user()->admin == 1)
					<li class="text-uppercase"><a {{{ (Request::is('*/tags') ? 'class=active' : '') }}} href="{{ route('dashboard.usersManagement', App::getLocale()) }}"><i class="material-icons">storage</i> @lang('navbar.tagsManagement')</a></li>
					<li class="text-uppercase"><a {{{ (Request::is('*/users') ? 'class=active' : '') }}} href="{{ route('dashboard.tagsManagement', App::getLocale()) }}"><i class="material-icons">people</i> @lang('navbar.usersManagement')</a></li>	
					<li><a {{{ (Request::is('*/tagorders') ? 'class=active' : '') }}} href="{{ route('dashboard.tagOrdersManagement', App::getLocale()) }}"><i class="material-icons">shopping_cart</i> @lang('navbar.tagOrders')				
						@php $orders = \App\TagOrder::where('shipped', 0)->where('paid', 1)->count() @endphp
						@if($orders > 0)
							<span class="badge notification"> {{ $orders }} </span>
						@endif
					</a></li>
					<li role="separator" class="divider"></li>
				@endif
				<li class="text-uppercase"><a  {{{ (Request::is('*/home') ? 'class=active' : '') }}} href="{{ route('dashboard.home', App::getLocale()) }}"><i class="material-icons">receipt</i> @lang('navbar.orders')
					@if(Auth::user()->orders()->where('status', 0)->count() > 0)
						<span class="badge notification"> {{ Auth::user()->orders()->where('status', 0)->count() }} </span>
					@else
						<span style="display: none;" class="badge notification"> {{ Auth::user()->orders()->where('status', 0)->count() }} </span>
					@endif
				</a></li>
				<li class="text-uppercase"><a {{{ (Request::is('*/history*') && !Request::is('*ordertags*') ? 'class=active' : '') }}} href="{{ route('dashboard.history', App::getLocale()) }}"><i class="material-icons">history</i> @lang('navbar.history')</a></li>
				<li class="text-uppercase"><a {{{ (Request::is('*/tables*') ? 'class=active' : '') }}} href="{{ route('dashboard.tables', App::getLocale()) }}"><i class="material-icons">event_seat</i> @lang('navbar.tables')</a></li>
				<li class="text-uppercase"><a {{{ (Request::is('*/categories*') ? 'class=active' : '') }}} href="{{ route('dashboard.categories', App::getLocale()) }}"><i class="material-icons">free_breakfast</i> @lang('navbar.products')</a></li>
				<li class="text-uppercase"><a {{{ (Request::is('*/billing*') ? 'class=active' : '') }}} href="{{ route('dashboard.billing', App::getLocale()) }}"><i class="material-icons">monetization_on</i> @lang('navbar.billing')</a></li>
				<li class="text-uppercase"><a {{{ (Request::is('*/ordertags*') ? 'class=active' : '') }}} href="{{ route('dashboard.ordertags', App::getLocale()) }}"><i class="material-icons">nfc</i> @lang('navbar.order')</a></li>
				<li class="text-uppercase"><a {{{ (Request::is('*/settings*') ? 'class=active' : '') }}} href="{{ route('dashboard.settings', App::getLocale()) }}"><i class="material-icons">tune</i> @lang('navbar.settings')</a></li>
				<li class="text-uppercase"><a {{{ (Request::is('*/help') ? 'class=active' : '') }}} href="{{ route('dashboard.help', App::getLocale()) }}"><i class="material-icons">help</i> @lang('navbar.help')</a></li>
				<li role="separator" class="divider"></li>
				<li class="text-uppercase"><a href="{{ route('dashboard.logout', App::getLocale()) }}"><i class="material-icons">exit_to_app</i> @lang('navbar.logout')</a></li>
			</ul>
			
			
			
		@else
			<ul class="nav navbar-nav navbar-right">
				<li class="text-uppercase"><a href="{{ route('login', App::getLocale()) }}">@lang('navbar.login')</a></li>
				<li class="text-uppercase"><p class="navbar-btn"><a href="{{ route('register', App::getLocale()) }}" class="btn btn-success btn-round" role="button">@lang('navbar.register')</a></p></li>
			</ul>
		@endif
		<!-- Single button -->

		
        </div><!--/.nav-collapse -->
      </div>
    </nav>