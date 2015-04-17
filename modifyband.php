<html>
	<body>
	<center>
	<img src='images/concert.jpeg'>
	<?php
	echo "<br />\n";
	echo "<br />\n";
	
	include ("include.php");

	//if the user is already logged in, redirect them back to homepage
	if(!isset($_SESSION["username"])) {
		echo "Welcome to the blog example, you are not logged in. <br /><br >\n";
		echo 'You must login in order to modify your profile
		<a href="login.php">login</a> or <a href="register.php">register</a> if you don\'t have an account yet.';
		echo "\n";
	}
	else {
		if($_SESSION["role"]=='1'){
			$stmt = $mysqli->prepare("select link, bio from band where uid = ?");
			$stmt->bind_param("i", $_SESSION["uid"]);
			$stmt->execute();
			$stmt->bind_result($link, $bio);
			$stmt->fetch();
			$stmt->close();
			if(!empty($_POST["link"])||!empty($_POST["bio"])){
				echo "Saved!\n<br />";
			}
			if(!empty($_POST["link"])){
				if(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$_POST["link"])){
					$link=$_POST["link"];
				}else{
					echo "Link is invalid!<br />\n";
				}
			}
			if(!empty($_POST["bio"])){
				if(strlen($_POST["bio"])<=200){
					$bio=$_POST["bio"];
				}
				else{
					echo "Biography is invalid!<br />\n";
				}
			}
			$stmt = $mysqli->prepare("update band set link=?, bio=? where uid=?");
			$stmt->bind_param("ssi",$link,$bio,$_SESSION["uid"]);
			$stmt->execute();
			$stmt->close();
			echo "\n<br />";

			echo "Modify your information below: <br /><br />\n";
			echo '<form action="modifyband.php" method="POST">';
			echo "\n<br />";
			echo 'Website: <input type="text" name="link" value="';
			if(!empty($link)){$link=htmlspecialchars($link);echo $link;}
			echo '"/><br />';
			echo "\n<br />";
			echo 'Biography: <textarea name="bio" rows="5" cols="40"/>';
			if(!empty($bio)){$bio=htmlspecialchars($bio);echo $bio;}
			echo '</textarea> less than 200 letters<br />';
			echo "\n<br />";
			echo '<input type="submit" value="Save" />';
			echo "\n";
			echo '</form>';
			echo "\n";
			echo '<br /><a href="index.php">Go back</a>';
		}
		else{
			echo "You login as a band, redirect to Home Page or click <a href=\"index.php\">here</a>";
			header("refresh: 3; index.php");
		}
	}

	?>
</center>
</body>
</html>