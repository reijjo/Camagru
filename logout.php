<?php
	session_start();
	if (!empty($_SESSION['login']))
		$_SESSION['login'] === '';
	session_unset();
	$_SESSION['action1'] = 'You have logged out.';
	header('location: index.php');
?>
