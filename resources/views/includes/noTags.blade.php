<div class="flash-message">	
	
	@if(Auth::check())
		@if(Auth::user()->tags()->count() < 1)
		<p class="alert alert-info text-center">@lang('actions.orderTags') <a href="{{ route('dashboard.ordertags', App::getLocale()) }}">@lang('navbar.order')</a> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endif
</div>