@extends('layouts.core')
@section('title', __('dashboardHistory.title'))
@section('description', __('dashboardHistory.description'))
@section('keywords', __('dashboardHistory.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-lg-10 col-md-9 margin-4">
				@include('includes.emailVerify')
				<h1 class="margin-bottom-2 text-capitalize">@lang('navbar.history')</h1>
				<div class="btn-group margin-bottom-2">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $period }} <span class="caret"></span>
					</button>
					
					<ul class="dropdown-menu text-capitalize">
						<li><a href="{{ '/' . App::getLocale() }}/history?period=today">@lang('dashboardHistory.today')</a></li>
						<li><a href="{{ '/' . App::getLocale() }}/history?period=30">@lang('dashboardHistory.30days')</a></li>
						<li><a href="{{ '/' . App::getLocale() }}/history?period=90">@lang('dashboardHistory.90days')</a></li>
						<li><a href="{{ '/' . App::getLocale() }}/history?period=all">@lang('dashboardHistory.allTime')</a></li>
						
					</ul>
				</div>
				
				<div class="table-responsive">
					<table class="table table-hover">
						<tr class="text-capitalize">
							<th>@lang('dashboardHistory.table')</th>
							<th>@lang('dashboardHistory.orderNo')</th> 
							<th>@lang('dashboardHistory.product')</th>
							<th>@lang('dashboardHistory.price')</th>
							<th>@lang('dashboardHistory.quantity')</th>
							<th>@lang('dashboardHistory.total')</th>
							<th>@lang('dashboardHistory.datetime')</th>
						</tr>
						@foreach($orders as $order)
						<tr>
							<td>{{ $order->getTableName($order->tagID) }}</td>
							<td>{{ $order->id }}</td> 
							
							@php $prodOrdersValue = $order->products @endphp
							<td>
								@php $i = 0; @endphp
								@foreach ($prodOrdersValue as $product)
									
									@if($i>0)
										</br>
									@endif
									
									{{ $product->name }}
									@php $i+=1; @endphp
									
								@endforeach							
							</td>
							
							<td>
								@php $i = 0; @endphp
								@foreach ($prodOrdersValue as $product)
									
									@if($i>0)
										</br>
									@endif
									
									{{ $product->formatCurrency(Lang::locale(), $product->price) }}
									@php $i+=1; @endphp
									
								@endforeach	
							</td>
							<td>
								@php $i = 0; @endphp
								@foreach ($prodOrdersValue as $product)
									
									@if($i>0)
										</br>
									@endif
									
									{{ $product->pivot->quantity }}
									@php $i+=1; @endphp
									
								@endforeach			
							
							</td>
							<td>
								@php $i = 0; @endphp
								@foreach ($prodOrdersValue as $product)
									
									@if($i>0)
										</br>
									@endif
									
									{{ $product->formatCurrency(Lang::locale(), ($product->pivot->quantity * $product->price)) }}
									@php $i+=1; @endphp
									
								@endforeach			
							</td>
							<td>
									@if(!isset($_COOKIE['offset']))
										@php $_COOKIE['offset'] = 0 @endphp
									@endif
							
									@if($_COOKIE['offset'] < 0)
										{{ $order->created_at->subMinutes($_COOKIE['offset'])->format("d.m.Y. - H:i:s") }}
									@elseif($_COOKIE['offset'] == 0)
										{{ 	$order->created_at->addMinutes($_COOKIE['offset'])->format("d.m.Y. - H:i:s") }}
									@else
										{{ 	$order->created_at->format("d.m.Y. - H:i:s") }}
									@endif
							</td>
						</tr>
						@endforeach
					</table>
				</div>
				<div class="pagination"> {{ $orders->appends(Request::except('page'))->links() }} </div>
			</div>
		</div>
	</div>
@stop