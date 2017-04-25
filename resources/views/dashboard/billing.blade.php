@extends('layouts.core')
@section('title', __('dashboardBilling.title'))
@section('description', __('dashboardBilling.description'))
@section('keywords', __('dashboardBilling.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				<h1 class="margin-bottom-2 text-capitalize">@lang('dashboardBilling.title')</h1>
				
				@include('includes.alerts')
				@include('includes.blocked')
				
				<ul class="nav nav-tabs text-capitalize">
					<li class="active"><a href="{{ route('dashboard.billing', App::getLocale()) }}"><i class="material-icons">payment</i> @lang('dashboardBilling.subscriptionSettings')</a></li>
					<li><a href="{{ route('dashboard.billing.history', App::getLocale()) }}"><i class="material-icons">history</i> @lang('dashboardBilling.paymentHistory')</a></li>
				</ul>
				
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					<div class="panel panel-default margin-4">
						<div class="panel-heading">@lang('dashboardBilling.currentPlan')</div>
						<div class="panel-body">
							<p><span class="bold">@lang('dashboardBilling.currentPlanShort'): </span>{{ Auth::user()->plan->name }}</p>
							@php $currencyDummy = new \App\Currency(); @endphp
							<p><span class="bold">@lang('dashboardBilling.monthlyFee'): </span>{{ $currencyDummy->formatCurrency(App::getLocale(), Auth::user()->plan->price, 'EUR', '€') }}</p>

							<hr>
							<p><span class="bold">@lang('dashboardBilling.balance'): </span>{{ $currencyDummy->formatCurrency(App::getLocale(), floatval($subscription->balance), 'EUR', '€') }}</p>
							<p><span class="bold">@lang('dashboardBilling.nextPayment'): </span>{{ $currencyDummy->formatCurrency(App::getLocale(), floatval($subscription->nextBillAmount), 'EUR', '€') }}</p>
							<p><span class="bold">@lang('dashboardBilling.nextPaymentDate'): </span>{{ $subscription->nextBillingDate->format("d.m.Y.")  }}</p>
							<p><span class="bold">@lang('dashboardBilling.paymentMethod'): </span>
							
								@if(Auth::user()->card_last_four != null)
									XXXX-XXXX-XXXX-{{ Auth::user()->card_last_four  }} ({{ Auth::user()->card_brand }}) 
								@elseif(Auth::user()->paypal_email != null)
								{{ Auth::user()->paypal_email }} (Paypal)
								@endif
								<a href="#" data-toggle="modal" data-target="#editCC">@lang('actions.edit')</a>
								
							</p>
							<hr>
							<p><span class="bold">@lang('dashboardBilling.nfcLimit'): </span>{{ Auth::user()->plan->tags_limit }}</p>
							<div class="text-center">
								@if(Auth::user()->blocked == 0)
									<a href="{{ route('dashboard.billing.changePlan', App::getLocale()) }}" class="btn btn-primary text-capitalize"><i class="material-icons">edit</i> @lang('dashboardBilling.changePlan')</a>
								@else
									<a href="{{ route('dashboard.billing.retryCharge', App::getLocale()) }}" class="btn btn-primary text-capitalize">@lang('dashboardBilling.retryCharge')</a>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<table>
</table>
	</div>
	
	<div id="editCC" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardBilling.editPaymentMethod')</h4>
				</div>
				<form id="change" class="inline" action="{{ route('subscription.editCC', App::getLocale()) }}" method="POST">
					{{ csrf_field() }}
					<div class="modal-body text-center">
						<div id="dropin-container"></div>
					</div>
					<div class="modal-footer text-center">
						 <button id="payment-button" class="btn btn-success btn-flat hidden" type="submit">@lang('actions.save')</button>
						 <a href="#" id="btnNo" data-dismiss="modal" class="btn btn-danger btn-flat hidden">@lang('actions.cancel')</a>
					</div>
				</form>
			</div>
		</div>
	</div>					
@stop

@section('javascript')
	<script src="https://js.braintreegateway.com/js/braintree-2.30.0.min.js"></script>
	
	<script>
        $.ajax({
            url: '{{ route('braintree.generateToken', App::getLocale()) }}'
        }).done(function (response) {
            braintree.setup(response.data.token, 'dropin', {
                container: 'dropin-container',
				paypal: {
					button: {
						type: 'checkout'
					}
				},
                onReady: function () {
                    $('#payment-button').removeClass('hidden');
					$('#btnNo').removeClass('hidden');
                }
            });
        });
    </script>
	
	<script>
		$(document).ready(function() {
			
			$(".btn-subscribe").click(function(e){
				$('#planID').val($(this).data('id'));
				$('#planName').html($(this).data('name'));
				$('#planPrice').html($(this).data('price'));
			});
			
			$('form#change').submit(function(){
				$(this).find(':input[type=submit]').attr('disabled', true);
				$('#btnNo').attr('disabled', true);
			});
		});
		

	</script>
	

@stop