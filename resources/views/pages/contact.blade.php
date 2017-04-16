@extends('layouts.core')
@section('title', __('pages/contactus.title'))
@section('description', __('pages/contactus.description'))
@section('keywords', __('pages/contactus.keywords'))

@section('content')

	<div class="container">
	
		<div class="row text-center margin-4">
			<div class="col-xs-12">
				<h1 class="margin-bottom-2">@lang('pages/contactus.heading')</h1>				
				<i class="material-icons heading-icon margin-bottom-2">drafts</i>
				<p>@lang('pages/contactus.headingText')</p>
			</div>
		</div>

		@include('includes.alerts')
		
		<div class="row text-center margin-4" @if(Session::has('success')) style="display: none;" @endif >
			<div class="col-md-6 col-md-offset-3 text-center">
				<form id="contactform" action="{{ route('contact.send', App::getLocale()) }}" method="post">
					{{ csrf_field () }}
					<div class="form-group">
						<label for="name">@lang('pages/contactus.formName')</label>
						<input type="text" class="form-control input-lg" name="name" id="name" required autofocus>
					</div>
					<div class="form-group">
						<label for="email">@lang('pages/contactus.formEmail')</label>
						<input type="email" class="form-control input-lg" name="email" id="email" required>
					</div>
					<div class="form-group">
						<label for="message">@lang('pages/contactus.formMessage')</label>
						<textarea class="form-control input-lg" id="message" name="message" required></textarea>
					</div>
					<button type="submit" class="btn btn-round btn-info btn-lg text-uppercase margin-top-2">@lang('pages/contactus.formSend')</button>
				</form>
			</div>
				
				
		</div>
			
	</div>

@stop