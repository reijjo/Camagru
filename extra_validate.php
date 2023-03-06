<?php
	function check_password($passwd)
	{
		if (strlen($_POST['passwd']) > 7 &&
			preg_match('/[a-z]/', $passwd) &&
			preg_match('/[A-Z]/', $passwd) &&
			preg_match('/[0-9]/', $passwd))
		{
			return (1);
		}
		else {
			return (0);
		}
	}

	function check_newpassword($newpasswd)
	{
		if (strlen($_POST['newpasswd']) > 7 &&
			preg_match('/[a-z]/', $newpasswd) &&
			preg_match('/[A-Z]/', $newpasswd) &&
			preg_match('/[0-9]/', $newpasswd))
		{
			return (1);
		}
		else {
			return (0);
		}
	}

	function check_data($data)
	{
		$data = trim($data);	// Strip whitespaces from beginning and end
		$data = stripslashes($data); // Un-quotes a quoted string
		$data = strip_tags($data);	// Strips HTML and PHP tags from a string
		$data = htmlspecialchars($data); // Converts specialchars to HTML entites (& => &amp;)
		return ($data);
	}
?>
