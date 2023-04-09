<?php
	//error_reporting(0);
	require_once('config/setup.php');
	require_once('config/database.php');
	require_once('extra_validate.php');
	//session_start();

	$msg = '';
	$bonus_msg = '';

	if (isset($_POST['submit'])) {
		if (strlen($_POST['username']) > 40)
			$msg = '<p>' . 'Username too long.' . '</p>';
		else if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username']))
			$msg = '<p>' . 'Username can only contain letters, numbers and underscores.' . '</p>';

		else if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['passwd']))
			$msg = '<p>' . 'Password can only contain letters, numbers and underscores.' . '</p>';
		else if (!check_password($_POST['passwd']))
			$msg = '<p>' . 'Password must contain at least 1 lowercase letter, 1 uppercase letter, 1 number and length of 8.' . '</p>';
		else if ($_POST['confpasswd'] != $_POST['passwd'])
			$msg = 'Check passwords';
		else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			$msg = '<p>' . 'Incorrect email.' . '</p>';

		else {
			$username = check_data($_POST['username']);
			$passwd = check_data($_POST['passwd']);
			$passwd = hash('whirlpool', $passwd);
			$email = check_data($_POST['email']);
			$activationcode = hash('whirlpool', $email.time());
			$sstatus = 0;

			// Duplicates check
			$dobbel = "SELECT * FROM `users` WHERE username = :username OR email = :email";
			if ($stmt = $pdo->prepare($dobbel)) {
				$stmt->bindParam(':username', $username, PDO::PARAM_STR);
				$stmt->bindParam(':email', $email, PDO::PARAM_STR);

				$stmt->execute();
				if ($stmt->rowCount() == 1) {
						$msg = '<p>' . 'This username is already taken.' . '</p>';
					}
					else {
						$sql = "INSERT INTO `users` (username, passwd, email, activationcode, sstatus)
						VALUES (:username, :passwd, :email, :activationcode, :sstatus)";
						$query = $pdo->prepare($sql);
						$query->bindParam(':username', $username, PDO::PARAM_STR);
						$query->bindParam(':passwd', $passwd, PDO::PARAM_STR);
						$query->bindParam(':email', $email, PDO::PARAM_STR);
						$query->bindParam(':activationcode', $activationcode, PDO::PARAM_STR);
						$query->bindParam(':sstatus', $sstatus, PDO::PARAM_STR);
						$query->execute();
					}
					unset($query);
				}
				$lastInsertId = $pdo->lastInsertId();
				if ($lastInsertId) {
					$to = $email;
					$email_msg = "Thanks for registration.";
					$subject = "Email verification";
					$headers = "From: infohelpdeskwhatever@camagru.com" . "\r\n";
					$headers .= "Content-type: text/html;" . "\r\n";

					$ms = "<html><body><div><div> Dear $username, </div><br><br>";
					$ms .= "<div style=\"padding-top: 8px;\"></div>";
					$ms .= "<div style='padding-top:10px;'><a href='http://localhost:3000/email_verification.php?code=$activationcode'>Click Here</a></div>";
					//$ms .= "<div style='padding-top:10px;'><a href='http://localhost:8080/Camagru/email_verification.php?code=$activationcode'>Click Here</a></div>";
					$ms .= "</body></html>";

					mail($to, $subject, $ms, $headers);

					$msg = 'Registration successful, check your email and verify your account';
					header('Refresh: 5; URL="index.php"');
				}
				else {
					$bonus_msg = "Oooops, something went wrong. Try again.";
				}
			}
			unset($lastInsertId);
			unset($stmt);
		}
?>
<!--	HTML STARTS		----------------------------------------------------->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/cama.css">
	<title>Create user</title>
</head>
<body>
	<main>
		<div class="sign">
			<div class="indtop">Create user</div>
			<div class="logform">
				<form method="post" action="">
					<input type="text" name="username" placeholder="Username" value="" autocomplete="off" pattern="[a-zA-Z0-9_]+$"><br>
					<input type="password" name="passwd" placeholder="Password" value="" autocomplete="off" required><br>
					<input type="password" name="confpasswd" placeholder="Confirm password" value="" autocomplete="off" required><br>
					<input type="email" name="email" placeholder="Email" value="" autocomplete="off" required><br>
					<input type="submit" name="submit" value="Register">
				</form>
			</div>
			<div class="msg">
				<p>
				<?php echo $bonus_msg . '<br>'; ?>
				<?php echo $msg; ?>
				</p>
			</div>
			<span class="makeforgot">
				<a href="index.php">Login page</a> <br>
				<a href="forgot_pw.php">Forgot password?</a>
			</span>
			<div class="indbot">&copy; taitomer</div>
			</div>
		</div>
	</main>
</body>
</html>
