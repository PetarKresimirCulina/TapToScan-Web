@extends('layouts.core')
@section('title', __('dashboardHome.title'))
@section('description', __('dashboardHome.description'))
@section('keywords', __('dashboardHome.keywords'))

@section('csrf-meta')
	<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')

	<div class="container-fluid">
	
		<div class="row">
			@include('includes.sidebar')
			
			<div class="col-xs-12 col-lg-10 col-md-9 margin-4">
				@include('includes.emailVerify')
				<h1 class="margin-bottom-2 text-capitalize">@lang('navbar.orders')</h1>
				<div class="table-responsive">
					<table id="currentOrders" class="table table-hover">
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
								@php $i = 0; @endphp
								@foreach ($productOrder as $product)
									
									@if($i>0)
										</br>
									@endif

									{{ $product->formatCurrency(Lang::locale(), $product->pivot->quantity * $product->price) }}
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
							
							<td><button class="btn btn-danger btn-lg table-btn" data-id="{{ $order->id }}">@lang('dashboardHome.notServed')</button></td>
							<!--<button class="btn btn-danger btn-lg table-btn" style="display: none;" data-id="{{ $order->id }}">@lang('dashboardHome.notServed')</button>-->
						</tr>
						@endforeach
					</table>
					<input type="hidden" value="{{ Auth::id() }}" name="auth_id" id="auth_id">
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
					}
				},
				error: function(xhr, status, error) {
					alert(error);
				}
		});
	});


	</script>
	<script src="{{ URL::asset('js/ajax.js') }}"></script>
	
	<script src="https://js.pusher.com/4.0/pusher.min.js"></script>
	<script>
		$(document).ready(function() {
			// Enable pusher logging - don't include this in production
			Pusher.logToConsole = true;
			
			Pusher.log = function(message) {
			if (window.console && window.console.log) {
				window.console.log(message);
			}
			};

			var pusher = new Pusher('999bf93e7a60681c22c9', {
				cluster: 'eu',
				encrypted: true,
				authEndpoint: '{{ route('pusher.auth') }}',
				auth: {
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				}
			});
			var id = $('#auth_id').val();

			var channel = pusher.subscribe('private-orders_' + id);
			channel.bind('App\\Events\\OrderPlaced', function(data) {
				data = data.message;

				var pOrders = [];
				var pQuantities = [];
				var pPrice = [];
				var pTotals = [];
				var locale = $('html').attr('lang');

				for (i = 0; i < data['productOrders'].length; i++) { 
					pOrders[i] = data['productOrders'][i]['name'] + '</br>';
					pQuantities[i] = data['productOrders'][i]['quantity'] + '</br>';
					
					pPrice[i] = formatCurrency(locale, data['productOrders'][i]['price'], data['productOrders'][i]['symbol'], data['productOrders'][i]['symbol_code']); 
					pTotals[i] = formatCurrency(locale, (data['productOrders'][i]['price'] * data['productOrders'][i]['quantity']), data['productOrders'][i]['symbol'], data['productOrders'][i]['symbol_code']); 
					
				}

				var offset = parseInt($.cookie('offset'));

				var time = new Date(data['order']['created_at']);

				if(offset < 0) {
					time.setMinutes(time.getMinutes() - offset);
				}
				else {
					time.setMinutes(time.getMinutes() + offset);
				}

				var year = pad(time.getFullYear()).toString();
				var date = pad(time.getDate()).toString();
				var month = time.getMonth() + 1;
				month = pad(month).toString();
				var hours = pad(time.getHours()).toString();
				var minutes = pad(time.getMinutes()).toString();
				var seconds = pad(time.getSeconds()).toString();

				//format date string
				var dateString = date + '.' + month + '.' + year + ' - ' + hours + ':' + minutes + ':' + seconds;

				$btn = $('<button class="btn btn-danger btn-lg table-btn">@lang('dashboardHome.notServed')</button>');
				$btn.attr('data-id', data['order']['id']);			

				$('#currentOrders').append(
				$('<tr>')
					.append($('<td>').append(data['table'])) 
					.append($('<td>').append(data['order']['id'])) 
					.append($('<td>').append(pOrders))
					.append($('<td>').append(pPrice)) 
					.append($('<td>').append(pQuantities)) 
					.append($('<td>').append(pTotals)) 
					.append($('<td>').append(dateString))
					.append($('<td>').append($btn))
					.hide().fadeIn(250)
				);
			});

			function pad(n) {
				return (n < 10) ? ("0" + n) : n;
			}
			
			function formatCurrency(locale, ammount, symbol, code) {
				
				ammount = parseFloat(ammount).toFixed(2);
				
				switch(locale) {
					case 'en':
						var ammountString = ammount.toString();
						return code + ammountString + '</br>';
					case 'hr':
						var ammountString = ammount.toString();
						ammountString = ammountString.replace(".", ",")
						return ammountString + ' ' + symbol + '</br>';
					default:
						//en
						var ammountString = ammount.toString();
						return code + ammountString + '</br>';
				}
			}
		});
	</script>
@stop