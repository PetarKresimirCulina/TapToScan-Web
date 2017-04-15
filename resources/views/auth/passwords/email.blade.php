@extends('layouts.core')
@section('title', __('reset.title'))
@section('description', __('reset.description'))
@section('keywords', __('reset.keywords'))

@section('content')

	<div class="container">
	
		<div class="row text-center margin-4">
			<h1 class="margin-bottom-2 text-capitalize">@lang('reset.reset')</h1>
					@if (session('status'))
						<div class="row margin-4">
							<div class="col-md-6 col-md-offset-3">
								<div class="alert alert-success" role="alert">
									{{ session('status') }}
								</div>
							</div>
                        </div>
                    @endif
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
				<form id="login-form" action="{{ route('password.email', App::getLocale()) }}" method="post">
					
					{{ csrf_field () }}
					
					<div class="form-group">
						<label class="text-capitalize" for="email">@lang('reset.email')</label>
						<input type="email" class="form-control input-lg" name="email" id="email" value="{{ old('email') }}" required autofocus>
					</div>
					
					<button type="submit" class="btn btn-round btn-success btn-lg text-uppercase margin-top-2">@lang('reset.send')</button>
				</form>
			</div>
			
		</div>
			
	</div>

@stop
