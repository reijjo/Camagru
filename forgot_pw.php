<?php
	require_once('config/database.php');
	require_once('config/setup.php');
	require_once('extra_validate.php');

		$msg = '';

		if (isset($_POST['submit'])) {
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				$msg = '<p>' . 'Incorrect email.' . '</p>';
			else {
				$email = check_data($_POST['email']);


				$check = "SELECT * FROM users WHERE email = :email";
				if ($stmt = $pdo->prepare($check)) {
					$stmt->bindParam(':email', $email, PDO::PARAM_STR);
					$stmt->execute();
					if ($stmt->rowCount() == 1) {
						$row = $stmt->fetch();
						$code = $row['activationcode'];


						$to = $email;
						$email_msg = "-Change your password link.-";
						$subject = "Password stuff";
						$headers = "From: infohelpdeskwhatever@camagru.com" . "\r\n";
						$headers .= "Content-type: text/html;" . "\r\n";

						$ms = "<html><body><div><div> Here you go, </div><br><br>";
						$ms .= "<div style='padding-top: 8px;'><a href='http://localhost:8080/Camagru/forgot_pw2.php?code=$code'>Click Here</a></div>";
						$ms .= "</body></html>";

						mail($to, $subject, $ms, $headers);

						$msg = 'Email sent.';
					}
					else {
						$msg = 'Just a random email address.';
					}
				}
			}
			unset($stmt);
		}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/settings.css">
	<title>Get yourself a password</title>
</head>
<body>
	<main>
		<div class="settings">
			<div class="indtop">Forgot your password?</div>
			<div class="change">
				<p class="pass">Enter your email and we send you a password reinitialisation mail</p>
				<form method="post" action="">
					<input type="email" name="email" placeholder="Email" value="" autocomplete="off"><br>
					<input type="submit" name="submit" value="Send">
				</form>
				<p style="text-align: center;">
				<?php echo $msg; ?>
				</p>
			</div>
			<span class="makeforgot">
				<a href="index.php" title="forgot_pw">Login</a>
				<a href="createuser.php" title="createuser">New user?</a>
			</span>
			<div class="indbot"></div>
		</div>
	</main>
	<footer class="foot">&copy; taitomer</footer>
</body>
</html>
