@extends('layouts.core')
@section('title', __('dashboardOrderTags.title'))
@section('description', __('dashboardOrderTags.description'))
@section('keywords', __('dashboardOrderTags.keywords'))

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
					<li class="active"><a href="{{ route('dashboard.ordertags', App::getLocale()) }}"><i class="material-icons">shopping_cart</i> @lang('dashboardOrderTags.orderTags')</a></li>
					<li><a href="{{ route('dashboard.ordertagsHistory', App::getLocale()) }}"><i class="material-icons">history</i> @lang('dashboardOrderTags.ordersHistory')</a></li>
					
					
				</ul>
				
				
				<div class="col-md-8 col-md-offset-2 margin-4">
				
					<h4 class="text-center">@lang('dashboardOrderTags.formHeading')</h4>
					<hr>
					
					<form id="update-form" action="{{ route('dashboard.ordertagsCheckout', App::getLocale()) }}" method="post">
						{{ csrf_field () }}
						
						<p>@lang('dashboardOrderTags.shippingInfo')</p>
						<hr>
						
						<div class="form-group">
							<label class="text-capitalize" for="personName">@lang('dashboardOrderTags.fullName')</label>
							<input type="text" class="form-control input-lg" name="personName" id="personName" value="{{ Auth::user()->first_name . ' ' .  Auth::user()->last_name}}" required disabled>
						</div>
						
						<div class="form-group">
							<label class="text-capitalize" for="name">@lang('dashboardOrderTags.businessName')</label>
							<input type="text" class="form-control input-lg" name="name" id="name" value="{{ Auth::user()->business_name }}" required disabled>
						</div>
						
						<div class="form-group">
							<label class="text-capitalize" for="address">@lang('dashboardOrderTags.address')</label>
							<input type="text" class="form-control input-lg" name="address" id="address" value="{{ Auth::user()->address }}" required>
						</div>
							
						<div class="form-group">
							<label class="text-capitalize" for="zip">@lang('dashboardOrderTags.zip')</label>
							<input type="number" class="form-control input-lg" name="zip" id="zip" value="{{ Auth::user()->zip }}" required>
						</div>
						
						<div class="form-group">
							<label class="text-capitalize" for="city">@lang('dashboardOrderTags.city')</label>
							<input type="text" class="form-control input-lg" name="city" id="city" value="{{ Auth::user()->city }}" required>
						</div>
						
						<hr>
						
						<p>@lang('dashboardOrderTags.orderDetails')</p>
						<hr>
						<div class="form-group">
							<label class="text-capitalize" for="numOfTags">@lang('dashboardOrderTags.numOfTags')</label>
							<input type="number" class="form-control input-lg" name="numOfTags" id="numOfTags" value="1" min="1" max="100" required>
						</div>
						
						<hr>
						
						<p>@lang('dashboardOrderTags.price'): <span id="price">{{ $price }}</span></p>
						<p>@lang('dashboardOrderTags.shipping'): <span id="shipping">{{ $shipping }}</span></p>
						<p>@lang('dashboardOrderTags.vat') (<span id="taxRate">{{ Auth::user()->taxPercentage() }}</span>%): <span id="tax"></span></p>
						<hr>
						<p class="bold">@lang('dashboardOrderTags.total'): <span id="total"></span></p>
							
						<div class="text-center">
							<button type="submit" class="btn btn-success btn-lg text-uppercase margin-top-2" id="orderBtn"><i class="material-icons">shopping_cart</i> @lang('dashboardOrderTags.order')</button>
						</div>
						<hr>
						<p class="small text-center">@lang('dashboardOrderTags.disclaimer')</p>
						
						
							
					</form>
				</div>
				
			</div>
		</div>
	</div>
	@stop
	
	@section('javascript')

	<script>
		$(document).ready(function() {
			
			/*$( "#numOfTags" ).change(function() {
				alert( "Handler for .change() called." );
			});*/
			
			// on page load
			updatePrices(1);
			// ---
			
			
			$('#numOfTags').on("input", function() {
				
				if($.isNumeric($(this).val())){
					var quantity = parseInt($(this).val());
					
					if(quantity > 0) {
						updatePrices(quantity);
					}
					
				}
				
				
			});
			
			$('form').submit(function() {
				$(this).find("button[type='submit']").prop('disabled',true);
			});
			
			function updatePrices(quantity) {
				var price = ({{ $price }}  * quantity).toFixed(2);
				$('#price').text(formatCurrency('{{ App::getLocale() }}', price, '{{ $code }}', '{{ $symbol }}'));
							
				var shipping = parseFloat({{ $shipping }} ).toFixed(2);
				$('#shipping').text(formatCurrency('{{ App::getLocale() }}', shipping, '{{ $code }}', '{{ $symbol }}'));
				var taxRate = parseInt($('#taxRate').text());
				
				var tax = ((Number(shipping) + Number(price))*(Number(taxRate)/100)).toFixed(2);
							
				var total = (Number(shipping) + Number(price) + Number(tax)).toFixed(2);
				$('#tax').text(formatCurrency('{{ App::getLocale() }}', tax, '{{ $code }}', '{{ $symbol }}'));
				$('#total').text(formatCurrency('{{ App::getLocale() }}', total, '{{ $code }}', '{{ $symbol }}'));
			}
			
			
			function formatCurrency(locale, value, code, symbol) {
					switch (locale) {
						case 'en':
							return code + value.replace(',', '.');
							break;
						case 'hr':
							return value.replace('.', ',') + symbol;
							break;
						default:
							//en
							return code + value.replace(',', '.');
							break;
					}
			}
			
			
		});
	</script>
@stop