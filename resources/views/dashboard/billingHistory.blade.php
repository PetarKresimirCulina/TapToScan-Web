@extends('layouts.core')
@section('title', __('dashboardBillingHistory.title'))
@section('description', __('dashboardBillingHistory.description'))
@section('keywords', __('dashboardBillingHistory.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				<h1 class="margin-bottom-2 text-capitalize">@lang('dashboardBillingHistory.title')</h1>
				
				@include('includes.alerts')
				@include('includes.blocked')
				@include('includes.noTags')
				
				<ul class="nav nav-tabs text-capitalize">
					<li><a href="{{ route('dashboard.billing', App::getLocale()) }}"><i class="material-icons">payment</i> @lang('dashboardBilling.subscriptionSettings')</a></li>
					<li class="active"><a href="{{ route('dashboard.billing.history', App::getLocale()) }}"><i class="material-icons">history</i> @lang('dashboardBilling.paymentHistory')</a></li>
				</ul>
				
				<div class="table-responsive">
				
					<table class="table table-hover table-responsive">
						<tr class="text-capitalize">
							<th>@lang('dashboardBillingHistory.invoiceID')</th>
							<th>@lang('dashboardBillingHistory.date')</th> 
							<th>@lang('dashboardBillingHistory.amount')</th>
							<th></th>

						</tr>
						@php $i = 0; @endphp
						@foreach ($invoices as $invoice)
						<tr>
							<td>{{ $invoice->id }}</td>
							<td>{{ $invoice->date()->toFormattedDateString() }}</td> 
							<td>{{ $invoice->total() }}</td> 
							
							<td>
								<form id="generatePDF{{ $i }}" action="{{ route('dashboard.billing.invoice', App::getLocale())  }}" method="post">
									{{ csrf_field() }}
									<input type="hidden" value="{{ $invoice->id }}" name="invoiceId"/>
									<input type="hidden" value="{{ $invoice->merchantAccountId }}" name="merchantAccountId"/>
									<input type="hidden" value="{{ $invoice->amount }}" name="amount"/>
									<input type="hidden" value="{{ $invoice->currencyIsoCode }}" name="currency"/>
									<input type="hidden" value="{{ $invoice->taxAmount }}" name="tax"/>
									<input type="hidden" value="{{ $invoice->purchaseOrderNumber }}" name="purchaseOrderNumber"/>
									<input type="hidden" value="{{ $invoice->orderId }}" name="orderId"/>
									<input type="hidden" value="{{ $invoice->status }}" name="status"/>
									

										@if($invoice->paymentInstrumentType == 'credit_card')
											@php $cc = $invoice->creditCardDetails @endphp
											
											<input type="hidden" value="{{ $cc->last4 }}" name="card_last_four"/>
											<input type="hidden" value="{{ $cc->cardType }}" name="card_brand"/>
											<input type="hidden" value="{{ $cc->cardholderName }}" name="cardholderName"/>
										@endif
											
										@if($invoice->paymentInstrumentType == 'paypal_account')
											@php $pp = $invoice->paypalDetails @endphp
											<input type="hidden" value="{{ $pp->payerEmail }}" name="payerEmail"/>
											<input type="hidden" value="{{ $pp->paymentId }}" name="paymentId"/>
											<input type="hidden" value="{{ $pp->payerFirstName }}" name="payerFirst"/>
											<input type="hidden" value="{{ $pp->payerLastName }}" name="payerLast"/>
										@endif
									
									<input type="hidden" value="{{ $invoice->date() }}" name="invoiceDate"/>
									<a href="javascript:{}" onclick="document.getElementById('generatePDF{{ $i }}').submit(); return false;">@lang('dashboardBillingHistory.download')</a>
								</form>
							</td>
						</tr>
						@php $i++ @endphp
						@endforeach
					</table>
				</div>
			</div>
		</div>
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
