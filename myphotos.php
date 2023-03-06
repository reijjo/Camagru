<?php
	require_once('config/database.php');
	require_once('config/setup.php');
	session_start();

	if (!empty($_SESSION['login']))
	{
		$username = $_SESSION['login'];

		$photossql = "SELECT * FROM images WHERE username = :username ORDER BY id DESC";
		$photos = $pdo->prepare($photossql);
		$photos->bindParam(':username', $username, PDO::PARAM_STR);
		$photos->execute();

		$delling1 = 'pruut';
		$delling2 = 'pruut22';

		if (isset($_POST['deleteForReal'])) {
			$delling1 = $_POST['deleteForReal'];
			if (isset($_POST['hidden_real'])) {
				$delling2 = $_POST['hidden_real'];

				$remove5sql = "DELETE FROM images WHERE id = :id" ;
				$remove5 = $pdo->prepare($remove5sql);
				$remove5->bindParam(':id', $delling2, PDO::PARAM_INT);
				$remove5->execute();
				header('Refresh: 0;');
				$msg = 'Photo deleted.';
			}
			unset($remove5);
		}

		if (isset($_POST['comdel'])) {
			if (isset($_POST['hidden_comdel'])) {
				$com_id = $_POST['comdel'];

				$delitsql = "DELETE FROM `comments` WHERE id = :id";
				$delit = $pdo->prepare($delitsql);
				$delit->bindParam(':id', $com_id, PDO::PARAM_INT);
				$delit->execute();

				$delcheck = 'comment deleteted';
			}
			unset($delit);
		}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="scripts/myphotos.js"></script>
	<link rel="stylesheet" type="text/css" href="css/feed.css">
	<title>My photos</title>
</head>
<body>
	<header class="head">Camagru</header>
	<?php include('navbar.php') ?>
	<main>
		<?php
			while($result = $photos->fetch()){
				$foto_id = $result['id'];
				$fotoname = $result['foto_name'];
				$foto = $result['foto'];
			echo '
				<form method="POST" enctype="multipart/form-data">
					<div class="photo">
						<img src="data:image/png;base64,'.$foto.'" name="'.$foto_id.'" alt="preview">
						<input type="hidden" id="hidden_data" name="hidden_data" value="'.$foto_id.'">
					</div>
					<div class="likedesc">
						<div class="likehelp" name="'.$foto_id.'">';
							$get_likessql = "SELECT * FROM likes WHERE foto_id = ?";
							$get_likes = $pdo->prepare($get_likessql);
							$get_likes->execute([$foto_id]);
			echo '
							<div id="heartshape">';
							$but_fotoid = $foto_id;
							$but_username = $_SESSION['login'];

							$butchecksql = "SELECT * FROM likes WHERE foto_id = :foto_id AND username = :username";
							$butcheck = $pdo->prepare($butchecksql);
							$butcheck->bindParam(':foto_id', $but_fotoid, PDO::PARAM_INT);
							$butcheck->bindParam(':username', $but_username, PDO::PARAM_STR);
							$butcheck->execute();
			echo '
							</div>
							<div id="likecount">'. $get_likes->rowCount() .' likes</div>
							<div class="photodesc">'.$fotoname.'</div>
						</div>
					</div>
					<div class="comments" name="'.$foto_id.'">';
						$show_commentssql = "SELECT * FROM comments WHERE foto_id = '$foto_id' ORDER BY created_at DESC";
						$show_comments = $pdo->prepare($show_commentssql);
						$show_comments->execute();
						while($row = $show_comments->fetch()) {
							$get_foto_id = $row['foto_id'];
							$get_username = $row['username'];
							$get_comment = $row['comment'];
							$get_id = $row['id'];
			echo '
						<div class="onecomment" name="'.$get_id.'">
							<div class="whosaid">'.$get_username.'</div>
							<div class="whatsaid">'.$get_comment.'</div>
							<div>
								<button class="comdell" id="comdel" value="'.$get_id.'" name="comdel" onclick="delComment(this)">del</button>
								<input type="hidden" id="hidden_comdel" name="hidden_comdel" value="'.$get_id.'">
							</div>
						</div>
					<hr color="#A5C9CA">';

					}
			echo '
					</div>
					<div class="realButton">
						<button class="deleteReal" id="deleteForReal" value="'.$foto_id.'" name="deleteForReal" onclick="delReal(this)">Delete this photo</button>
						<input type="hidden" id="hidden_real" name="hidden_real" value="'.$foto_id.'">
					</div>
				</form>
			';
			}
		?>
	</main>
	<footer class="foot">&copy; taitomer</footer>
</body>
</html>
<!-- HTML ENDS SCRIPT STARTS -->
<script>

</script>
<?php
	}
	else {
		header('location: logout.php');
	}
	unset($photos);
?>
