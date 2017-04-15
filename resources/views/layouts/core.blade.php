
<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>@yield('title') - TapToScan.com</title>
    <meta name="description" content="@yield('description')">
	<meta name="keywords" content="@yield('keywords')">
    <meta name="author" content="TapToScan.com">
	<meta name="google-site-verification" content="Zi9jqYtCJDYRupq_Icre00V5Y53gR4qMlAZQmoSG0t0" />
    <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('img/favicon/apple-icon-57x57.png') }}">
	<link rel="apple-touch-icon" sizes="60x60" href="{{ URL::asset('img/favicon/apple-icon-60x60.png') }}">
	<link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('img/favicon/apple-icon-72x72.png') }}">
	<link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('img/favicon/apple-icon-76x76.png') }}">
	<link rel="apple-touch-icon" sizes="114x114" href="{{ URL::asset('img/favicon/apple-icon-114x114.png') }}">
	<link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('img/favicon/apple-icon-120x120.png') }}">
	<link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('img/favicon/apple-icon-144x144.png') }}">
	<link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('img/favicon/apple-icon-152x152.png') }}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('img/favicon/apple-icon-180x180.png') }}">
	<link rel="icon" type="image/png" sizes="192x192"  href="{{ URL::asset('img/favicon/android-icon-192x192.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('img/favicon/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ URL::asset('img/favicon/favicon-96x96.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('img/favicon/favicon-16x16.png') }}">
	<link rel="manifest" href="{{ URL::asset('img/favicon/manifest.json') }}">
	<link rel="alternate" hreflang="en" href="https://taptoscan.com/en" />
	<link rel="alternate" hreflang="hr" href="https://taptoscan.com/hr" />
	<meta name="msapplication-TileColor" content="#4281a4">
	<meta name="msapplication-TileImage" content="{{ URL::asset('img/favicon/ms-icon-144x144.png') }}">
	<meta name="theme-color" content="#4281a4">
	
	

    <!-- Bootstrap core CSS -->
    <!--<link href="../../dist/css/bootstrap.min.css" rel="stylesheet">-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('css/tts.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}">
	
	
	@yield('aos')
	
	

   
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
  </head>

  <body>

	@include('includes.navbar')
	<div class="content">
		@yield('content')
	</div>
	
	@include('includes.footer')
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="{{ URL::asset('js/jquery.cookie.js') }}"></script>
	<script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ URL::asset('js/css3-animate-it.js') }}"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
	<script>
		window.addEventListener("load", function(){
			
			if(!$.cookie('offset')) {
				var offset = new Date().getTimezoneOffset();
				$.cookie('offset', offset);
			}
			//console.log($.cookie('offset'));
			
			
			
			window.cookieconsent.initialise({
			  "palette": {
				"popup": {
				  "background": "#252e39"
				},
				"button": {
				  "background": "#14a7d0"
				}
			  },
			  "position": "top"
			})
			
			if (Notification && Notification.permission !== "granted") {
				Notification.requestPermission();
			}
		});
			
	</script>

	@if (Auth::check())	
		<script src="https://js.pusher.com/4.0/pusher.min.js"></script>
		<script>
			$(document).ready(function() {
				
				Pusher.logToConsole = false;
				
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
				var id = "{{ Auth::id() }}";

				var channel = pusher.subscribe('private-orders_' + id);
				channel.bind('App\\Events\\OrderPlaced', function(data) {
					
					var locale = $('html').attr('lang');
					@if(Request::is('*/home'))
						data = data.message;

						var pOrders = [];
						var pQuantities = [];
						var pPrice = [];
						var pTotals = [];
						var pTotalAmmount = [];

						for (i = 0; i < data['productOrders'].length; i++) { 
							pOrders[i] = data['productOrders'][i]['name'] + '</br>';
							pQuantities[i] = data['productOrders'][i]['quantity'] + '</br>';
							
							pPrice[i] = formatCurrency(locale, data['productOrders'][i]['price'], data['productOrders'][i]['symbol'], data['productOrders'][i]['symbol_code']); 
							pTotals[i] = formatCurrency(locale, (data['productOrders'][i]['price'] * data['productOrders'][i]['quantity']), data['productOrders'][i]['symbol'], data['productOrders'][i]['symbol_code']); 
							
							
							if(!pTotalAmmount[data['productOrders'][i]['symbol_code']]) { 
								pTotalAmmount[data['productOrders'][i]['symbol_code']] = [];
								pTotalAmmount[data['productOrders'][i]['symbol_code']]['price'] = (data['productOrders'][i]['price'] * data['productOrders'][i]['quantity']);
								pTotalAmmount[data['productOrders'][i]['symbol_code']]['formatted'] = formatCurrency(locale, pTotalAmmount[data['productOrders'][i]['symbol_code']]['price'], data['productOrders'][i]['symbol'], data['productOrders'][i]['symbol_code']); 
							} else {
								pTotalAmmount[data['productOrders'][i]['symbol_code']]['price'] += (data['productOrders'][i]['price'] * data['productOrders'][i]['quantity']);
								pTotalAmmount[data['productOrders'][i]['symbol_code']]['formatted'] = formatCurrency(locale, pTotalAmmount[data['productOrders'][i]['symbol_code']]['price'], data['productOrders'][i]['symbol'], data['productOrders'][i]['symbol_code']);
							}
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

						var btn = $('<button class="btn btn-danger btn-lg table-btn">@lang('dashboardHome.notServed')</button>');
						btn.attr('data-id', data['order']['id']);		

						var formatTotalAmmount = '';
						
						for (var key in pTotalAmmount) {
							formatTotalAmmount = formatTotalAmmount + pTotalAmmount[key]['formatted'];
						}
						
						var formatTotals = pTotals.join('') + "<hr class=\"noMargin\"><span class=\"bold\">@lang('dashboardHistory.total')</span></br>" + formatTotalAmmount;
						$('#currentOrders').append(
						$('<tr>')
							.append($('<td>').append(data['table'])) 
							.append($('<td>').append(data['order']['id'])) 
							.append($('<td>').append(pOrders))
							.append($('<td>').append(pPrice)) 
							.append($('<td>').append(pQuantities)) 
							.append($('<td>').append(formatTotals))
							.append($('<td>').append(dateString))
							.append($('<td>').append(btn))
							.hide().fadeIn(250)
						);
						
					@endif
					
					if ($('.badge.notification')[0]){
						$('.badge.notification').each(function() {
							var value = $(this).val();
							alert(value);
							$(this).val($val + 1);
						});
					}
					
					if(Notification) {
						if (Notification.permission !== "granted") {
							Notification.requestPermission();
						}
						else {
							var notification = new Notification("@lang('dashboardHome.notificationTitle')", {
								icon: "{{ URL::asset('img/favicon/logo_158x158.png') }}",
								body: "@lang('dashboardHome.notificationBody')",
							});
						}

						notification.onclick = function () {
							window.open('http://taptoscan.com/' + locale + '/home');      
						}
					}
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
	@endif
	
	@yield('javascript')

  </body>
</html>
