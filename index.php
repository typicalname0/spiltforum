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
			?>
			<div class="hero">
				<h1>a twitter alternative</h1>
				<a href="register.php"><button>join</button></a>
			</div>
				<h2>Recent Posts</h2>
				<?php
					$stmt = $conn->prepare("SELECT * FROM threads ORDER BY id DESC");
					$stmt->execute();
					$result = $stmt->get_result();
					if($result->num_rows === 0) echo('no users');
					while($row = $result->fetch_assoc()) {
						echo '<div class="message"><a href="profile.php?id=' . getID($row['author'], $conn) . '">' . $row['author'] . '</a> <small><a href="reply.php?id=' . $row['id'] . '">[reply]</a></small>
						<small><small>' . $row['date'] . ' - [' . $row['replies'] . ' replies]</small></small><br>
						' . $row['message'] . '</div><br>';
					}
					$stmt->close();
				?>
				<p>meet people like <b>
					<?php
						$stmt = $conn->prepare("SELECT * FROM users ORDER BY rand() LIMIT 1");
						$stmt->execute();
						$result = $stmt->get_result();
						if($result->num_rows === 0) echo('no users');
						while($row = $result->fetch_assoc()) {
							echo '<a href="profile.php?id=' . $row['id'] . '">' . $row['username'] . '</a>';
						}
						$stmt->close();
					?>	
				</b> and others like...</p>
				<div class="userlist">
					<?php
						$stmt = $conn->prepare("SELECT * FROM users");
						$stmt->execute();
						$result = $stmt->get_result();
						if($result->num_rows === 0) echo('no users');
						while($row = $result->fetch_assoc()) {
							echo '<a href="profile.php?id=' . $row['id'] . '">' . $row['username'] . '</a><br>';
						}
						$stmt->close();
					?>
					
				</div>
			<div class="footer">
				made by spiltbrains. <a href="terms.txt">terms & conditions</a>
			</div>
		</div>
	</body>
</html>

