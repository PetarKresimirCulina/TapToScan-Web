@extends('layouts.core')
@section('title', __('setup.title'))
@section('description', __('setup.description'))
@section('keywords', __('setup.keywords'))

@section('content')

	<div class="container">
	
		<div class="row text-center margin-4">
			<h1 class="margin-bottom-2">@lang('setup.chooseSubscriptionPlan')</h1>
			
			@include('includes.alerts')
			
			<div class="col-md-6 col-md-offset-3 text-center">
				<div class="progress">
					<div id="progress" class="progress-bar" role="progressbar" aria-valuenow="66"
						aria-valuemin="0" aria-valuemax="100" style="width:66%">
					</div>
				</div>
			</div>
			
			@foreach($plans as $plan)
					<div class="col-xs-12 col-md-4">
						<div class="panel panel-default panel-business">
							<div class="panel-heading text-center">
								<h1>{{ $plan->name }}</h1>
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
										<h1 class="text-center">{{ $currencyDummy->formatCurrency(App::getLocale(), $plan->price, 'EUR', '€') }}/@lang('pages/business.month')</h1>
										<ul class="list-group">
											<li class="list-group-item">@lang('dashboardBillingPlans.tagsLimit1', ['limit' => $plan->tags_limit])</li>
											<li class="list-group-item">@lang('pages/business.smallLine2')</li>
											<li class="list-group-item">@lang('pages/business.smallLine3')</li>
											<li class="list-group-item">@lang('pages/business.smallLine4')</li>
										</ul>
										<div class="text-center">
											<a href="#" class="btn btn-success btn-subscribe" data-toggle="modal" data-target="#planChange" data-id="{{ $plan->id }}" data-name="{{ $plan->name }}" data-price="{{ $currencyDummy->formatCurrency(App::getLocale(), $plan->price, 'EUR', '€') }}/@lang('pages/business.month')">@lang('actions.select')</a>
										</div>
									</div>
							</div>
						</div>
					</div>
					@endforeach
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
				<form id="change" class="inline" action="{{ route('subscription.subscribe', App::getLocale()) }}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="planID" id="planID" value="1" />
					<div class="modal-body text-center">
						<h1 class="text-capitalize" id="planName"></h1>
						<p class="text-capitalize" id="planPrice"></p>
						<hr>
						<div id="dropin-container"></div>
					</div>
					<div class="modal-footer text-center">
						 <button id="payment-button" class="btn btn-primary btn-flat hidden" type="submit">@lang('actions.subscribe')</button>
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
			});
		});
		

	</script>
	

@stop