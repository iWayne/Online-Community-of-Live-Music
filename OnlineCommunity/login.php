<html>
	<title>Login</title>
<body>
	<center>
	<img src='images/concert.jpeg'>
	
	<?php
	echo "<br />\n";
	include "include.php";

	//if the user is already logged in, redirect them back to homepage
	if(isset($_SESSION["username"])) {
		echo "You are already logged in. \n";
		echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
		header("refresh: 3; index.php");
	}
	else {
		//if the user have entered both entries in the form, check if they exist in the database
		if(isset($_POST["username"]) && isset($_POST["password"])) {
			//check if entry exists in database
			if ($stmt = $mysqli->prepare("select uid, username, password, role from customer where username = ? and password = ?")) {
				$stmt->bind_param("ss", $_POST["username"], md5($_POST["password"]));
				$stmt->execute();
				$stmt->bind_result($uid, $username, $password, $role);
				//if there is a match set session variables and send user to homepage
				if ($stmt->fetch()) {
					$_SESSION["uid"] = $uid;
					$_SESSION["username"] = $username;
					$_SESSION["password"] = $password;
					$_SESSION["role"] = $role;
					$_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"]; //store clients IP address to help prevent session hijack
					echo "Login successful. \n";
					echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.";
					header("refresh: 3; index.php");
				}
				//if no match then tell them to try again
				else {
					sleep(1); //pause a bit to help prevent brute force attacks
					echo "Your username or password is incorrect, click <a href=\"login.php\">here</a> to try again.";
				}
				$stmt->close();
				if(isset($_SESSION["uid"])){
					$nowtime=strtotime(date("y-m-d h:i:s"));
					$diff;
					if ($stmt = $mysqli->prepare("select regTime from customer where uid=?")) {
						$stmt->bind_param("i",$_SESSION["uid"]);
						$stmt->execute();
						$stmt->bind_result($regtime);
						//if there is a match set session variables and send user to homepage
						if ($stmt->fetch()) {
							$diff=floor(($nowtime-strtotime($regtime))/86400/30);
							echo "debug ".$diff;
						}
						$stmt->close();
					}
					//Update Trust Score
					if($diff>10){$diff=10;} 	
					if($diff>4){
						
						if ($stmt = $mysqli->prepare("update users set trust=$diff where uid=?")){
							$stmt->bind_param("i",$_SESSION["uid"]);
							$stmt->execute();
							$stmt->close();
						}
					}
					//Update Access time
					if ($stmt = $mysqli->prepare("update customer set lastAccessDT=now() where uid=?")){
						$stmt->bind_param("i",$_SESSION["uid"]);
						$stmt->execute();
						$stmt->close();
					}
				}
			}
		}
		//if not then display login form
		else {
			echo "Enter your username and password below: <br /><br />\n";
			echo '<form action="login.php" method="POST">';
			echo "\n";
			echo 'Username: <input type="text" name="username" /><br />';
			echo "\n";
			echo 'Password: <input type="password" name="password" /><br />';
			echo "\n";
			echo "<br />\n";
			echo '<input type="submit" value="Submit" />';
			echo "\n";
			echo '</form>';
			echo "\n";
			echo '<br /><a href="index.php">Go back</a>';
		}
	}
	?>

</center>
</body>
</html>