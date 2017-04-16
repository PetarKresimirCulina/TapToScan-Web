@if(Auth::user()->email_verified == 0)
	<div class="alert alert-warning text-center">
		<p>@lang('register.verifyEmail') {{ Auth::user()->email }}. 
		<a href="{{ route('dashboard.resendVerification', App::getLocale()) }}">@lang('register.resendVerification')</a></p>
	</div>
@endif