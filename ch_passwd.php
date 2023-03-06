<?php
	require_once('config/database.php');
	require_once('config/setup.php');
	require_once('extra_validate.php');
	session_start();

	if (!empty($_SESSION['login']))
	{
		$msg = '';

		if (isset($_POST['submit']))
		{
			if ($_POST['username'] != $_SESSION['login'])
				$msg = 'Check username.';
			else if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['newpw']))
				$msg = 'Password can only contain letters, numbers and underscores.';
			else if (!check_password($_POST['newpw']))
				$msg = 'Password must contain at least 1 lowercase letter, 1 uppercase letter, 1 number and length of 8.';
			else if ($_POST['confnewpw'] != $_POST['newpw'])
				$msg = 'Check passwords.';
			else {
				$id = $_SESSION['id'];
				$username = $_SESSION['login'];
				$passwd = check_data($_POST['passwd']);
				$passwd = hash('whirlpool', $passwd);
				$newpw = hash('whirlpool', $_POST['newpw']);

				$sql = "SELECT passwd FROM users WHERE id = :id AND username = :username AND passwd = :passwd";
				$query = $pdo->prepare($sql);
				$query->bindParam(':id', $id, PDO::PARAM_INT);
				$query->bindParam(':username', $username, PDO::PARAM_STR);
				$query->bindParam(':passwd', $passwd, PDO::PARAM_STR);
				$query->execute();

				//$results = $query->fetchAll(PDO::FETCH_OBJ);
				if ($query->rowCount() > 0)
				{
					$new = "UPDATE users SET passwd = :newpw WHERE username = :username AND id = :id";
					$change = $pdo->prepare($new);
					$change->bindParam(':id', $id, PDO::PARAM_INT);
					$change->bindParam(':username', $username, PDO::PARAM_STR);
					$change->bindParam(':newpw', $newpw, PDO::PARAM_STR);
					$change->execute();
					$msg = 'Password changed succesfully.';
				}
				else {
					$msg = 'Ooops, something went wrong. Try again.';
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
	<title>Change password</title>
</head>
<body>
	<header class="head">Camagru</header>
<?php include('navbar.php'); ?>
		<div class="settings">
			<div class="indtop">Change password</div>
			<div class="change">
				<form method="post" action="">
					<input type="text" name="username" placeholder="Username" value="" autocomplete="off" pattern="[a-zA-Z0-9_]+$"><br>
					<input type="password" name="passwd" placeholder="Password" value="" autocomplete="off"><br>
					<input type="password" name="newpw" placeholder="New password" value="" autocomplete="off"><br>
					<input type="password" name="confnewpw" placeholder="Confirm new password" value="" autocomplete="off"><br>
					<input type="submit" name="submit" value="Confirm">
				</form>
				<div class="msg">
					<p>
						<?php echo htmlentities($msg); ?>
					</p>
				</div>
			</div>
			<div class="indbot"></div>
		</div>
	</main>
	<footer class="foot">&copy; taitomer</footer>
</body>
</html>

<?php
	}
	else {
		header('location: logout.php');
	}
?>
