@extends('layouts.core')
@section('title', __('pages/business.title'))
@section('description', __('pages/business.description'))
@section('keywords', __('pages/business.keywords'))
@section('aos')
	<link rel="stylesheet" href="{{ URL::asset('css/animations.css') }}">
@stop

@section('content')

	<div class="business-banner-container animatedParent animateOnce" data-sequence="250">
		<div class="container">
			<div>
				<h1>@lang('pages/business.startTrial')</h1>
				<p class="margin-bottom-2">@lang('pages/business.choosePlan')</p>

					<div class="col-md-4 animated growIn" data-id="1">
						<div class="panel panel-default panel-business">
							<div class="panel-heading text-center">
								<h1>@lang('pages/business.small')</h1>
								<i class="material-icons">local_cafe</i>
							</div>
							<div class="panel-body">
								<div class="panel-price">
									<h1 class="text-center">7.99 EUR/@lang('pages/business.month')</h1>
									<ul class="list-group">
										<li class="list-group-item">@lang('pages/business.smallLine1')</li>
										<li class="list-group-item">@lang('pages/business.smallLine2')</li>
										<li class="list-group-item">@lang('pages/business.smallLine3')</li>
										<li class="list-group-item">@lang('pages/business.smallLine4')</li>
									</ul>
									<a href="{{ route('register', App::getLocale()) }}" class="btn btn-success btn-lg text-capitalize btn-signup" data-id="1">@lang('pages/business.signUp')</a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-4 animated growIn" data-id="2">
						<div class="panel panel-default panel-business">
							<div class="panel-heading text-center">
								<h1>@lang('pages/business.medium')</h1>
								<i class="material-icons">local_cafe</i>
								<i class="material-icons">local_cafe</i>
							</div>
							<div class="panel-body">
								<div class="panel-price">
									<h1 class="text-center">19.99 EUR/@lang('pages/business.month')</h1>
									<ul class="list-group">
										<li class="list-group-item">@lang('pages/business.mediumLine1')</li>
										<li class="list-group-item">@lang('pages/business.mediumLine2')</li>
										<li class="list-group-item">@lang('pages/business.mediumLine3')</li>
										<li class="list-group-item">@lang('pages/business.mediumLine4')</li>
									</ul>
									<a href="{{ route('register', App::getLocale()) }}" class="btn btn-success btn-lg text-capitalize btn-signup" data-id="2">@lang('pages/business.signUp')</a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-4 animated growIn" data-id="3">
						<div class="panel panel-default panel-business">
							<div class="panel-heading text-center">
								<h1>@lang('pages/business.large')</h1>
								<i class="material-icons">local_cafe</i>
								<i class="material-icons">local_cafe</i>
								<i class="material-icons">local_cafe</i>
							</div>
							<div class="panel-body">
								<div class="panel-price">
									<h1 class="text-center">29.99 EUR/@lang('pages/business.month')</h1>
									<ul class="list-group">
										<li class="list-group-item">@lang('pages/business.largeLine1')</li>
										<li class="list-group-item">@lang('pages/business.largeLine2')</li>
										<li class="list-group-item">@lang('pages/business.largeLine3')</li>
										<li class="list-group-item">@lang('pages/business.largeLine4')</li>
									</ul>
									<a href="{{ route('register', App::getLocale()) }}" class="btn btn-success btn-lg text-capitalize btn-signup" data-id="3">@lang('pages/business.signUp')</a>
								</div>
							</div>
						</div>
					</div>
					

			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row text-center margin-4 animatedParent animateOnce" data-sequence="250">
			<div class="col-md-4 animated growIn" data-id="1">
				<i class="material-icons landing-icon">access_time</i>
				<p>@lang('pages/business.landing1')</p>
			</div>
			<div class="col-md-4 animated growIn" data-id="2">
				<i class="material-icons landing-icon">trending_up</i>
				<p>@lang('pages/business.landing2')</p>
			</div>
			<div class="col-md-4 animated growIn" data-id="3">
				<i class="material-icons landing-icon">web</i>
				<p>@lang('pages/business.landing3')</p>
			</div>
		</div>
		
	</div>
	
	<div class="container">
		<div class="row margin-4 animatedParent animateOnce">
			<div class="col-xs-12 col-md-5 business-text col-md-push-7 animated growIn">
				<h1 class="text-capitalize">@lang('pages/business.feature1Title')</h1>
				<p>@lang('pages/business.feature1Text')</p>
			</div>
			
			<div class="col-xs-12 col-md-7 col-md-pull-5 animated growIn">
				<img class="img-responsive" src="{{ URL::asset('img/business1.png') }}"/>
			</div>
		</div>
		
		<div class="row margin-4 animatedParent animateOnce">
			<div class="col-xs-12 col-md-5 business-text animated growIn">
				<h1 class="text-capitalize">@lang('pages/business.feature2Title')</h1>
				<p>@lang('pages/business.feature2Text')</p>
			</div>
			
			<div class="col-xs-12 col-md-7 animated growIn">
				<img class="img-responsive" src="{{ URL::asset('img/business2.png') }}"/>
			</div>
		</div>
		
		<div class="row margin-4 animatedParent animateOnce">
			<div class="col-xs-12 col-md-5 business-text col-md-push-7 animated growIn">
				<h1 class="text-capitalize">@lang('pages/business.feature3Title')</h1>
				<p>@lang('pages/business.feature3Text')</p>
			</div>
			
			<div class="col-xs-12 col-md-7 col-md-pull-5 animated growIn">
				<img class="img-responsive" src="{{ URL::asset('img/business3.png') }}"/>
			</div>
		</div>
		
		<div class="row margin-4 animatedParent animateOnce">
			<div class="col-xs-12 col-md-5 business-text animated growIn">
				<h1 class="text-capitalize">@lang('pages/business.feature4Title')</h1>
				<p>@lang('pages/business.feature4Text')</p>
			</div>
			
			<div class="col-xs-12 col-md-7 animated growIn">
				<img class="img-responsive" src="{{ URL::asset('img/business4.png') }}"/>
			</div>
		</div>
		
	</div>
	
	<div class="container animatedParent animateOnce text-center margin-4">
			<div>
				<h1>@lang('pages/business.startTrial')</h1>
				<p class="margin-bottom-2">@lang('pages/business.choosePlan')</p>

					<div class="col-md-4 animated growIn" data-id="1">
						<div class="panel panel-default panel-business">
							<div class="panel-heading text-center">
								<h1>@lang('pages/business.small')</h1>
								<i class="material-icons">local_cafe</i>
							</div>
							<div class="panel-body">
								<div class="panel-price">
									<h1 class="text-center">7.99 EUR/@lang('pages/business.month')</h1>
									<ul class="list-group">
										<li class="list-group-item">@lang('pages/business.smallLine1')</li>
										<li class="list-group-item">@lang('pages/business.smallLine2')</li>
										<li class="list-group-item">@lang('pages/business.smallLine3')</li>
										<li class="list-group-item">@lang('pages/business.smallLine4')</li>
									</ul>
									<a href="{{ route('register', App::getLocale()) }}" class="btn btn-success btn-lg text-capitalize btn-signup" data-id="1">@lang('pages/business.signUp')</a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-4 animated growIn" data-id="2">
						<div class="panel panel-default panel-business">
							<div class="panel-heading text-center">
								<h1>@lang('pages/business.medium')</h1>
								<i class="material-icons">local_cafe</i>
								<i class="material-icons">local_cafe</i>
							</div>
							<div class="panel-body">
								<div class="panel-price">
									<h1 class="text-center">19.99 EUR/@lang('pages/business.month')</h1>
									<ul class="list-group">
										<li class="list-group-item">@lang('pages/business.mediumLine1')</li>
										<li class="list-group-item">@lang('pages/business.mediumLine2')</li>
										<li class="list-group-item">@lang('pages/business.mediumLine3')</li>
										<li class="list-group-item">@lang('pages/business.mediumLine4')</li>
									</ul>
									<a href="{{ route('register', App::getLocale()) }}" class="btn btn-success btn-lg text-capitalize btn-signup" data-id="2">@lang('pages/business.signUp')</a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-4 animated growIn" data-id="3">
						<div class="panel panel-default panel-business">
							<div class="panel-heading text-center">
								<h1>@lang('pages/business.large')</h1>
								<i class="material-icons">local_cafe</i>
								<i class="material-icons">local_cafe</i>
								<i class="material-icons">local_cafe</i>
							</div>
							<div class="panel-body">
								<div class="panel-price">
									<h1 class="text-center">29.99 EUR/@lang('pages/business.month')</h1>
									<ul class="list-group">
										<li class="list-group-item">@lang('pages/business.largeLine1')</li>
										<li class="list-group-item">@lang('pages/business.largeLine2')</li>
										<li class="list-group-item">@lang('pages/business.largeLine3')</li>
										<li class="list-group-item">@lang('pages/business.largeLine4')</li>
									</ul>
									<a href="{{ route('register', App::getLocale()) }}" class="btn btn-success btn-lg text-capitalize btn-signup" data-id="3">@lang('pages/business.signUp')</a>
								</div>
							</div>
						</div>
					</div>
					

			</div>
		</div>
	
	@section('javascript')
		<script>
			$( ".btn-signup" ).click(function() {
				$.cookie('packageSelected', $(this).data('id'), 1);
			});
		</script>
	@stop

@stop