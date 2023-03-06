<?php
	include('config/setup.php');
	include('config/database.php');

	$msg = '';
	if (empty($_GET['code']))
		header('Location: index.php');

	if (!empty($_GET['code']) && isset($_GET['code']))
	{
		$code = $_GET['code'];

		$sql = "SELECT * FROM `users` WHERE activationcode=:code";
		$query = $pdo->prepare($sql);
		$query->bindParam(':code', $code, PDO::PARAM_STR);
		$query->execute();

		$cnt = 1;

		if ($query->rowCount() > 0)
		{
			$st = 0;
			$sql = "SELECT `id` FROM `users` WHERE `activationcode`=:code and `sstatus`=:st";
			$query = $pdo->prepare($sql);
			$query->bindParam(':code', $code, PDO::PARAM_STR);
			$query->bindParam(':st', $st, PDO::PARAM_STR);
			$query->execute();

			$results = $query->fetchAll(PDO::FETCH_OBJ);
			$cnt = 1;

			if ($query->rowCount() > 0)
			{
				$st = 1;
				$sql = "UPDATE `users` SET `sstatus`=:st WHERE `activationcode`=:code";
				$query = $pdo->prepare($sql);
				$query->bindParam(':code', $code, PDO::PARAM_STR);
				$query->bindParam(':st', $st, PDO::PARAM_STR);
				$query->execute();

				$msg = "Your account is activated";
			}
			else {
				$msg = "Your account is already active, no need to activate again.";
			}
		}
		else {
			$msg = "Wrong activation code.";
		}
		header('refresh: 2; URL="index.php"');
	}
	unset($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Email stuff</title>
</head>
<body>
	<?= $msg ?>
</body>
</html>
