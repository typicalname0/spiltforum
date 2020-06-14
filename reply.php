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
				
				if($_GET['id']) {
					$stmt = $conn->prepare("SELECT * FROM threads WHERE id = ?");
					$stmt->bind_param("s", $_GET['id']);
					$stmt->execute();
					$result = $stmt->get_result();
					if($result->num_rows === 0) echo('no users');
					while($row = $result->fetch_assoc()) {
						echo '<div class="message"><a href="profile.php?id=' . getID($row['author'], $conn) . '">' . $row['author'] . '</a> 
						<small><small>' . $row['date'] . '</small></small><br>
						' . $row['message'] . '</div><br>';
					}
					$stmt->close();
				} else {
					die("specify an id idiot");
				}
				
				if(@$_POST) {
					$stmt = $conn->prepare("INSERT INTO `replies` (`author`, `date`, `text`, `toid`) VALUES (?, now(), ?, ?)");
					$stmt->bind_param("sss", $_SESSION['user'], $text, $_GET['id']);
					if(@$_POST) {
						$text = replaceBBcodes(@$_POST['comment']);
						$text = str_replace(PHP_EOL, "<br>", $text);
					}
					$stmt->execute();
					$stmt->close();
					mysqli_query($conn, "UPDATE threads SET replies = replies+1 WHERE id = '" . (int)$_GET['id'] . "'");
				}
			?>
			<form method="post" enctype="multipart/form-data">
				<textarea required cols="88" placeholder="Text" name="comment"></textarea><br>
				<input name="newpost" type="submit" value="Post"> <small>max limit: 300 characters</small>
			</form>
			<hr>
			<?php
				$stmt = $conn->prepare("SELECT * FROM replies WHERE toid = ?");
				$stmt->bind_param("s", $_GET['id']);
				$stmt->execute();
				$result = $stmt->get_result();
				while($row = $result->fetch_assoc()) {
					echo '<div class="message"><a href="profile.php?id=' . getID($row['author'], $conn) . '">' . $row['author'] . '</a> 
					<small><small>' . $row['date'] . '</small></small><br>
					' . $row['text'] . '</div><br>';
				}
				$stmt->close();
			?>
			<div class="footer">
				made by spiltbrains. <a href="terms.txt">terms & conditions</a>
			</div>
		</div>
	</body>
</html>

