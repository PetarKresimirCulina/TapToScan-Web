@extends('layouts.core')
@section('title', __('dashboardSettings.title'))
@section('description', __('dashboardSettings.description'))
@section('keywords', __('dashboardSettings.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				<h1 class="margin-bottom-2 text-capitalize">@lang('dashboardSettings.title')</h1>
				
				@include('includes.alerts')
				@include('includes.blocked')
				@include('includes.noTags')
				
				<ul class="nav nav-tabs text-capitalize">
					<li class="active"><a href="{{ route('dashboard.settings', App::getLocale()) }}"><i class="material-icons">account_circle</i> @lang('dashboardSettings.basicInfo')</a></li>
					<li><a href="{{ route('dashboard.settingsPanel2', App::getLocale()) }}"><i class="material-icons">verified_user</i> @lang('dashboardSettings.password')</a></li>
					
					
				</ul>

				<div class="col-md-8 col-md-offset-2 margin-4">
					
					<form id="update-form" action="{{ route('dashboard.updateUserInformation', App::getLocale()) }}" method="post">
						{{ csrf_field () }}
						<div class="form-group">
						<label class="text-capitalize" for="name">@lang('dashboardSettings.businessName')</label>
							<input type="text" class="form-control input-lg" name="name" id="name" value="{{ Auth::user()->business_name }}" required>
						</div>
						
						<div class="form-group">
							<label class="text-capitalize" for="address">@lang('dashboardSettings.address')</label>
							<input type="text" class="form-control input-lg" name="address" id="address" value="{{ Auth::user()->address }}" required>
						</div>
						
						<div class="form-group">
							<label class="text-capitalize" for="city">@lang('dashboardSettings.city')</label>
							<input type="text" class="form-control input-lg" name="city" id="city" value="{{ Auth::user()->city }}" required>
						</div>
							
						<div class="form-group">
							<label class="text-capitalize" for="zip">@lang('dashboardSettings.zip')</label>
							<input type="numeric" class="form-control input-lg" name="zip" id="zip" value="{{ Auth::user()->zip }}" required>
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