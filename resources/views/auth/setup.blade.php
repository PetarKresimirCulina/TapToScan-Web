@extends('layouts.core')
@section('title', __('setup.title'))
@section('description', __('setup.description'))
@section('keywords', __('setup.keywords'))

@section('aos')
	<link rel="stylesheet" href="{{ URL::asset('css/animations.css') }}">
@stop

@section('content')

	<div class="container">
	
		<div class="row text-center margin-4">
			<h1 class="margin-bottom-2">@lang('setup.letsSetup')</h1>
			
			<div>
				@include('includes.alerts')
				<form id="setupForm" method="POST" class="animatedParent animateOnce" action="{{ route('user.setup.update', App::getLocale()) }}">
					{{ csrf_field () }}
					
					<div class="col-md-6 col-md-offset-3 text-center">
						<div class="progress">
							<div id="progress" class="progress-bar" role="progressbar" aria-valuenow="0"
								  aria-valuemin="0" aria-valuemax="100" style="width:0%">
							</div>
						</div>
					</div>
				
					<div id="f_name" class="col-md-6 col-md-offset-3 text-center animated fadeIn">
					
						<div class="form-group">
							<p>@lang('setup.whoYouAre')<p>
						</div>
						<div class="form-group">
							<label class="text-capitalize" for="email">@lang('setup.firstName')</label>
							<input type="text" class="form-control input-lg" name="first_name" id="first_name" required autofocus>
						</div>
						
						<div class="form-group">
							<label class="text-capitalize" for="password">@lang('setup.lastName')</label>
							<input type="text" class="form-control input-lg" name="last_name" id="last_name" required>
						</div>
						<a id="step1" href="#" class="btn btn-primary margin-top-2">@lang('actions.next')</a>
					</div>
					
					<div id="f_business" class="hidden col-md-6 col-md-offset-3 text-center animated fadeIn">
					
						<div class="form-group">
							<p>@lang('setup.yourBusinessInfo')<p>
						</div>
						<div class="form-group">
							<label class="text-capitalize" for="name">@lang('dashboardSettings.businessName')</label>
							<input type="text" class="form-control input-lg" name="name" id="name" required>
						</div>
						
						<div class="form-group">
							<label class="text-capitalize" for="address">@lang('dashboardSettings.address')</label>
							<input type="text" class="form-control input-lg" name="address" id="address" required>
						</div>
							
						<div class="form-group">
							<label class="text-capitalize" for="city">@lang('dashboardSettings.city')</label>
							<input type="text" class="form-control input-lg" name="city" id="city" required>
						</div>
								
						<div class="form-group">
							<label class="text-capitalize" for="zip">@lang('dashboardSettings.zip')</label>
							<input type="numeric" class="form-control input-lg" name="zip" id="zip" required>
						</div>
						<button id="step2" type="submit" class="btn btn-primary margin-top-2">@lang('actions.next')</button>
					</div>
					
					
				</form>
			</div>
		</div>
	</div>	

@stop

@section('javascript')
	
	<script>
		$(document).ready(function() {
			
			$("#step1").click(function(e){
				if($("#first_name").val().length > 1 && $("#last_name").val().length > 1) {
					$('.progress-bar').css('width', '33%').attr('aria-valuenow', 33).html('');
					$('#f_name').hide();
					$('#f_business').removeClass('hidden');
					$('#name').focus();
				}
			});
		});
		
		
	</script>
@stop