<?php
	require_once('config/database.php');
	require_once('config/setup.php');
	session_start();
	if (!empty($_SESSION['login']))
	{
		$username = $_SESSION['login'];
		$userid = $_SESSION['id'];

		$getpresql = "SELECT * FROM previews WHERE username = :username ORDER BY id DESC";
		$getpre = $pdo->prepare($getpresql);
		$getpre->bindParam(':username', $username, PDO::PARAM_STR);
		$getpre->execute();

		$getphotossql = "SELECT * FROM images WHERE username = :username ORDER BY id DESC";
		$getphotos = $pdo->prepare($getphotossql);
		$getphotos->bindParam(':username', $username, PDO::PARAM_STR);
		$getphotos->execute();

		if (isset($_POST['takeFoto']))
		{
			if (isset($_POST['hidden_pic'])) {
				$preimg = $_POST['hidden_pic'];
				$preimg = str_replace('data:image/jpeg;base64,', '', $preimg);
				$preimg = str_replace(' ', '+', $preimg);

				$thephoto1 = base64_decode($preimg);
			// Checking stickers
				if (isset($_POST['hidden_st1'])) {
					$sticker1url = $_POST['hidden_st1'];
				}
				else
					$sticker1url = '';
				if (isset($_POST['hidden_st2'])) {
					$sticker2url = $_POST['hidden_st2'];
				}
				else
					$sticker2url = '';
			// END checking stickers
				$image1 = imagecreatefromstring($thephoto1);
				$posx1 = 0;		//kohta mihin menee leveyssuunnassa
				$posy1 = 0;		//kohta mihin menee korkeus suunnassa
				$posx2 = 0;
				$posy2 = 0;

			// Adding stickers
				if ($sticker1url !== '') {
					$add_sticker1 = imagecreatefrompng($sticker1url);
					imagecopyresized(
						$image1, $add_sticker1,
				 		$posx1, $posy1,
				 		0, 0, imagesx($image1) / 4, imagesy($image1) / 4,
						imagesx($add_sticker1), imagesy($add_sticker1)
					);
				}
				if ($sticker2url !== '') {
					$add_sticker2 = imagecreatefrompng($sticker2url);
					imagecopyresized(
						$image1, $add_sticker2,
						$posx2, $posy2,
						0, 0, imagesx($image1), imagesy($image1),
						imagesx($add_sticker2), imagesy($add_sticker2)
					);
				}
			// END adding stickers
				ob_start();
				imagejpeg($image1);
				$ready1 = ob_get_contents();
				ob_end_clean();

				$final1 = "data:image/jpeg;base64," . base64_encode($ready1);

				$presql = "INSERT INTO previews (username, foto) VALUES (:username, :foto)";
				$pre = $pdo->prepare($presql);
				$pre->bindParam(':username', $username, PDO::PARAM_STR);
				$pre->bindParam(':foto', $final1, PDO::PARAM_STR);
				$pre->execute();
			}
			unset($pre);
		}

		if (isset($_POST['usebutton']))
		{
			if (isset($_POST['hidden_data'])) {

				$username = $_SESSION['login'];

				$img = $_POST['hidden_data'];
				$img = str_replace('data:image/jpeg;base64,', '', $img);
				$img = str_replace(' ', '+', $img);

				$img_name = uniqid();

				$picchecksql = "SELECT * FROM previews WHERE username = :username";
				$piccheck = $pdo->prepare($picchecksql);
				$piccheck->bindparam(':username', $username, PDO::PARAM_STR);
				$piccheck->execute();
				//$resultpic = $piccheck->fetchAll(PDO::FETCH_ASSOC);
				$sql = "INSERT INTO `images` (username, userid, foto_name, foto) VALUES (:username, :userid, :foto_name, :foto)";
				if ($insert = $pdo->prepare($sql)) {
					$insert->bindParam(':username', $username, PDO::PARAM_STR);
					$insert->bindParam(':userid', $userid, PDO::PARAM_STR);
					$insert->bindParam(':foto_name', $img_name, PDO::PARAM_STR);
					$insert->bindParam(':foto', $img, PDO::PARAM_STR);
					$insert->execute();
					$msg = 'data inserted successfully';
				}
				else
					$msg = 'WHATTHEFUKK!';

				$delimg = $_POST['hidden_data'];

				$delsql = "DELETE FROM previews WHERE foto = :foto";
				$delete = $pdo->prepare($delsql);
				$delete->bindParam(':foto', $delimg, PDO::PARAM_STR);
				$delete->execute();

			unset($insert);
			unset($delete);
			}
		}

		if (isset($_POST['delbutton']))
		{
			if (isset($_POST['hidden_data'])) {
				$username = $_SESSION['login'];

				$delimg = $_POST['hidden_data'];

				$delsql = "DELETE FROM previews WHERE foto = :foto";
				$delete = $pdo->prepare($delsql);
				$delete->bindParam(':foto', $delimg, PDO::PARAM_STR);
				$delete->execute();
			}
		}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/user.css">
	<script src="https://code.jquery.com/jquery-3.6.1.js"
	integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
	crossorigin="anonymous"></script>
	<script src="scripts/userpage.js"></script>
	<title>Add photo</title>
</head>
<body>
	<header class="head">Camagru</header>
	<?php include('navbar.php') ?>
	<main>
<!--	Stickers				-->
		<div class="stickers">
			<h4 style="text-align: center;">Stickers</h4>
			<p style="text-align: center;";>Choose one first:</p>
			<div class="sticker">
				<div class="stickerstash">
					<img src="images/CCTV-Camera-icon.png" id="stick1" class="resize" alt="cctv">
				</div>
				<div class="span">
					<button class="stickbut" id="add1" onclick="addSticker1(this)">Use</button>
					<button class="stickbut" id="del1" onclick="delSticker1(this)" disabled>Del</button>
				</div>
			</div>
			<div class="sticker">
				<div class="stickerstash">
					<img src="images/vetta.png" id="stick2" class="resize" alt="dice">
				</div>
				<div class="span">
					<button class="stickbut" id="add2" onclick="addSticker2(this)">Use</button>
					<button class="stickbut" id="del2" onclick="delSticker2(this)" disabled>Del</button>
				</div>
			</div>
		</div>
<!--END Stickers					-->
		<div id="foto">
			<video id="videoCam" autoplay></video>
			<canvas id="bigscreen"></canvas>
		</div>
<!-- 	Side preview juttu 				-->
		<div id="side">
			<h4 style="text-align: center;">Photos</h4>
			<p style="text-align: center;">Make a post:</p>
			<?php
				while($newrow = $getpre->fetch()) {
					$newfoto = $newrow['foto'];
					?>
					<div class="pic">
						<img id="canvas" class="sidepic" src="<?= $newrow['foto'] ?>" onerror="this.onerror=null; this.src='images/dia.png'" alt="preview" name="photo">
						<form method="post" enctype="multipart/form-data"></form>
						<input name="hidden_data" id="hiddendata" type="hidden">
						<button type="file" class="usebutton" name="usebutton" onclick="useThumb(this)">Use</button>
						<button type="file" class="delbutton" name="delbutton" onclick="delThumb(this)">Del</button>
					</div>
			<?php }
			?>
			<hr />
			<p style="text-align: center;">Already posted:</p>
			<?php
				while($oldrow = $getphotos->fetch()) {
					$oldname = $oldrow['foto_name'];
					$oldfoto = $oldrow['foto'];
				echo '
					<div id="already" class="alreadyposted" style="padding: 0.2vw;">
						<img id="canvas" src="data:image/jpeg;base64,'.$oldfoto.'" alt="'.$oldname.'">
					</div>';
				}
			?>
		</div>
<!-- END side preview juttu -->
<!-- Buttons --->
	</main>
	<div class="buttonsetc">
		<span>
			<button id="startCam" class="button" onclick="openCam()" disabled>Webcam</button>
			<button id="closeCam" class="button" onclick="closeCam()" disabled>Stop webcam</button>
			<button type="file" id="takeFoto" name="takeFoto" class="button" onclick="takePic()" disabled>Take photo</button>
			<form method="POST" enctype="multipart/form-data">
				<input type="hidden" id="hidden_pic" name="hidden_pic" value="">
				<input type="hidden" id="hidden_st1" name="hidden_st1" value="">
				<input type="hidden" id="hidden_st2" name="hidden_st2" value="">
			</form>
			<button id="uploadPic" class="button" onclick="uploadPic()" >
				<label for="fileinput">
					Upload image
					<input type="file" id="fileinput" name="fileinput" class="button" accept="image/*" onclick="" >
				</label>
			</button>
		</span>
	</div>
	<footer class="foot">&copy; taitomer</footer>
</body>
</html>
<!-- HTML ENDS SCRIPT STARTS -->
<?php
	}
	else {
		header('location: logout.php');
	}
?>
