<?php
require("conn.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<div class="container">
			<?php
				require("header.php");
				
				if(@$_POST) {
					$stmt = $conn->prepare("INSERT INTO `threads` (`date`, `author`, `message`) VALUES (now(), ?, ?)");
					$stmt->bind_param("ss", $_SESSION['user'], $text);
					if(@$_POST) {
						$text = replaceBBcodes(@$_POST['comment']);
						$text = str_replace(PHP_EOL, "<br>", $text);
					}
					$stmt->execute();
					$stmt->close();
				}
			?>
			<form method="post" enctype="multipart/form-data">
				<textarea required cols="88" placeholder="Text" name="comment"></textarea><br>
				<input name="newpost" type="submit" value="Post"> <small>max limit: 300 characters</small>
			</form>
			<div class="footer">
				made by spiltbrains. <a href="terms.txt">terms & conditions</a>
			</div>
		</div>
	</body>
</html>

