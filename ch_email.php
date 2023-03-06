<?php
	require_once('config/database.php');
	require_once('config/setup.php');
	require_once('extra_validate.php');
	session_start();

	if (!empty($_SESSION['login']))
	{
		$msg = '';
		if (isset($_POST['submit'])) {
			if ($_POST['username'] != $_SESSION['login'])
				$msg = 'Check username.';
			else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
				$msg = 'Incorrect email.';
			else if (!filter_var($_POST['newemail'], FILTER_VALIDATE_EMAIL))
				$msg = 'Incorrect email.';
			else {
				//$username = $_SESSION['login'];
				//$passwd = hash('whirlpool', $_POST['passwd']);
				$id = $_SESSION['id'];
				$username = $_SESSION['login'];
				$email = check_data($_POST['email']);
				$newemail = check_data($_POST['newemail']);

				// Duplicate check
				$dobbel = "SELECT * FROM users WHERE email = :email";
				if ($stmt = $pdo->prepare($dobbel)) {
					$stmt->bindParam(':email', $newemail, PDO::PARAM_STR);
					$stmt->execute();

					if ($stmt->rowCount() == 1) {
						$msg = 'This email is already in use.';
					}
					else {
						$sql = "SELECT email FROM users WHERE id = :id AND username = :username AND email = :email";
						$query = $pdo->prepare($sql);
						$query->bindParam(':username', $username, PDO::PARAM_STR);
						$query->bindParam(':email', $email, PDO::PARAM_STR);
						$query->bindParam(':id', $id, PDO::PARAM_INT);
						$query->execute();

						//$results = $query->fetchAll(PDO::FETCH_OBJ);
						if ($query->rowCount() > 0)
						{
							$new = "UPDATE users SET email = :newemail WHERE id = :id AND username = :username";
							$change = $pdo->prepare($new);
							$change->bindParam(':username', $username, PDO::PARAM_STR);
							$change->bindParam(':newemail', $newemail, PDO::PARAM_STR);
							$change->bindParam(':id', $id, PDO::PARAM_INT);
							$change->execute();
							$msg = 'Email changed.';
						}
						else
							$msg = 'Ooops, something went wrong.';
						unset($change);
					}
					unset($query);
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
	<title>Change email</title>
</head>
<body>
	<header class="head">Camagru</header>
<?php include('navbar.php'); ?>
		<div class="settings">
			<div class="indtop">Change email</div>
			<div class="change">
				<form method="post" action="">
					<input type="text" name="username" placeholder="Username" value="" autocomplete="off" pattern="[a-zA-Z0-9_]+$"><br>
					<input type="email" name="email" placeholder="Email" value="" autocomplete="off"><br>
					<input type="email" name="newemail" placeholder="New email" value="" autocomplete="off"><br>
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
