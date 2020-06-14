<div class="header">
	<big><a style="font-size: 19px;text-decoration: none;"href="index.php">spilt.xyz</a></big> &bull; 
	<a href="login.php">login</a> &bull; <a href="register.php">register</a> &bull; 
	<a href="settings.php">settings</a> &bull; <a href="newpost.php">new post</a>
	<?php
	if(isset($_SESSION['user'])) {
		echo "<small><span style='float:right'>logged in as " . htmlspecialchars($_SESSION['user']) . "</span></small>";
	}
	?>
</div>