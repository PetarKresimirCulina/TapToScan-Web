<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
	html {
		font-size: 12px;
	}
	.center { text-align: center; }
	.bold { font-weight: 700; }
</style>

<body>
	<div class="center">
		<h1>TapToScan.com</h1>
		<h3>@lang('dashboardBillingHistory.invoice')</h3>
		<p><span class="bold">@lang('dashboardBillingHistory.transactionDate'):</span> 
				@if(!isset($_COOKIE['offset']))
					@php $_COOKIE['offset'] = 0 @endphp
				@endif
								
				@if($_COOKIE['offset'] < 0)
						{{ Carbon\Carbon::parse($data['invoiceDate'])->subMinutes($_COOKIE['offset'])->format("d.m.Y. - H:i:s") }}
				@elseif($_COOKIE['offset'] == 0)
					{{ 	Carbon\Carbon::parse($data['invoiceDate'])->addMinutes($_COOKIE['offset'])->format("d.m.Y. - H:i:s") }}
				@else
					{{ 	Carbon\Carbon::parse($data['invoiceDate'])->format("d.m.Y. - H:i:s") }}
				@endif
		</p>
	</div>
	<hr>
			<p><span class="bold">@lang('dashboardBillingHistory.transactionID'):</span> {{ $data['invoiceId'] }}</p>
			<p><span class="bold">@lang('dashboardBillingHistory.merchant'):</span> {{ $data['merchantAccountId'] }}</p>
			<p><span class="bold">@lang('dashboardBillingHistory.total'):</span> {{ $data['currency'] . ' ' . $data['amount'] }}</p>
			<p><span class="bold">@lang('dashboardBillingHistory.transactionDate'):</span> 
				
				@if(!isset($_COOKIE['offset']))
					@php $_COOKIE['offset'] = 0 @endphp
				@endif
								
				@if($_COOKIE['offset'] < 0)
					{{ Carbon\Carbon::parse($data['invoiceDate'])->subMinutes($_COOKIE['offset'])->format("d.m.Y. - H:i:s") }}
				@elseif($_COOKIE['offset'] == 0)
					{{ 	Carbon\Carbon::parse($data['invoiceDate'])->addMinutes($_COOKIE['offset'])->format("d.m.Y. - H:i:s") }}
				@else
					{{ 	Carbon\Carbon::parse($data['invoiceDate'])->format("d.m.Y. - H:i:s") }}
				@endif
			
			</p>
			<p><span class="bold">@lang('dashboardBillingHistory.tax'):</span> 
			@if($data['tax'] == null)
				@php $data['tax'] = 0.00; @endphp
			@endif
			{{ $data['currency'] . ' ' . number_format($data['tax'], 2, '.', ',') }}</p>
			
			<p><span class="bold">@lang('dashboardBillingHistory.taxExempt'):</span> @lang('actions.no')</p>
			<p><span class="bold">@lang('dashboardBillingHistory.purchaseOrderNumber'):</span> {{ $data['purchaseOrderNumber'] }}</p>
			<p><span class="bold">@lang('dashboardBillingHistory.status'):</span>  {{ $data['status'] }}</p>
			<hr>
			<p><span class="bold">--@lang('dashboardBillingHistory.paymentInformation')--</span></p>
			<hr>
			@if($data['card_brand'] != null)
				<p><span class="bold">@lang('dashboardBillingHistory.paymentType'):</span> {{ $data['card_brand']  }}</p>
				<p><span class="bold">@lang('dashboardBillingHistory.cc'):</span> XXXX-XXXX-XXXX-{{ $data['card_last_four']  }}</p>
			@else
				<p><span class="bold">@lang('dashboardBillingHistory.paymentType'):</span> {{ $data['payerEmail']  }} (Paypal)</p>
			<p><span class="bold">@lang('dashboardBillingHistory.name'):</span> {{ $data['payerFirst'] . ' ' . $data['payerLast'] }}</p>
				<p><span class="bold">@lang('dashboardBillingHistory.paymentId'):</span> {{ $data['paymentId']  }}</p>
			@endif
			
	</div>
	<hr>
</body>	

<footer class="center">
	<p>www.taptoscan.com - @lang('dashboardBillingHistory.generatedAt'): 
	
									@if(!isset($_COOKIE['offset']))
										@php $_COOKIE['offset'] = 0 @endphp
									@endif
							
									@if($_COOKIE['offset'] < 0)
										{{ Carbon\Carbon::now()->subMinutes($_COOKIE['offset'])->format("d.m.Y. - H:i:s") }}
									@elseif($_COOKIE['offset'] == 0)
										{{ 	Carbon\Carbon::now()->addMinutes($_COOKIE['offset'])->format("d.m.Y. - H:i:s") }}
									@else
										{{ 	Carbon\Carbon::now()->format("d.m.Y. - H:i:s") }}
									@endif</p>
</footer>
