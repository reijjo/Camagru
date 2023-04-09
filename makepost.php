<?php
	require_once('config/database.php');
	require_once('config/setup.php');
//	error_reporting(0);
	session_start();

	$msg = '';

	if (!empty($_SESSION['login']))
	{
		$username = $_SESSION['login'];

		$getnotifsql = "SELECT * FROM users WHERE username = :username";
		$getnotif = $pdo->prepare($getnotifsql);
		$getnotif->bindParam(':username', $username, PDO::PARAM_STR);
		$getnotif->execute();
		$notif = $getnotif->fetch();
		$notid = $notif['notifications'];

		$sql = "SELECT * FROM images ORDER BY id DESC LIMIT 1";
		$getthat = $pdo->prepare($sql);
		$getthat->execute();
		$result = $getthat->fetch();

		$foto_id = $result['id'];
		$name = $result['username'];
		$fotoname = $result['foto_name'];
		$foto = $result['foto'];
		$foto_notif = $result['notifications'];

		if (isset($_POST['post']))
		{
			if (strlen($_POST['desc']) > 250)
				$msg = 'Description too long.';
			else if (trim(strlen($_POST['desc'])) < 1)
				$msg = 'No empty descriptions thanks.';
			else {
				$new_fotoname = htmlentities($_POST['desc']);

				$upd = "SELECT foto_name FROM images WHERE username = :username AND id = :id AND foto_name = :foto_name AND notifications = :notifications";
				$prep = $pdo->prepare($upd);
				$prep->bindParam(':username', $name, PDO::PARAM_STR);
				$prep->bindParam(':id', $foto_id, PDO::PARAM_INT);
				$prep->bindParam('foto_name', $fotoname, PDO::PARAM_STR);
				$prep->bindParam(':notifications', $notif, PDO::PARAM_INT);

				$prep->execute();

				if ($prep->rowCount() == 1) {
					$newname = "UPDATE images SET foto_name = :new_fotoname, notifications = :upnotif WHERE id = :id AND username = :username";
					$update = $pdo->prepare($newname);
					$update->bindParam(':username', $name, PDO::PARAM_STR);
					$update->bindParam(':id', $foto_id, PDO::PARAM_STR);
					$update->bindParam(':new_fotoname', $new_fotoname, PDO::PARAM_STR);
					$update->bindParam(':upnotif', $notid, PDO::PARAM_INT);

					$update->execute();

					header('Refresh: 0; URL="feed.php"');
				}
				unset($update);
			}
		}
		else if (isset($_POST['delete'])) {
			$delsql = "DELETE FROM images WHERE id = :id";
			$remove = $pdo->prepare($delsql);
			$remove->bindParam(':id', $foto_id, PDO::PARAM_STR);
			$remove->execute();

			header('location: userpage.php');
		}
		unset($prep);
		unset($remove);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script>
		function emptycheck() {
			var img_ch = document.getElementById('imgid');
			var src_ch = img_ch.getAttribute('src');
			if (!src_ch)
				window.location.href = 'http://localhost:3000/userpage.php';
				//window.location.href = 'http://localhost:8080/Camagru/userpage.php';
			else if (src_ch.includes('data:image/png;base64,http://localhost:3000/'))
			//else if (src_ch.includes('data:image/png;base64,http://localhost:8080/Camagru/'))
				window.location.href = 'http://localhost:3000/useless.php';
				//window.location.href = 'http://localhost:8080/Camagru/useless.php';
		}
		window.onload = emptycheck;
	</script>
	<link rel="stylesheet" type="text/css" href="css/post.css">
	<title>Make post</title>
</head>
<body>
	<header class="head">Camagru</header>
	<?php include('navbar.php'); ?>
	<main>
		<?php echo '<p style="text-align: center;">'.$msg.'<p>' ?>
		<form method="Post" action="">
			<div id="photo">
				<img id="imgid" src='data:image/png;base64,<?php echo $result['foto']; ?> ' alt="preview">
			</div>
			<textarea id="desc" name="desc" rows="4" placeholder="Descripton..." autocomplete="off"></textarea>
			<span>
				<input type="submit" class="buttons" name="post" value="Post">
				<input type="submit" class="buttons" name="delete" value="Delete">
			</span>
		</form>
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
