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
			if ($_POST['oldname'] != $_SESSION['login'])
				$msg = 'Check usernames.';
			else if (strlen($_POST['newname']) > 40)
				$msg = '<p>' . 'Username too long.' . '</p>';
			else if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['newname']))
				$msg = '<p>' . 'Username can only contain letters, numbers and underscores.' . '</p>';
			else {
				$username = $_SESSION['login'];
				$newname = check_data($_POST['newname']);
				$passwd = hash('whirlpool', $_POST['passwd']);
				$id = $_SESSION['id'];

				// Duplicate check
				$dobbel = "SELECT * FROM users WHERE username = :username";
				if ($stmt = $pdo->prepare($dobbel)) {
					$stmt->bindParam(':username', $newname, PDO::PARAM_STR);
					$stmt->execute();

					//$namecheck = $stmt->fetchAll(PDO::FETCH_OBJ);
					if ($stmt->rowCount() == 1) {
						$msg = 'This username is already taken.';
					}
					else {
						$sql = "SELECT username FROM users WHERE username = :username AND passwd = :passwd AND id = :id";
						$query = $pdo->prepare($sql);
						$query->bindParam(':username', $username, PDO::PARAM_STR);
						$query->bindParam(':passwd', $passwd, PDO::PARAM_STR);
						$query->bindParam(':id', $id, PDO::PARAM_INT);
						$query->execute();

						$imagestoo = "SELECT username FROM images WHERE username = :username AND userid = :userid";
						$imgquery = $pdo->prepare($imagestoo);
						$imgquery->bindParam(':username', $username, PDO::PARAM_STR);
						$imgquery->bindParam(':userid', $id, PDO::PARAM_INT);
						$imgquery->execute();

						$commentstoo = "SELECT username FROM comments WHERE username = :username AND userid = :userid";
						$comquery = $pdo->prepare($commentstoo);
						$comquery->bindParam(':username', $username, PDO::PARAM_STR);
						$comquery->bindParam(':userid', $id, PDO::PARAM_INT);
						$comquery->execute();

						$likestoo = "SELECT username FROM likes WHERE username = :username AND userid = :userid";
						$likequery = $pdo->prepare($likestoo);
						$likequery->bindParam(':username', $username, PDO::PARAM_STR);
						$likequery->bindParam(':userid', $id, PDO::PARAM_INT);
						$likequery->execute();

						if ($query->rowCount() > 0)
						{
							$new = "UPDATE users SET username = :newname WHERE passwd = :passwd AND id = :id";
							$change = $pdo->prepare($new);
							$change->bindParam(':newname', $newname, PDO::PARAM_STR);
							$change->bindParam(':passwd', $passwd, PDO::PARAM_STR);
							$change->bindParam(':id', $id, PDO::PARAM_INT);
							$change->execute();
							$msg = 'Username changed succesfully.';

							$newimg = "UPDATE images SET username = :newname WHERE userid = :userid";
							$imgchange = $pdo->prepare($newimg);
							$imgchange->bindParam(':newname', $newname, PDO::PARAM_STR);
							$imgchange->bindParam(':userid', $id, PDO::PARAM_STR);
							$imgchange->execute();

							$newcom = "UPDATE comments SET username = :newname WHERE userid = :userid";
							$comchange = $pdo->prepare($newcom);
							$comchange->bindParam(':newname', $newname, PDO::PARAM_STR);
							$comchange->bindParam(':userid', $id, PDO::PARAM_STR);
							$comchange->execute();

							$newlik = "UPDATE likes SET username = :newname WHERE userid = :userid";
							$likechange = $pdo->prepare($newlik);
							$likechange->bindParam(':newname', $newname, PDO::PARAM_STR);
							$likechange->bindParam(':userid', $id, PDO::PARAM_STR);
							$likechange->execute();

							$_SESSION['login'] = $_POST['newname'];
						}
						else
							$msg = 'Something went wrong.';
						unset($change);
						unset($imgchange);
						unset($comchange);
						unset($likechange);
					}
					unset($query);
					unset($imgquery);
					unset($comquery);
					unset($likequery);
				}
			}
		}
		unset($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/settings.css">
	<title>Change username</title>
</head>
<body>
	<header class="head">Camagru</header>
<?php include('navbar.php'); ?>
		<div class="settings">
			<div class="indtop">Change username</div>
			<div class="change">
				<form method="post" action="">
					<input type="text" name="oldname" placeholder="Old username" value="" autocomplete="off" pattern="[a-zA-Z0-9_]+$"><br>
					<input type="text" name="newname" placeholder="New username" value="" autocomplete="off" pattern="[a-zA-Z0-9_]+$"><br>
					<input type="password" name="passwd" placeholder="Password" value="" autocomplete="off"><br>
					<input type="submit" name="submit" value="Confirm">
				</form>
				<div class="msg">
					<p>
					<?php echo $msg; ?>
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
