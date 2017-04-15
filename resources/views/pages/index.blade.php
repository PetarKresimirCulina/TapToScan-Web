@extends('layouts.core')
@section('title', __('pages/index.title'))
@section('description', __('pages/index.description'))
@section('keywords', __('pages/index.keywords'))
@section('aos')
	<link rel="stylesheet" href="{{ URL::asset('css/animations.css') }}">
@stop

@section('content')

	<div class="container-fluid home-banner-container animatedParent animateOnce">
		<div class="col-xs-12">
			<div class="animated growIn">
				<h1>@lang('pages/index.headingMain')</h1>
				<p>@lang('pages/index.headingSubtitle')</p>
				<a href="#" class="btn btn-round btn-success btn-lg text-capitalize btn-cta" role="button"><i class="material-icons">get_app</i></i> @lang('pages/index.CTA')</a>
				<p class="small">@lang('pages/index.playStore')</p>
			</div>
		</div>
	</div>
	
	
	<div class="container">
		<div class="row text-center margin-4 animatedParent animateOnce" data-sequence="250">
			<div class="col-md-4 animated growIn" data-id="1">
				<i class="material-icons landing-icon">timelapse</i>
				<p>@lang('pages/index.cta1')</p>
			</div>
			<div class="col-md-4 animated growIn" data-id="2">
				<i class="material-icons landing-icon">nfc</i>
				<p>@lang('pages/index.cta2')</p>
			</div>
			<div class="col-md-4 animated growIn" data-id="3">
				<i class="material-icons landing-icon">phone_android</i>
				<p>@lang('pages/index.cta3')</p>
			</div>
		</div>
		
	</div>

	<div id="what-is-tts">
		<div class="container">
			<div class="row margin-4 animatedParent animateOnce">
				<div class="col-md-7 col-xs-12 animated growIn">
					<h1 class="margin-bottom-2">@lang('pages/index.whatIsTTSTitle')</h1>
					<p>@lang('pages/index.whatIsTTS')</p>
				</div>
			</div>	
		</div>
	</div>
	
	<div id="how-it-works">
		<div class="container">
			<div class="row margin-4">
			
				<div class="col-xs-12 text-center animatedParent animateOnce" data-sequence="250">
				
					<h1 class="margin-bottom-2">@lang('pages/index.howItWorks')</h1>
					<div class="col-md-3 animated growIn"  data-id="1">
						<div class="panel panel-default panel-landing">
							<div class="panel-body text-center">
								<i class="material-icons">get_app</i>
								<h4>@lang('pages/index.howItWorksCTA1Title')</h4>
								<p class="small">@lang('pages/index.howItWorksCTA1')</p>
							</div>
						</div>
					</div>
					
					<div class="col-md-3 animated growIn"  data-id="2">
						<div class="panel panel-default panel-landing">
							<div class="panel-body text-center">
								<i class="material-icons">nfc</i>
								<h4>@lang('pages/index.howItWorksCTA2Title')</h4>
								<p class="small">@lang('pages/index.howItWorksCTA2')</p>
							</div>
						</div>
					</div>
					
					<div class="col-md-3 animated growIn" data-id="3">
						<div class="panel panel-default panel-landing">
							<div class="panel-body text-center">
								<i class="material-icons">phone_android</i>
								<h4>@lang('pages/index.howItWorksCTA3Title')</h4>
								<p class="small">@lang('pages/index.howItWorksCTA3')</p>
							</div>
						</div>
					</div>
					
					<div class="col-md-3 animated growIn" data-id="4">
						<div class="panel panel-default panel-landing">
							<div class="panel-body text-center">
								<i class="material-icons">local_cafe</i>
								<h4>@lang('pages/index.howItWorksCTA4Title')</h4>
								<p class="small">@lang('pages/index.howItWorksCTA4')</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	

@stop