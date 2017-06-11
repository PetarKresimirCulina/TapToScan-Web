@extends('layouts.core')
@section('title', __('dashboardOrders.title'))
@section('description', __('dashboardOrders.description'))
@section('keywords', __('dashboardOrders.keywords'))


@section('content')

	<div class="container-fluid">
	
		<div class="row flex">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
			
				<h1 class="margin-bottom-2 text-capitalize">@lang('navbar.tagOrders')</h1>
				
				@include('includes.alerts')

				<div class="table-responsive">
					<table id="currentUsers" class="table table-condensed table-hover" style="border-collapse:collapse;">
						<thead>
							<tr class="text-capitalize">
								<th>@lang('dashboardOrders.number')</th>
								<th>@lang('dashboardOrders.orderDate')</th> 
								<th>@lang('dashboardOrders.lastUpdate')</th>
								<th>@lang('dashboardOrders.quantity')</th>
								<th>@lang('dashboardOrders.total')</th>
								<th>@lang('dashboardOrders.invoiceID')</th>
								<th>@lang('dashboardOrders.status')</th>
								<th class="text-center">@lang('dashboardOrders.actions')</th>
							</tr>
						</thead>
						<tbody>
								
							@php $i = 1; @endphp
							@php $currencyDummy = new \App\Currency() @endphp
							@foreach ($orders as $order)
							
							<tr data-toggle="collapse" data-target="#demo{{ $order->id }}" class="accordion-toggle">								
								<td>{{ $i }}.</td>
								<td>{{ $order->created_at->toFormattedDateString() }}</td> 
								<td>{{ $order->updated_at->toFormattedDateString() }}</td>
								
								@php $currency = $order->invoice->getCurrency @endphp
								
								<td>{{ $order->quantity }} @lang('dashboardOrders.pcs')</td>
								<td>{{ $currencyDummy->formatCurrency(App::getLocale(), $order->invoice->totalWVat, $currency->code, $currency->symbol) }}</td>
								
								<td>{{ $order->invoice->id . '/' . sprintf( '%02d', $order->invoice->saleVenue ) . '/' . sprintf( '%02d', $order->invoice->saleOperator) }}</td>
								<td>@if($order->shipped == 0)
										<span class="text-danger"><i class="material-icons">autorenew</i> @lang('dashboardOrders.notShipped')</span>
									@else
										<span class="text-success"><i class="material-icons">check_circle</i> @lang('dashboardOrders.shipped')</span>
									@endif
								</td>
								<td class="text-center">
									@if($order->shipped == 0)
										<a href="#" class="btn btn-success btn-shipped" data-id="{{ $order->id }}"><i class="material-icons">check_circle</i> @lang('dashboardOrders.markAsShipped')</a>
									@else
										-
									@endif
								</td>
							</tr>
							
							<tr>
								<td colspan="8" class="hiddenRow">
									<div class="accordian-body collapse" id="demo{{ $order->id }}">
										<div class="container">
											<div class="col-xs-5 col-xs-offset-1">
												<p class="small"><span class="bold">@lang('dashboardOrders.fullName'): </span>{{ $order->shipping_name }}</p>
												<p class="small"><span class="bold">@lang('dashboardOrders.address'): </span>{{ $order->shipping_address }}</p>
												<p class="small"><span class="bold">@lang('dashboardOrders.zip'): </span>{{ $order->shipping_zip }}</p>
												<p class="small"><span class="bold">@lang('dashboardOrders.city'): </span>{{ $order->shipping_city }}</p>
												<p class="small"><span class="bold">@lang('dashboardOrders.country'): </span>{{ $order->shipping_country }}</p>
											</div>
											<div class="col-xs-5">
												<p class="small"><span class="bold">@lang('dashboardOrders.company'): </span>{{ \App\User::where('id', $order->user_id)->first()->legal_name }}</p>
												<p class="small"><span class="bold">@lang('dashboardOrders.orderedQuantity'): </span>{{ $order->quantity }}</p>
												<p class="small"><span class="bold">@lang('dashboardOrders.paid'): </span>	@if($order->paid == 1)
																										@lang('actions.yes')
																									@else
																										@lang('actions.no')
																									@endif
												</p>
												<p class="small"><span class="bold">@lang('dashboardOrders.trackingID'): </span><span>{{ $order->tracking_id }}</p>
												<p class="small"><span class="bold">@lang('dashboardOrders.invoiceID'): </span>{{ $order->invoice->id . '/' . sprintf( '%02d', $order->invoice->saleVenue ) . '/' . sprintf( '%02d', $order->invoice->saleOperator) }}</p>
											</div>
										</div>
									</div>
								</td>
							</tr>
							@php $i++ @endphp
						@endforeach
						</tbody>
					</table>
				</div>
				<div class="pagination"> {{ $orders->appends(Request::except('page'))->links() }} </div>
			</div>
			
		</div>
	</div>
	
	
	<div id="markShipped" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-capitalize">@lang('dashboardOrders.markAsShipped')</h4>
				</div>
				<div class="modal-body">
					<form id="addTable" action="{{ route('dashboard.tagOrdersManagementShipped', App::getLocale()) }}" method="post">
					{{ csrf_field () }}
						<div class="form-group">
							<label for="tracking">@lang('dashboardOrders.packageTrackingNo')</label>
							<input type="text" class="form-control input-lg" autofocus placeholder="RB267096010HR " name="tracking" id="tracking">
						</div>
						
						<input type="hidden" id="orderID" name="orderID" value="2">
						
						<div class="row text-center">
							<button type="submit" class="btn btn-success btn-lg text-uppercase margin-top-2">@lang('dashboardOrders.markAsShipped')</button>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">@lang('actions.close')</button>
				</div>
			</div>
		</div>
	</div>
	
@stop
	
	
@section('javascript')

<script>
	
	$(document).ready(function() {
		
		$(".btn-shipped").click(function(e) {
			e.stopPropagation();
			e.preventDefault();
			$('#orderID').val($(this).data('id'));
			$('#markShipped').modal('show');
		});
		
	});
</script>

		@stop