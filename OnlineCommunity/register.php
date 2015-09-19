<html>
	<title>Register</title>
	<body>
	<center>
	<img src='images/concert.jpeg'>
	<?php
	echo "<br />\n";
	echo "<br />\n";
	include "include.php";

	//if the user is already logged in, redirect them back to homepage
	if(isset($_SESSION["username"])) {
		echo "You are already logged in. ";
		echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.";
		header("refresh: 3; index.php");
	}
	else {
		//if the user have entered _all_ entries in the form, insert into database
		if(isset($_POST["username"]) && isset($_POST["password"])&& isset($_POST["type"])) {
			if($_POST["password"]!=$_POST["password2"]){
				echo "The two passwords are different.";
				echo "You will be redirected in 3 seconds or click <a href=\"register.php\">here</a>.";
				header("refresh: 3; register.php");
			}
			//check if username already exists in database
			else if ($stmt = $mysqli->prepare("select username from customer where username = ?")) {
				$stmt->bind_param("s", $_POST["username"]);
				$stmt->execute();
				$stmt->bind_result($username);
				if ($stmt->fetch()) {
					echo "That username already exists. ";
					echo "You will be redirected in 3 seconds or click <a href=\"register.php\">here</a>.";
					header("refresh: 3; register.php");
					$stmt->close();
				}
				//if not then insert the entry into database, note that uid is set by auto_increment
				else {
					$stmt->close();
					if ($stmt = $mysqli->prepare("insert into customer (username,password,role,lastAccessDT) values (?,?,?,now())")) {
						$stmt->bind_param("sss", $_POST["username"], md5($_POST["password"]),$_POST["type"]);
						$stmt->execute();
						//fetch the uid from DB
						$stmt = $mysqli->prepare("select uid, username, password, role from customer where username = ?");
						$stmt->bind_param("s", $_POST["username"]);
						$stmt->execute();
						$stmt->bind_result($uid, $username, $password, $role);
						$stmt->fetch();
						//login
						$_SESSION["uid"] = $uid;
						$_SESSION["username"] = $username;
						$_SESSION["password"] = $password;
						$_SESSION["role"] = $role;
						$_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"];
						$stmt->close();
						//request the space in DB for more information of the user
						if($_POST["type"]=='0'){
							if($stmt = $mysqli->prepare("insert into users (uid, trust) values (?, 4)")){
								$stmt->bind_param("i",$uid);
								$stmt->execute();
								$stmt->close();
								echo "Registration complete, click <a href=\"modifyuser.php\">here</a> to add more information, ";
							}
						}else{
							if($stmt = $mysqli->prepare('insert into band (uid, authStatus) values (?, "pending")')){
								$stmt->bind_param("i",$uid);
								$stmt->execute();
								$stmt->close();
								echo "Registration complete, <a href=\"modifyband.php\">Add More Background</a>, ";
							}
						}
						echo "or <a href=\"index.php\">Home Page</a>.";
					}
				}
			}
		}
		//if not then display registration form
		else {
			echo "Enter your information below: <br /><br />\n";
			echo '<form action="register.php" method="POST">';
			echo "\n";
			echo "<table>";
			echo "<tr><td>";
			echo 'Username: ';
			echo "</td><td>";
			echo '<input type="text" name="username" />';
			echo "</td></ tr>";
			echo "<tr><td>";
			echo 'Password: ';
			echo "</td><td>";
			echo '<input type="password" name="password" />';
			echo "</td></ tr>";
			echo "<tr><td>";
			echo 'Password again: ';
			echo "</td><td>";
			echo '<input type="password" name="password2" />';
			echo "</td></ tr>";
			echo "<tr><td>";
			echo "Type: ";
			echo "</td><td>";
			echo "<input type='radio' name='type' value='0'>Fan
			<input type='radio' name='type' value='1'>Band";
			echo "</td></ tr>";
			echo "</table>";
			echo "<br />\n";
			echo '<input type="submit" value="Submit" />';
			echo "\n";
			echo '</form>';
			echo "\n";
			echo '<br /><a href="index.php">Go back</a>';

		}
	}
	$mysqli->close();


	?>

</center>
</body>
</html>