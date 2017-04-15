@if(Auth::user()->email_verified == 0)
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
							<div class="alert alert-danger" role="alert">
								<p>@lang('register.verifyEmail') {{ Auth::user()->email }}. 
								<a href="{{ route('dashboard.resendVerification', App::getLocale()) }}">@lang('register.resendVerification')</a></p>
							</div>
						</div>
					</div>
				@endif