<!DOCTYPE html>
<head>
  <title>Pusher Test</title>

	<meta name="csrf-token" content="{{ csrf_token() }}">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://js.pusher.com/4.0/pusher.min.js"></script>
  
</head>
<body>

	

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
				auth: {
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				}
			});
			var id = $('#auth_id').val();

			var channel = pusher.subscribe('private-orders_' + id);
			channel.bind('OrderPlaced', function(data) {
				console.log(data);
			});
		});
	</script>
</body>