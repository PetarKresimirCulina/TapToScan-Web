@extends('layouts.core')
@section('title', __('register.verifyTitle'))
@section('description', __('register.description'))
@section('keywords', __('register.keywords'))

@section('content')

	<div class="container">
	
		<div class="row text-center margin-4">
			<h1 class="margin-bottom-2">@lang('register.verifyTitle')</h1>
			
			<p>@lang('register.verifyEmail') {{ Auth::user()->email }}<p>
			<a href="{{ route('dashboard.resendVerification', App::getLocale()) }}" class="btn btn-primary margin-top-2">@lang('register.resendVerification')</a>
			
		</div>
			
	</div>

@stop