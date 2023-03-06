<?php
	//error_reporting(0);
	require_once('config/database.php');
	require_once('config/setup.php');
	require_once('extra_validate.php');

	session_start();

	$id = $login = $msg = $action1 = '';

	if (isset($_POST['submit'])) {
		$username = check_data($_POST['login']);
		$passwd = check_data($_POST['passwd']);
		$passwd = hash('whirlpool', $passwd);
		$sstatus = 1;

		$sql = "SELECT * FROM `users` WHERE username = :username AND `passwd` = :passwd AND `sstatus` = :sstatus";
		$query = $pdo->prepare($sql);
		$query->bindParam(':username', $username, PDO::PARAM_STR);
		$query->bindParam(':passwd', $passwd, PDO::PARAM_STR);
		$query->bindParam(':sstatus', $sstatus, PDO::PARAM_INT);
		$query->execute();

		$results = $query->fetchAll(PDO::FETCH_OBJ);
		if ($query->rowCount() > 0 && $sstatus = 1)
		{
			foreach($results as $row) {
				$name = $row->username;
				$id = $row->id;
			}
			$_SESSION['login'] = $name;
			$_SESSION['id'] = $id;
			$msg = 'Wohoo! Logged in.';
			header('refresh: 1; URL="userpage.php"');
		}
		else {
			$msg = 'Invalid details.';
		}
	}
	unset($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/cama.css">
	<title>Camagru</title>
</head>
<body>
	<main>
		<div class="sign">
			<div class="indtop">Camagru</div>
			<div class="logform">
				<form method="post" action="index.php">
					<input type="text" name="login" placeholder="Username" size="20" value="" autocomplete="off" pattern="[a-zA-Z0-9_]+$"> <br>
					<input type="password" name="passwd" placeholder="Password" size="20" value="" autocomplete="off"> <br>
					<input type="submit" name="submit" value="Login">
				</form>
			</div>
			<div class="msg">
				<p>
					<?php echo htmlentities($msg) ?>
				</p>
			</div>
			<span class="makeforgot">
				<a href="forgot_pw.php" title="forgot_pw">Forgot password?</a>
				<a href="createuser.php" title="createuser">New user?</a>
			</span>
			<div class="indbot">&copy; taitomer</div>
		</div>
		<div class="extra">Click
			<a href="sneakpeak.php" title="check">here</a>
			to see what Camagru looks like.
		</div>
	</main>
</body>
</html>
