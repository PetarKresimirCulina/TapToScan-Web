@extends('layouts.core')
@section('title', __('dashboardBillingPlans.title'))
@section('description', __('dashboardBillingPlans.description'))
@section('keywords', __('dashboardBillingPlans.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				@include('includes.emailVerify')
				<h1 class="margin-bottom-2 text-capitalize">@lang('dashboardBillingPlans.title')</h1>
				
				<div class="col-xs-12">
					@foreach($plans as $plan)
					<div class="col-xs-12 col-md-4">
						<div class="panel panel-default panel-business">
							<div class="panel-heading text-center">
								<h1>{{ $plan->name }}</h1>
								<i class="material-icons">local_cafe</i>
							</div>
							<div class="panel-body">
								<div class="panel-price">
									@php $currencyDummy = new \App\Currency(); @endphp
									<h1 class="text-center">{{ $currencyDummy->formatCurrency(App::getLocale(), Auth::user()->plan->price, 'EUR', '€') }}/@lang('pages/business.month')</h1>
									<ul class="list-group">
										<li class="list-group-item">@lang('dashboardBillingPlans.tagsLimit1', ['limit' => $plan->tags_limit])</li>
										<li class="list-group-item">@lang('pages/business.smallLine2')</li>
										<li class="list-group-item">@lang('pages/business.smallLine3')</li>
										<li class="list-group-item">@lang('pages/business.smallLine4')</li>
									</ul>
									<div class="text-center">
										@if(Auth::user()->plan->id == $plan->id)
											<p>@lang('dashboardBillingPlans.yourPlan')</p>
										@else
											<a href="{{ route('register', App::getLocale()) }}" class="btn btn-success btn-lg text-capitalize">@lang('actions.select')</a>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
@stop