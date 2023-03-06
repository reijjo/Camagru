<?php
	require_once('config/database.php');
	require_once('config/setup.php');
	require_once('extra_validate.php');

		$msg = $realcode = $code = '';

		if (empty($_GET))
			return (header('location: index.php'));

		$code = $_GET['code'];

		$getcodesql = "SELECT * FROM users WHERE activationcode = :activationcode";
		$getcode = $pdo->prepare($getcodesql);
		$getcode->bindParam(':activationcode', $code, PDO::PARAM_STR);
		$getcode->execute();

		while($row = $getcode->fetch()) {
			$realcode = $row['activationcode'];
		}

		if(!isset($code) || $code == '' || $code !== $realcode)
			header('location: index.php');

		if (isset($_POST['submit'])) {
			if ($code !== $realcode)
				return(header('location: index.php'));
			if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['newpasswd']))
				$msg = '<p>' . 'Password can only contain letters, numbers and underscores.' . '</p>';
			else if (!check_newpassword($_POST['newpasswd']))
				$msg = '<p>' . 'Password must contain at least 1 lowercase letter, 1 uppercase letter, 1 number and length of 8.' . '</p>';
			else if ($_POST['newpasswd'] != $_POST['confnewpasswd'])
				$msg = 'Check passwords.';
			else {
				$username = check_data($_POST['username']);
				$newpasswd = check_data($_POST['newpasswd']);
				$newpasswd = hash('whirlpool', $newpasswd);

				$sql = "SELECT passwd FROM users WHERE username = :username AND activationcode = :activationcode";
				$query = $pdo->prepare($sql);
				$query->bindParam(':username', $username, PDO::PARAM_STR);
				$query->bindParam(':activationcode', $realcode, PDO::PARAM_STR);
				$query->execute();

				if ($query->rowCount() == 1) {
					$new = "UPDATE users SET passwd = :newpasswd WHERE username = :username";
					$change = $pdo->prepare($new);
					$change->bindParam(':username', $username, PDO::PARAM_STR);
					$change->bindParam(':newpasswd', $newpasswd, PDO::PARAM_STR);
					$change->execute();
					$msg = 'Ok!';
					header('refresh: 2; URL="index.php"');
				}
				else {
					$msg = 'Something went wrong.';
				}
				unset($change);
			}
			unset($query);
		}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/settings.css">
	<title>Make yourself a new password</title>
</head>
<body>
	<main>
		<div class="settings">
			<div class="indtop">Forgot your password?</div>
			<div class="change">
				<p class="pass">New password:</p>
				<form method="post" action="">
					<input type="text" name="username" placeholder="Username" value="" autocomplete="off" pattern="[a-zA-Z0-9_]+$"><br>
					<input type="password" name="newpasswd" placeholder="Password" value="" autocomplete="off"><br>
					<input type="password" name="confnewpasswd" placeholder="Confirm password" value="" autocomplete="off"><br>
					<input type="submit" name="submit" value="Ok">
				</form>
				<?php echo '<p style="text-align: center">'.$msg.'</p>'; ?>
			</div>
			<div class="indbot"></div>
		</div>
	</main>
	<footer class="foot">&copy; taitomer</footer>
</body>
</html>
