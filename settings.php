<?php
require("conn.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="style.css">
		<?php
			$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
			$stmt->bind_param("s", $_SESSION['user']);
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
				
				if(@$_POST['bioset']) {
					$stmt = $conn->prepare("UPDATE users SET bio = ? WHERE `users`.`username` = ?;");
					$stmt->bind_param("ss", $text, $_SESSION['user']);
					$text = str_replace(PHP_EOL, "<br>", $_POST['bio']);
					$stmt->execute(); 
					$stmt->close();
					header("Location: settings.php");
				} else if(@$_POST['submit']) {
					$target_dir = "pfp/";
					$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
					$uploadOk = 1;
					$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
					if(isset($_POST["submit"])) {
						$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
						if($check !== false) {
							$uploadOk = 1;
						} else {
							$uploadOk = 0;
						}
					}
					if (file_exists($target_file)) {
						echo 'file with the same name already exists<hr>';
						$uploadOk = 0;
					}
					if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif" ) {
						echo 'unsupported file type. must be jpg, png, jpeg, or gif<hr>';
						$uploadOk = 0;
					}
					if ($uploadOk == 0) { } else {
						if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
							$stmt = $conn->prepare("UPDATE users SET pfpurl = ? WHERE `users`.`username` = ?;");
							$stmt->bind_param("ss", $filename, $_SESSION['user']);
							$filename = basename($_FILES["fileToUpload"]["name"]);
							$stmt->execute(); 
							$stmt->close();
						} else {
							echo 'fatal error<hr>';
						}
					}
				} else if(@$_POST['css']) {
					$stmt = $conn->prepare("UPDATE users SET css = ? WHERE `users`.`username` = ?;");
					$stmt->bind_param("ss", $validatedcss, $_SESSION['user']);
					$validatedcss = validateCSS($_POST['css']);
					$stmt->execute(); 
					$stmt->close();
					header("Location: settings.php");
				}
				
				$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
				$stmt->bind_param("s", $_SESSION['user']);
				$stmt->execute();
				$result = $stmt->get_result();
				if($result->num_rows === 0) { 
					echo('ur not logged in dummy. log in to change settings.');
					echo '<div class="footer">
						made by spiltbrains. <a href="terms.txt">terms & conditions</a>
					</div>';
					die();
				}
				while($row = $result->fetch_assoc()) {
					echo '<h1>' . $row['username'] . '</h1>';
					echo '<img width="300px" src="pfp/' . $row['pfpurl'] . '"><br><div class="info"><b><small>Account Creation Date:</b> ' . $row['date'] . '<br>' . $row['bio'] . '</small></div>';
					$css = $row['css'];
					$bio = $row['bio'];
				}
				$stmt->close();
			?>	
			<form method="post" enctype="multipart/form-data">
				<small>Select file:</small>
				<input type="file" name="fileToUpload" id="fileToUpload">
				<input type="submit" value="Upload Image" name="submit">
			</form>
			<br>
			<form method="post" enctype="multipart/form-data">
				<textarea required cols="58" placeholder="Bio" name="bio"><?php echo $bio;?></textarea><br>
				<input name="bioset" type="submit" value="Set"> <small>max limit: 300 characters</small>
			</form>
			<br>
			<form method="post" enctype="multipart/form-data">
				<textarea required cols="58" placeholder="Your CSS" name="css"><?php echo $css;?></textarea><br>
				<input name="cssset" type="submit" value="Set"> <small>max limit: 500 characters</small>
			</form>
			<div class="footer">
				made by spiltbrains. <a href="terms.txt">terms & conditions</a>
			</div>
		</div>
	</body>
</html>

