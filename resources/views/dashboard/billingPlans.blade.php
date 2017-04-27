@extends('layouts.core')
@section('title', __('dashboardBillingPlans.title'))
@section('description', __('dashboardBillingPlans.description'))
@section('keywords', __('dashboardBillingPlans.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				<h1 class="margin-bottom-2 text-capitalize">@lang('dashboardBillingPlans.title')</h1>
				
				@include('includes.blocked')
				
				<a href="{{ route('dashboard.billing', App::getLocale()) }}" class="btn btn-default text-capitalize margin-bottom-2"><i class="material-icons">keyboard_arrow_left</i> @lang('actions.back')</a>
				
				<div class="col-xs-12">
					@foreach($plans as $plan)
					<div class="col-xs-12 col-md-4">
						<div class="panel panel-default panel-business">
							<div class="panel-heading text-center">
								<h1>{{ $plan->name }}</h1>
								
									 {{ $plan->getCurrency->id }}
									 
								@if($plan->name == 'Small')
									<i class="material-icons">local_cafe</i>
								@elseif($plan->name == 'Medium')
									<i class="material-icons">local_cafe</i>
									<i class="material-icons">local_cafe</i>
								@else
									<i class="material-icons">local_cafe</i>
									<i class="material-icons">local_cafe</i>
									<i class="material-icons">local_cafe</i>
								@endif
							</div>
							<div class="panel-body">
									<div class="panel-price">
										@php $currencyDummy = new \App\Currency(); @endphp
										<h1 class="text-center">{{ $currencyDummy->formatCurrency(App::getLocale(), $plan->price, $plan->getCurrency->code, $plan->getCurrency->symbol) }}/@lang('pages/business.month')</h1>
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
												<a href="#" class="btn btn-success btn-subscribe" data-toggle="modal" data-target="#planChange" data-id="{{ $plan->id }}" data-name="{{ $plan->name }}" data-price="{{ $currencyDummy->formatCurrency(App::getLocale(), $plan->price, 'EUR', '€') }}/@lang('pages/business.month')">@lang('actions.select')</a>
											
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
	
	<div id="planChange" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardBilling.changePlan')</h4>
				</div>
				<form id="change" class="inline" action="{{ route('subscription.change', App::getLocale()) }}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="planID" id="planID" value="1" />
					<div class="modal-body text-center">
						<h1 class="text-capitalize" id="planName"></h1>
						<p class="text-capitalize" id="planPrice"></p>
						<hr>
						<p>@lang('dashboardBillingPlans.changePlanQuestion')</p>
					</div>
					<div class="modal-footer text-center">
						 <button id="payment-button" class="btn btn-success btn-flat" type="submit">@lang('actions.yes')</button>
						 <a href="#" data-dismiss="modal" class="btn btn-danger btn-flat">@lang('actions.no')</a>
					</div>
				</form>
			</div>
		</div>
	</div>												
	
@stop	
@section('javascript')
	
	<script>
		$(document).ready(function() {
			
			$(".btn-subscribe").click(function(e){
				$('#planID').val($(this).data('id'));
				$('#planName').html($(this).data('name'));
				$('#planPrice').html($(this).data('price'));
			});
			
		});
	</script>
	

@stop