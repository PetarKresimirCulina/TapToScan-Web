@extends('layouts.core')
@section('title', __('login.title'))
@section('description', __('login.description'))
@section('keywords', __('login.keywords'))

@section('content')

	<div class="container">
	
		<div class="row text-center margin-4">
			<h1 class="margin-bottom-2">@lang('login.login')</h1>
					@if($errors->count() > 0)
						<div class="row margin-4">
							<div class="col-md-6 col-md-offset-3">
								@foreach($errors->all() as $message)
								<div class="alert alert-danger" role="alert">
									<p>{{ $message }}</p>
								</div>
								@endforeach 
							</div>
						</div>
					@endif
			
			<div class="col-md-6 col-md-offset-3 text-center">
				<form id="login-form" action="{{ route('login', App::getLocale()) }}" method="post">
					
					{{ csrf_field () }}
					
					<div class="form-group">
						<label class="text-capitalize" for="email">@lang('login.email')</label>
						<input type="email" class="form-control input-lg" name="email" id="email" required autofocus>
					</div>
					
					<div class="form-group">
						<label class="text-capitalize" for="password">@lang('login.password')</label>
						<input type="password" class="form-control input-lg" name="password" id="password" required>
					</div>
					<div class="form-group">
						<div class="checkbox">
							<label>
							<input checked="checked" name="remember" type="checkbox" id="remember">
							@lang('login.remember')
							</label>
						</div>
					</div>
					<p><a href="{{ '/' . App::getLocale() . '/password/reset' }}">@lang('login.forgot')</a></p>
					
					<button type="submit" class="btn btn-round btn-success btn-lg text-uppercase margin-top-2">@lang('login.login_btn')</button>
					
				</form>
			</div>
			
		</div>
			
	</div>

@stop