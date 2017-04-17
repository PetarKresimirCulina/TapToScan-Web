@extends('layouts.core')
@section('title', 'Billing')
@section('description', 'Manage your account billing and invoices')
@section('keywords', 'taptoscan, billing, user, dashboard')

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				<h1 class="margin-bottom-2 text-capitalize">@lang('dashboardBilling.title')</h1>
				
				@include('includes.alerts')
				
				<ul class="nav nav-tabs text-capitalize">
					<li class="active"><a href="{{ route('dashboard.settings', App::getLocale()) }}"><i class="material-icons">payment</i> @lang('dashboardBilling.subscriptionSettings')</a></li>
					<li><a href="{{ route('dashboard.settingsPanel2', App::getLocale()) }}"><i class="material-icons">history</i> @lang('dashboardBilling.paymentHistory')</a></li>
				</ul>
				
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					<div class="panel panel-default margin-4">
						<div class="panel-heading">@lang('dashboardBilling.currentPlan')</div>
						<div class="panel-body">
							<p><span class="bold">@lang('dashboardBilling.currentPlanShort'): </span>{{ Auth::user()->plan->name }}</p>
							@php $currencyDummy = new \App\Currency(); @endphp
							<p><span class="bold">@lang('dashboardBilling.monthlyFee'): </span>{{ $currencyDummy->formatCurrency(App::getLocale(), Auth::user()->plan->price, 'EUR', '€') }}</p>
							<hr>
							<p><span class="bold">@lang('dashboardBilling.lastPayment'): </span>15/04/2017</p>
							<p><span class="bold">@lang('dashboardBilling.nextPayment'): </span>15/05/2017</p>
							<hr>
							<p><span class="bold">@lang('dashboardBilling.nfcLimit'): </span>{{ Auth::user()->plan->tags_limit }}</p>
							<div class="text-center">
								<a href="{{ route('dashboard.billing.changePlan', App::getLocale()) }}" class="btn btn-primary text-capitalize"><i class="material-icons">edit</i> @lang('dashboardBilling.changePlan')</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop