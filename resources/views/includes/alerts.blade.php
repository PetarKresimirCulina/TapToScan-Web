<div class="flash-message">	
	@foreach (['danger', 'warning', 'success', 'info', ''] as $msg)
		@if(Session::has('alert-' . $msg))
			<p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
		@endif
	@endforeach
	
	@foreach($errors->all() as $message)
		<p class="alert alert-danger text-center">{{ $message }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
	@endforeach 
</div>