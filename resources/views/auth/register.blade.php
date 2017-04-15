@extends('layouts.core')
@section('title', __('register.title'))
@section('description', __('register.description'))
@section('keywords', __('register.keywords'))

@section('content')

	<div class="container">
	
		<div class="row text-center margin-4">
			<h1 class="margin-bottom-2">@lang('register.register')</h1>
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
				<form id="register-form" action="{{ route('register', App::getLocale()) }}" method="post">
					
					{{ csrf_field () }}
					
					<div class="form-group">
						<label class="text-capitalize" for="email">@lang('register.email')</label>
						<input type="email" class="form-control input-lg" name="email" id="email" required autofocus>
					</div>
					
					<div class="form-group">
						<label class="text-capitalize" for="password">@lang('register.password')</label>
						<input type="password" class="form-control input-lg" name="password" id="password" required>
					</div>
					
					<div class="form-group">
						<label class="text-capitalize" for="password_confirmation">@lang('register.password_confirmation')</label>
						<input type="password" class="form-control input-lg" name="password_confirmation" id="password_confirmation" required>
					</div>
					
					<div class="form-group">
						<label class="text-capitalize" for="country">@lang('register.country')</label>
						<select class="form-control input-lg" id="country" name="country">
							@foreach ($codes as $key => $value)
								<option value="{{ $key }}">{{ $value }}</option>
							@endforeach
						</select>
					</div>
					
					<button type="submit" class="btn btn-round btn-success btn-lg text-uppercase margin-top-2">@lang('register.register_btn')</button>
				</form>
			</div>
			
		</div>
			
	</div>

@stop