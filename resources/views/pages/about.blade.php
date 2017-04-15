@extends('layouts.core')
@section('title', __('pages/aboutus.title'))
@section('description', __('pages/aboutus.description'))
@section('keywords', __('pages/aboutus.keywords'))

@section('content')

	<div class="container">
	
		<div class="row text-center margin-4">
			<div class="col-xs-12">
				<h1 class="margin-bottom-2">@lang('pages/aboutus.whoAreWeTitle')</h1>
				<i class="material-icons heading-icon margin-bottom-2">people</i>
				<p>@lang('pages/aboutus.whoAreWe')</p>
			</div>
		</div>
		
		<div class="row text-center margin-4">
			<div class="col-xs-12">
				<h1 class="margin-bottom-2">@lang('pages/aboutus.ourMissionTitle')</h1>
				<i class="material-icons heading-icon margin-bottom-2">details</i>
				<p>@lang('pages/aboutus.ourMission')</p>
			</div>
		</div>
	</div>
@stop