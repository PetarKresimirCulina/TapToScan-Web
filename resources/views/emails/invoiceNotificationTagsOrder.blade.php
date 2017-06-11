<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1280">


    <title>TapToScan invoice</title>

</head>
<body>
	
    <div class="container">
		<p>Hello {{ $user->first_name }},</p>
		</br>
		<p>Your order has been received and will be processed soon. Please allow up to 2 business days for the order to be shipped to your address. 
		Your invoice has been generated and you can download it from the email or you can visit <a href="https://taptoscan.com">TapToScan.com</a> and login to your dashboard to view the invoice</p>
		</br>
		<p>Thank you for choosing TapToScan,</p>
		<p>Your TapToScan team!</p>
	</div>

</body>
</html>
