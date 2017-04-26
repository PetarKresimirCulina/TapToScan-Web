<div class="flash-message">	
	
	@if(Auth::user()->tags()->count() < 1)
		<p class="alert alert-info text-center">@lang('actions.orderTags') <a href="{{ route('dashboard.ordertags', App::getLocale()) }}">@lang('navbar.order')</a> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	@endif
	
	@foreach (['danger', 'warning', 'success', 'info', ''] as $msg)
		@if(Session::has('alert-' . $msg))
			<p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	
	@foreach($errors->all() as $message)
		<p class="alert alert-danger text-center">{{ $message }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	@endforeach 
</div>