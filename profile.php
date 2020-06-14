<?php
require("conn.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style.css">
		<?php
			$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
			$stmt->bind_param("s", $_GET['id']);
			$stmt->execute();
			$result = $stmt->get_result();
			while($row = $result->fetch_assoc()) {
				echo "<style>" . $row['css'] . "</style>";
			}
			$stmt->close();
		?>
	</head>
	<body>
		<div class="container">
			<?php
				require("header.php");
				$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
				$stmt->bind_param("s", $_GET['id']);
				$stmt->execute();
				$result = $stmt->get_result();
				if($result->num_rows === 0) echo('no users');
				while($row = $result->fetch_assoc()) {
					echo '<h1>' . $row['username'] . '</h1>';
					echo '<img width="300px" src="pfp/' . $row['pfpurl'] . '"><br><div class="info"><b><small>Account Creation Date:</b> ' . $row['date'] . '<br>' . $row['bio'] . '</small></div>';
				}
				$stmt->close();
			?>	
			<div class="footer">
				made by spiltbrains. <a href="terms.txt">terms & conditions</a>
			</div>
		</div>
	</body>
</html>

