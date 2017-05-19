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
		<p>Your invoice is ready. You can download it in this email or you can visit <a href="https://taptoscan.com">TapToScan.com</a> and login to your dashboard to view the invoice</p>
		</br>
		<p>Thank you for choosing TapToScan,</p>
		<p>Your TapToScan team!</p>
	</div>

</body>
</html>
