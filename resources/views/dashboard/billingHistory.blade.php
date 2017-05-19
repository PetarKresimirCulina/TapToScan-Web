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
					<thead>
						<tr class="text-capitalize">
							<th>@lang('dashboardBillingHistory.invoiceID')</th>
							<th>@lang('dashboardBillingHistory.date')</th> 
							<th>@lang('dashboardBillingHistory.amount')</th>
							<th></th>
					

						</tr>
						
					</thead>
					
					<tbody>
						@php $i = 0; @endphp
						@php $currencyDummy = \App\Currency::where('id', Auth::user()->plan->currency)->first(); @endphp
						@foreach ($invoices as $invoice)
						<tr>
							<td>{{ $invoice->id . '/' . sprintf( '%02d', $invoice->saleVenue ) . '/' . sprintf( '%02d', $invoice->saleOperator) }}</td>
							<td>{{ $invoice->created_at->toFormattedDateString() }}</td> 
							<td>{{ $currencyDummy->formatCurrency(App::getLocale(), $invoice->totalWVat, $invoice->getCurrency->code, $invoice->getCurrency->symbol) }}</td>

							
							<td>
								<form id="generatePDF{{ $i }}" action="{{ route('dashboard.billing.invoice', App::getLocale())  }}" method="post">
									{{ csrf_field() }}
									<input name="invoiceId" type="hidden" value="{{ $invoice->id }}">
									<a href="javascript:{}" onclick="document.getElementById('generatePDF{{ $i }}').submit(); return false;">@lang('dashboardBillingHistory.download')</a>
								
								</form>
							</td>
						</tr>
						@php $i++ @endphp
						@endforeach
						</tbody>
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
