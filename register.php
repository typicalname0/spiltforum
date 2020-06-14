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
					if($_POST['password'] && $_POST['username']) {
						if($_POST['password'] != $_POST['confirm']) {
							exit("passwords do not match up.");
						}
						$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
						$stmt->bind_param("s", $_POST['username']);
						$stmt->execute();
						$result = $stmt->get_result();
						if($result->num_rows === 1) {
							echo "there's already a user with that same name!";
							$check = 0;
						} else {
							$check = 1;
						}

						$stmt->close();

						if($check == 1) {
							//TODO: add cloudflare ip thing 
							$stmt = $conn->prepare("INSERT INTO `users` (`username`, `password`, `date`) VALUES (?, ?, now())");
							$stmt->bind_param("ss", $username, $password);
						
							$username = htmlspecialchars(@$_POST['username']);
							$password = password_hash(@$_POST['password'], PASSWORD_DEFAULT);
							$stmt->execute();
						
							$stmt->close();
							$conn->close();
							$_SESSION['user'] = htmlspecialchars($username);
							header("Location: register.php");
						}
					}
				}
			?>
			<h1>register/spilt.xyz</h1>
			<form action="" method="post">
				<input required placeholder="Username" type="text" name="username"><br><br>
				<input required placeholder="Password" type="password" name="password"><br>
				<input required placeholder="Confirm Password" type="password" name="confirm"><br><br>
				<input type="submit" value="Register">
			</form>
			<div class="footer">
				made by spiltbrains. <a href="terms.txt">terms & conditions</a>
			</div>
		</div>
	</body>
</html>