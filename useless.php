<?php
	require_once('config/database.php');
	session_start();

	if (!empty($_SESSION['login']))
	{
		$sqlshitty = "DELETE FROM images ORDER BY id DESC LIMIT 1";
		$shitty = $pdo->prepare($sqlshitty);
		$shitty->execute();
	}
	header('Location: userpage.php');
?>
