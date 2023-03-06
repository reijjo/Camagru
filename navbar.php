<?php
	if (empty($_SESSION))
		return (header('Location: index.php'));
	else {
	echo '
	<nav>
		<ul>
			<li><a href="feed.php">Home</a></li>
			<li><a href="userpage.php">Add photo</a></li>
			<li><a href="myphotos.php">My photos</a></li>
			<li><a><button style="all: unset;">'.$_SESSION['login'].'</button></a>
				<ul>
					<li><a href="settings.php">Settings</a></li>
				</ul>
				<li><a href="logout.php">Log out</a></li>
			</li>
		</ul>
	</nav>
	';
	}

?>
