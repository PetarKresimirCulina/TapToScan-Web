<div class="flash-message">
	@if(Auth::user()->blocked == 1)
		<p class="alert alert-danger text-center">@lang('dashboardBilling.userBlocked') <a href="{{ route('dashboard.billing', App::getLocale()) }}">@lang('dashboardBilling.title')</a></p>
	@endif
</div>