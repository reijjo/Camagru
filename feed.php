<?php
	require_once('config/database.php');
	require_once('config/setup.php');
	session_start();

	$msg = $comm_foto_id = $comment = '';
	$getthat = 'jotainpaskaa';
	$gotit = 'mailipaskaa';
	$delcheck = 'KOmmenttii8';

	if (!empty($_SESSION['login']))
	{
		if (!isset($_GET['page']))
			$page = 1;
		else if (!ctype_digit($_GET['page']))
			$page = 1;
		else
			$page = $_GET['page'];

		$results_per_page = 5;
		$page_first_result = ($page-1) * $results_per_page;

		$photosql = "SELECT * FROM images ORDER BY id DESC";
		$photos = $pdo->prepare($photosql);
		$photos->execute();

		$number_of_result = $photos->rowCount();
		$number_of_page = ceil($number_of_result / $results_per_page);

		$pagi_sql = "SELECT * FROM images ORDER BY id DESC LIMIT " . $page_first_result . ',' . $results_per_page;
		$pagi = $pdo->prepare($pagi_sql);
		$pagi->execute();
/* Like */
		if (isset($_POST['like'])) {
			if (isset($_POST['hidden_like'])) {
				$li_fotoid = $_POST['hidden_like'];
				$li_username = $_SESSION['login'];
				$li_userid = $_SESSION['id'];
				$li_likes = '1';

				// Like check
				$likechecksql = "SELECT * FROM likes WHERE foto_id = :foto_id AND username = :username AND likes = :likes";
				$likecheck = $pdo->prepare($likechecksql);
				$likecheck->bindParam(':foto_id', $li_fotoid, PDO::PARAM_INT);
				$likecheck->bindParam(':username', $li_username, PDO::PARAM_STR);
				$likecheck->bindParam(':likes', $li_likes, PDO::PARAM_INT);
				$likecheck->execute();
				if ($likecheck->rowCount() > 0) {
					$msg = 'You liked this already';
				}
				else {
					$lik_sql = "INSERT INTO likes (foto_id, username, userid, likes) VALUES (:foto_id, :username, :userid, :likes)";
					$lik_add = $pdo->prepare($lik_sql);
					$lik_add->bindParam(':foto_id', $li_fotoid, PDO::PARAM_INT);
					$lik_add->bindParam(':username', $li_username, PDO::PARAM_STR);
					$lik_add->bindParam(':userid', $li_userid, PDO::PARAM_INT);
					$lik_add->bindParam(':likes', $li_likes, PDO::PARAM_INT);
					$lik_add->execute();
				}
				unset($lik_add);
			}
			unset($likecheck);
		}

		if (isset($_POST['dislike'])) {
			if (isset($_POST['hidden_dislike'])) {
				$li_fotoid = $_POST['hidden_dislike'];
				$li_username = $_SESSION['login'];
				$li_likes = '1';

				$dellikesql = "DELETE FROM likes WHERE foto_id = :foto_id AND username = :username AND likes = :likes";
				$dellike = $pdo->prepare($dellikesql);
				$dellike->bindParam(':foto_id', $li_fotoid, PDO::PARAM_INT);
				$dellike->bindParam(':username', $li_username, PDO::PARAM_STR);
				$dellike->bindParam(':likes', $li_likes, PDO::PARAM_INT);
				$dellike->execute();
			}
			unset($dellike);
		}
/* END Like */
/* Comment */
		if (isset($_POST['go'])) {
			if (isset($_POST['hidden_data'])) {
				if (!isset($_POST['comment']))
					return ;
				$comment = trim(htmlspecialchars($_POST['comment']));
				$comm_foto_id = $_POST['hidden_data'];
				$comm_username = $_SESSION['login'];
				$userid = $_SESSION['id'];

				if (strlen($comment) < 1)
					$msg = 'Comment empty.';
				else if (strlen($comment) > 250)
					$msg = 'Comment too long';
				else {
					$comm_sql = "INSERT INTO comments (foto_id, username, userid, comment)
					VALUES (:foto_id, :username, :userid, :comment)";
					$comm_add = $pdo->prepare($comm_sql);
					$comm_add->bindParam(':foto_id', $comm_foto_id, PDO::PARAM_INT);
					$comm_add->bindParam(':username', $comm_username, PDO::PARAM_STR);
					$comm_add->bindParam(':userid', $userid, PDO::PARAM_STR);
					$comm_add->bindParam(':comment', $comment, PDO::PARAM_STR);
					$comm_add->execute();

					$msg = 'comment aiiiight';

					$comm_notif = '1';

					$comm_mailsql = "SELECT * FROM images WHERE id = '$comm_foto_id' AND notifications = '$comm_notif'";
					$comm_mail = $pdo->prepare($comm_mailsql);
					$comm_mail->execute();
					while ($answer = $comm_mail->fetch()) {
						$getthat = $answer['username'];
					}
						$getmailsql = "SELECT email FROM users WHERE username = '$getthat'";
						$getmail = $pdo->prepare($getmailsql);
						$getmail->execute();
						while ($answer2 = $getmail->fetch()) {
							$gotit = $answer2['email'];
						}
						$thismail = $gotit;

						$to = $thismail;

						$email_msg = "Someone commented something on some of your photos.";
						$subject = "You got a comment.";
						$headers = "From: infohelpdeskwhatever@camagru.com" . "\r\n";
						$headers .= "Content-type: text/html;" . "\r\n";

						$ms = "<html><body><div><div> Dear $getthat, </div><br><br>";
						$ms .= "<div style=\"padding-top: 8px;\"></div>";
						$ms .= "<div style='padding-top:10px;'>Check your comments.</div>";
						$ms .= "</body></html>";

						mail($to, $subject, $ms, $headers);
					}
					unset($comm_add);
					$comm_add = null;
					header('Refresh: 0; URL="feed.php"');

				}
			}
			unset($getmail);
/* END comment */
		if (isset($_POST['comdel'])) {
			if (isset($_POST['hidden_comdel'])) {
				$com_id = $_POST['comdel'];

				$delitsql = "DELETE FROM `comments` WHERE id = :id AND username = :username";
				$delit = $pdo->prepare($delitsql);
				$delit->bindParam(':id', $com_id, PDO::PARAM_INT);
				$delit->bindParam(':username', $_SESSION['login'], PDO::PARAM_STR);
				$delit->execute();

				$delcheck = 'comment deleted';
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
	<script src="scripts/feed.js"></script>
	<link rel="stylesheet" type="text/css" href="css/feed.css">
	<title>Home</title>
</head>
<body>
	<header class="head">Camagru</header>
	<?php include('navbar.php') ?>
	<main>
		<?php
			while($result = $pagi->fetch()) {
				$foto_id = $result['id'];
				$username = $result['username'];
				$userid = $result['userid'];
				$fotoname = $result['foto_name'];
				$foto = $result['foto'];
			echo '
			<form method="POST" action="" enctype="multipart/form-data">
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
					if ($get_likes->rowCount() < 1) {
					echo '
						<div id="heartshape">
							<button id="like" name="like" onclick="likeThis(this)">like</button>
							<input type="hidden" id="hidden_like" name="hidden_like" value="'.$foto_id.'">
						</div>
						<div id="likecount">'.'0 likes</div>
						<div class="photodesc">'.$fotoname.'</div>';
					}
					else {
					echo '
						<div id="heartshape">';
						$but_fotoid = $foto_id;
						$but_username = $_SESSION['login'];
						$but_likes = '1';

						// Like check
						$butchecksql = "SELECT * FROM likes WHERE foto_id = :foto_id AND username = :username AND likes = :likes";
						$butcheck = $pdo->prepare($butchecksql);
						$butcheck->bindParam(':foto_id', $but_fotoid, PDO::PARAM_INT);
						$butcheck->bindParam(':username', $but_username, PDO::PARAM_STR);
						$butcheck->bindParam(':likes', $but_likes, PDO::PARAM_INT);
						$butcheck->execute();

						if ($butcheck->rowCount() < 1) {
					echo '
						<button id="like" name="like" onclick="likeThis(this)">like</button>
						<input type="hidden" id="hidden_like" name="hidden_like" value="'.$foto_id.'">';
						}
						else {
					echo '
						<button id="dislike" name="dislike" onclick="dislikeThis(this)">dislike</button>
						<input type="hidden" id="hidden_dislike" name="hidden_dislike" value="'.$foto_id.'">';
						}
					echo '
						</div>
						<div id="likecount">'. $get_likes->rowCount() .' likes</div>
						<div class="photodesc">'.$fotoname.'</div>';
					}
					echo '
					</div>
				</div>
				<div class="makecomment">
					<label>'.$_SESSION["login"].'</label>
					<input type="text" name="comment" placeholder="Comment" value="" autocomplete="off" pattern="[a-zA-Z0-9]+$">
					<button name="go" class="go" value="" onclick="makeComment(this)">go!</button>
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
				if ($get_username == $_SESSION['login']) {
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
				else {
				echo '
					<div class="onecomment">
					<div class="whosaid">'.$get_username.'</div>
					<div class="whatsaid">'.$get_comment.'</div>
				</div>
				<hr color="#A5C9CA">';
				}
			}
			echo '
				</div>
			</form>
			';
		} ?>
		<div class="holdpagi">Pages:
		<?php
			for($page = 1;$page<=$number_of_page;$page++) {
				echo '<a class="pagibuttons" href="feed.php?page='.$page.'">'.$page.'</a>';
			}
		?>
		</div>
	</main>
	<footer class="foot">&copy; taitomer</footer>
</body>
</html>
<!-- HTML ENDS SCRIPT STARTS -->
<?php
	}
	else {
		header('location: logout.php');
	}
	unset($photos);
?>
