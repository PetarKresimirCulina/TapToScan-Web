@extends('layouts.core')
@section('title', __('dashboardHome.title'))
@section('description', __('dashboardHome.description'))
@section('keywords', __('dashboardHome.keywords'))

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-sm-9 col-md-10 margin-4">
				<h1 class="margin-bottom-2 text-capitalize">@lang('navbar.orders')</h1>
				
				@include('includes.alerts')
				@include('includes.blocked')
				
				<div class="table-responsive">
				
					<table id="currentOrders" class="table table-hover table-responsive">
						<tr class="text-capitalize">
							<th>@lang('dashboardHome.table')</th>
							<th>@lang('dashboardHome.orderNo')</th> 
							<th>@lang('dashboardHome.product')</th>
							<th>@lang('dashboardHome.price')</th>
							<th>@lang('dashboardHome.quantity')</th>
							<th>@lang('dashboardHome.total')</th>
							<th>@lang('dashboardHome.datetime')</th>
							<th>@lang('dashboardHome.status')</th>
						</tr>
						
						@foreach($orders as $order)
						<tr>
							@php $productOrder = $order->products @endphp
							<td>{{ $order->getTableName($order->tagID) }}</td>
							<td>{{ $order->id }}</td> 
							<td>
								@php $i = 0; @endphp
								@foreach ($productOrder as $product)
									
									@if($i>0)
										</br>
									@endif
									
									{{ $product->name }}
									@php $i+=1; @endphp
									
								@endforeach							
							</td>
							<td>
								@php $i = 0; @endphp
								@foreach ($productOrder as $product)
									
									@if($i>0)
										</br>
									@endif
									
									{{ $product->formatCurrency(Lang::locale(), $product->price) }}
									@php $i+=1; @endphp
									
								@endforeach		
							</td>
							<td>
								@php $i = 0; @endphp
								@foreach ($productOrder as $product)
									
									@if($i>0)
										</br>
									@endif
									
									{{ $product->pivot->quantity }}
									@php $i+=1; @endphp
									
								@endforeach			
							
							</td>
							
							<td>
								@php 
									$i = 0;
									$totalArray = array();
								@endphp
								
								@foreach ($productOrder as $product)
									
									@if($i>0)
										</br>
									@endif
									
									@php $price = $product->pivot->quantity * $product->price; @endphp
									
									{{ $product->formatCurrency(Lang::locale(), $price) }}

									@php 
										if(isset($totalArray[$product->currency->code])) {
											$totalArray[$product->currency->code]['price'] += $price;
											$totalArray[$product->currency->code]['formatted'] = $product->formatCurrency(Lang::locale(), $totalArray[$product->currency->code]['price']);
										} else {
											$totalArray[$product->currency->code]['price'] = $price;
											$totalArray[$product->currency->code]['formatted'] = $product->formatCurrency(Lang::locale(), $totalArray[$product->currency->code]['price']);
										}
										$i+=1; 
									@endphp
									
								@endforeach		
								
								<hr class="noMargin">
								<span class="bold">@lang('dashboardHistory.total')</span></br>
								
								@php $j = 0; @endphp
								@foreach ($totalArray as $currency)
									@if($j>0)
										</br>
									@endif
									
									{{ $currency['formatted'] }}
									
									@php $j+=1; @endphp
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
							
							<td><button class="btn btn-danger btn-lg table-btn" data-id="{{ $order->id }}">@lang('dashboardHome.notServed')</button></td>
							<!--<button class="btn btn-danger btn-lg table-btn" style="display: none;" data-id="{{ $order->id }}">@lang('dashboardHome.notServed')</button>-->
						</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
@stop

@section('javascript')
<script>

	$(document).on('click','.table-btn',function(){
    var btn = $(this);
		if($(btn).data("requestRunning") ) {
			return;
		}
		$(btn).data("requestRunning", true);
			
		$.ajax({
			type: "POST",
			url: "{{ route('home.served', App::getLocale()) }}",
			data: { "id": $(this).data("id") },
				success: function(data ){
					if(data == "Success")
					{
						var w = $(btn).width();
						$(btn).addClass("btn-success").removeClass("btn-danger").text("@lang('dashboardHome.served')").width(w).delay(250).queue(function(){
								$(btn).closest("tr").fadeOut(250, function(){ $(this).remove();
							});
						});
						if ($('.badge.notification')[0]){
							$('.badge.notification').each(function() {
								var value = $(this).html();

								$(this).html(parseInt(value) - 1);
								if((parseInt(value) - 1) < 1) {
									$(this).hide();
								}
							});
						}
					}
				},
				error: function(xhr, status, error) {
					alert(error);
				}
		});
	});


	</script>
	<script src="{{ URL::asset('js/ajax.js') }}"></script>
@stop