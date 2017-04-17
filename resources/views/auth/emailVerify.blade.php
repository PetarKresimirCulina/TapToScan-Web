@extends('layouts.core')
@section('title', __('login.title'))
@section('description', __('login.description'))
@section('keywords', __('login.keywords'))

@section('content')

	<div class="container">
	
		<div class="row text-center margin-4">
			<h1 class="margin-bottom-2">@lang('register.verifyTitle')</h1>
			
			<p>@lang('register.verifyEmail') {{ Auth::user()->email }}<p>
			<a href="{{ route('dashboard.resendVerification', App::getLocale()) }}" class="btn btn-primary margin-top-2">@lang('register.resendVerification')</a>
			
		</div>
			
	</div>

@stop