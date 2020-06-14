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
						$stmt = $conn->prepare("SELECT password FROM `users` WHERE username=?");
						$stmt->bind_param("s", $_POST['username']);
						$stmt->execute();
						$result = $stmt->get_result();
						
						while($row = $result->fetch_assoc()) {
							$hash = $row['password'];
							if(password_verify($_POST['password'], $hash)){
								$_SESSION['user'] = htmlspecialchars($_POST['username']);
								header("Location: settings.php");
							} else {
								echo 'login information dosent exist.<hr>';
							}
						}
					}
				} 
			?>
			<h1>login/spilt.xyz</h1>
			<form action="" method="post">
				<input required placeholder="Username" type="text" name="username"><br>
				<input required placeholder="Password" type="password" name="password"><br><br>
				<input type="submit" value="Register">
			</form>
			<div class="footer">
				made by spiltbrains. <a href="terms.txt">terms & conditions</a>
			</div>
		</div>
	</body>
</html>

