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
				
				<ul class="nav nav-tabs text-capitalize">
					<li><a href="{{ route('dashboard.settings', App::getLocale()) }}"><i class="material-icons">account_circle</i> @lang('dashboardSettings.basicInfo')</a></li>
					<li class="active"><a href="{{ route('dashboard.settingsPanel2', App::getLocale()) }}"><i class="material-icons">verified_user</i> @lang('dashboardSettings.password')</a></li>
					
					
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
						<form id="update-form" action="{{ route('dashboard.updatePassword', App::getLocale()) }}" method="post">
							
							{{ csrf_field () }}
							
							<div class="form-group">
								<label class="text-capitalize" for="password">@lang('dashboardSettings.currentPass')</label>
								<input type="password" class="form-control input-lg" name="password" id="password">
							</div>
							
							<div class="form-group">
								<label class="text-capitalize" for="password_new">@lang('dashboardSettings.newPass')</label>
								<input type="password" class="form-control input-lg" name="password_new" id="password_new">
							</div>
							
							<div class="form-group">
								<label class="text-capitalize" for="password_new_confirmation">@lang('dashboardSettings.repeatNewPass')</label>
								<input type="password" class="form-control input-lg" name="password_new_confirmation" id="password_new_confirmation">
							</div>
							
							<div class="text-center">
								<button type="submit" class="btn btn-success btn-lg text-uppercase margin-top-2">@lang('dashboardSettings.updatePass')</button>
							</div>
							
						</form>
					</div>
			</div>
		</div>
	</div>
@stop