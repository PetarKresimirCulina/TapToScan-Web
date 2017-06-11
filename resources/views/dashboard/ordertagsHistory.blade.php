@extends('layouts.core')
@section('title', __('dashboardOrderTagsHistory.title'))
@section('description', __('dashboardOrderTagsHistory.description'))
@section('keywords', __('dashboardOrderTagsHistory.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				<h1 class="margin-bottom-2 text-capitalize">@lang('navbar.order')</h1>
				
				@include('includes.alerts')
				@include('includes.blocked')
				@include('includes.noTags')
				
				<ul class="nav nav-tabs text-capitalize">
					<li><a href="{{ route('dashboard.ordertags', App::getLocale()) }}"><i class="material-icons">shopping_cart</i> @lang('dashboardOrderTagsHistory.orderTags')</a></li>
					<li class="active"><a href="{{ route('dashboard.ordertagsHistory', App::getLocale()) }}"><i class="material-icons">history</i> @lang('dashboardOrderTagsHistory.ordersHistory')</a></li>
					
				</ul>
				
				
				<div class="table-responsive">
				
					<table class="table table-hover table-responsive">
					<thead>
						<tr class="text-capitalize">
							<th>@lang('dashboardOrderTagsHistory.number')</th>
							<th>@lang('dashboardOrderTagsHistory.orderDate')</th> 
							<th>@lang('dashboardOrderTagsHistory.lastUpdate')</th>
							<th>@lang('dashboardOrderTagsHistory.quantity')</th>
							<th>@lang('dashboardOrderTagsHistory.total')</th>
							<th>@lang('dashboardOrderTagsHistory.invoiceID')</th>
							<th>@lang('dashboardOrderTagsHistory.trackingID')</th>
							<th>@lang('dashboardOrderTagsHistory.status')</th>
						</tr>
						
					</thead>
					
					<tbody>
						@php $i = 1; @endphp
						@php $currencyDummy = new \App\Currency() @endphp
						@foreach ($orders as $order)
						<tr>
							<td>{{ $i }}.</td>
							<td>{{ $order->created_at->toFormattedDateString() }}</td> 
							<td>{{ $order->updated_at->toFormattedDateString() }}</td>
							
							@php $currency = $order->invoice->getCurrency @endphp
							
							<td>{{ $order->quantity }} @lang('dashboardOrderTagsHistory.pcs')</td>
							<td>{{ $currencyDummy->formatCurrency(App::getLocale(), $order->invoice->totalWVat, $currency->code, $currency->symbol) }}</td>
							
							<td>{{ $order->invoice->id . '/' . sprintf( '%02d', $order->invoice->saleVenue ) . '/' . sprintf( '%02d', $order->invoice->saleOperator) }}</td>
							<td>@if($order->tracking_id == null)
									N/A
								@else
									{{ $order->tracking_id }}
								@endif
							</td>
							<td>@if($order->shipped == 0)
									<span class="text-danger"><i class="material-icons">autorenew</i> @lang('dashboardOrderTagsHistory.processing')</span>
								@else
									<span class="text-success"><i class="material-icons">check_circle</i> @lang('dashboardOrderTagsHistory.shipped')</span>
								@endif
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
	@stop