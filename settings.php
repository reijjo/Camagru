<?php
	require_once('config/database.php');
	require_once('config/setup.php');
	session_start();

	if (!empty($_SESSION['login']))
	{
		$username = $_SESSION['login'];
		$offnotif = '0';
		$onnotif = '1';
		if (isset($_POST['offemailnotif'])) {
			$sqloff = "UPDATE users SET notifications = :notifications WHERE username = :username";
			$off = $pdo->prepare($sqloff);
			$off->bindParam(':username', $username, PDO::PARAM_STR);
			$off->bindParam(':notifications', $offnotif, PDO::PARAM_INT);
			$off->execute();

			$sqloff2 = "UPDATE images SET notifications = :notifications WHERE username = :username";
			$off2 = $pdo->prepare($sqloff2);
			$off2->bindParam(':username', $username, PDO::PARAM_STR);
			$off2->bindParam(':notifications', $offnotif, PDO::PARAM_INT);
			$off2->execute();
		}
		if (isset($_POST['onemailnotif'])) {
			$sqloff = "UPDATE users SET notifications = :notifications WHERE username = :username";
			$on = $pdo->prepare($sqloff);
			$on->bindParam(':username', $username, PDO::PARAM_STR);
			$on->bindParam(':notifications', $onnotif, PDO::PARAM_INT);
			$on->execute();

			$sqloff2 = "UPDATE images SET notifications = :notifications WHERE username = :username";
			$on2 = $pdo->prepare($sqloff2);
			$on2->bindParam(':username', $username, PDO::PARAM_STR);
			$on2->bindParam(':notifications', $onnotif, PDO::PARAM_INT);
			$on2->execute();
		}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="scripts/settings.js"></script>
	<link rel="stylesheet" type="text/css" href="css/settings.css">
	<title>Settings</title>
</head>
<body>
	<header class="head">Camagru</header>
<?php include('navbar.php'); ?>
		<div class="settings">
			<div class="indtop">Settings</div>
			<div class="change">
				<div class="child">
					<table>
						<tr>
							<td>
								<a href="ch_name.php"><input type="submit" name="ch_username" value="Change username"></a>
							</td>
						</tr>
						<tr>
							<td>
								<a href="ch_passwd.php"><input type=submit name="ch_password" value="Change password"></a>
							</td>
						</tr>
						<tr>
							<td>
								<a href="ch_email.php"><input type="submit" name="ch_email" value="Change email"></a>
							</td>
						</tr>
						<tr>
							<td>
								<?php
									$checknotifsql = "SELECT notifications FROM users WHERE username = :username AND notifications = :notifications";
									$checknotif = $pdo->prepare($checknotifsql);
									$checknotif->bindParam(':username', $username, PDO::PARAM_STR);
									$checknotif->bindParam(':notifications', $onnotif, PDO::PARAM_INT);
									$checknotif->execute();
									if ($checknotif->rowCount() > 0) {
										echo '
										<form method="post" class="offform">
											<input type="submit" id="offemailnotif" name="offemailnotif" value="Turn OFF email notifications" onclick="offthat">
										</form>';
									}
									else {
										echo '
										<form method="post" class="offform">
											<input type="submit" id="onemailnotif" name="onemailnotif" value="Turn ON email notifications" onclick="onthat">
										</form>';
									}
								?>
							</td>
						</tr>
					</table>
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
	unset($off);
	unset($off2);
	unset($on);
	unset($on2);
?>
