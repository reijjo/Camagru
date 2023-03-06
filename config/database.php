<?php
require_once('setup.php');
	$DB_NAME = 'camagru';
	$DB_DSN = 'mysql:host=localhost;dbname='.$DB_NAME.';charset=utf8mb4';
	$DB_USER = 'root';
	$DB_PASSWORD = 'taitomer';

	$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
