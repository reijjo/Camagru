<?php
	require_once('config/database.php');
	require_once('config/setup.php');

	if (!isset($_GET['page']))
		$page = 1;
	else if (!ctype_digit($_GET['page']))
		$page = 1;
	else
		$page = $_GET['page'];

	$results_per_page = 5;
	$page_first_result = ($page-1) * $results_per_page;

	$photossql = "SELECT * FROM images ORDER BY id DESC";
	$photos = $pdo->prepare($photossql);
	$photos->execute();

	$number_of_result = $photos->rowCount();
	$number_of_page = ceil($number_of_result / $results_per_page);

	$pagi_sql = "SELECT * FROM images ORDER BY id DESC LIMIT " . $page_first_result . ',' . $results_per_page;
	$pagi = $pdo->prepare($pagi_sql);
	$pagi->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/feed.css">
	<title>Preview</title>
</head>
<body>
<header class="head">Camagru</header>
	<?php include('strippednavbar.php') ?>
	<main>
		<?php
			while($result = $pagi->fetch()) {
				$foto_id = $result['id'];
				$username = $result['username'];
				$fotoname = $result['foto_name'];
				$foto = $result['foto'];
			echo '
			<div class="formlookalike">
				<div class="username">'.$username.'</div>
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
						$but_username = $username;
						$but_likes = '1';

						// Like check
						$butchecksql = "SELECT * FROM likes WHERE foto_id = :foto_id AND username = :username AND likes = :likes";
						$butcheck = $pdo->prepare($butchecksql);
						$butcheck->bindParam(':foto_id', $but_fotoid, PDO::PARAM_INT);
						$butcheck->bindParam(':username', $but_username, PDO::PARAM_STR);
						$butcheck->bindParam(':likes', $but_likes, PDO::PARAM_INT);
						$butcheck->execute();
					echo '
						</div>
						<div id="likecount">'. $get_likes->rowCount() .' likes</div>
						<div class="photodesc">'.$fotoname.'</div>';

					echo '
					</div>
				</div>
				<div class="makecomment">
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
					<div class="onecomment">
					<div class="whosaid">'.$get_username.'</div>
					<div class="whatsaid">'.$get_comment.'</div>
				</div>
				<hr color="#A5C9CA">';
			}
			echo '
				</div>
			</div>
			<div style="text-align: center;"><a href="index.php">Back</a> to frontpage</div>
			';
		}
		unset($photos);
		unset($get_likes);
		unset($butcheck);
		unset($show_comments);
		?>
		<div class="holdpagi">Pages:
		<?php
			for($page = 1;$page<=$number_of_page;$page++) {
				echo '<a class="pagibuttons" href="sneakpeak.php?page='.$page.'">'.$page.'</a>';
			}
		?>
		</div>
	</main>
	<footer class="foot">&copy; taitomer</footer>
</body>
</html>
