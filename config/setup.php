<?php
	$DB_NAME = 'camagru';
	$DB_CONN = "mysql:host=localhost";
	$DB_USER = 'root';
	$DB_PASSWORD = 'taitomer';

	try {
		// Try to connect server
		$conn = new PDO($DB_CONN, $DB_USER, $DB_PASSWORD);

		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// Execute sql

		$sql = "
			CREATE DATABASE IF NOT EXISTS camagru;

			USE camagru;

			CREATE TABLE IF NOT EXISTS users (
				`id` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
				`username` VARCHAR(255) NOT NULL,
				`passwd` VARCHAR(255) NOT NULL,
				`email` VARCHAR(255) NOT NULL,
				`activationcode` VARCHAR(255) NOT NULL,
				`sstatus` int(11) NOT NULL,
				`notifications` INT DEFAULT 1,
				`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			);
			CREATE TABLE IF NOT EXISTS images (
				`id` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
				`username` VARCHAR(50) NOT NULL,
				`userid` INT(11) NOT NULL,
				`foto_name` TEXT NOT NULL,
				`foto` LONGBLOB NOT NULL,
				`notifications` INT DEFAULT 1,
				`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			);
			CREATE TABLE IF NOT EXISTS previews (
				`id` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
				`username` VARCHAR(50) NOT NULL,
				`foto` LONGBLOB NOT NULL,
				`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			);
			CREATE TABLE IF NOT EXISTS comments (
				`id` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
				`foto_id` INT(11) NOT NULL,
				`username` VARCHAR(50) NOT NULL,
				`userid` INT(11) NOT NULL,
				`comment` VARCHAR(255) NOT NULL,
				`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			);
			CREATE TABLE IF NOT EXISTS likes (
				`id` INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
				`foto_id` INT(11) NOT NULL,
				`username` VARCHAR(50) NOT NULL,
				`userid` INT(11) NOT NULL,
				`likes` INT DEFAULT 0 NOT NULL,
				`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
			);
		";
		$conn->exec($sql);
	}
	catch(PDOException $e) {
		echo 'PDO error' . $sql . '<br>' . $e->getMessage();
	}
	$conn = null;

?>
