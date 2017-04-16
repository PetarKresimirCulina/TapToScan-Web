@extends('layouts.core')
@section('title', __('dashboardSettings.title'))
@section('description', __('dashboardSettings.description'))
@section('keywords', __('dashboardSettings.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				@include('includes.emailVerify')
				<h1 class="margin-bottom-2 text-capitalize">@lang('dashboardSettings.title')</h1>
				
				<ul class="nav nav-tabs text-capitalize">
					<li class="active"><a href="{{ route('dashboard.settings', App::getLocale()) }}"><i class="material-icons">account_circle</i> @lang('dashboardSettings.basicInfo')</a></li>
					<li><a href="{{ route('dashboard.settingsPanel2', App::getLocale()) }}"><i class="material-icons">verified_user</i> @lang('dashboardSettings.password')</a></li>
					
					
				</ul>

				<div class="col-md-8 col-md-offset-2 margin-4">
					@if($errors->count() > 0)
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
							@foreach($errors->all() as $message)
							<div class="alert alert-danger" role="alert">
								<p>{{ $message }}</p>
							</div>
							@endforeach 
						</div>
					</div>
					@elseif(Session::has('success'))
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
							<div class="alert alert-success" role="alert">
								<p>{{ Session::get('success') }}</p>
							</div>
						</div>
					</div>
					@endif
						<form id="update-form" action="{{ route('dashboard.updateUserInformation', App::getLocale()) }}" method="post">
							
							{{ csrf_field () }}
							
							<div class="form-group">
								<label class="text-capitalize" for="name">@lang('dashboardSettings.businessName')</label>
								<input type="text" class="form-control input-lg" name="name" id="name" value="{{ Auth::user()->business_name }}">
							</div>
							
							<div class="form-group">
								<label class="text-capitalize" for="address">@lang('dashboardSettings.address')</label>
								<input type="text" class="form-control input-lg" name="address" id="address" value="{{ Auth::user()->address }}">
							</div>
							
							<div class="form-group">
								<label class="text-capitalize" for="city">@lang('dashboardSettings.city')</label>
								<input type="text" class="form-control input-lg" name="city" id="city" value="{{ Auth::user()->city }}">
							</div>
							
							<div class="form-group">
								<label class="text-capitalize" for="zip">@lang('dashboardSettings.zip')</label>
								<input type="numeric" class="form-control input-lg" name="zip" id="zip" value="{{ Auth::user()->zip }}">
							</div>
							
							<div class="text-center">
								<button type="submit" class="btn btn-success btn-lg text-uppercase margin-top-2">@lang('dashboardSettings.update')</button>
							</div>
							
						</form>
					</div>
			</div>
		</div>
	</div>
@stop