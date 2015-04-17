<html>
	<title>Blog Example</title>

	<?php

	include ("include.php");

	//if the user is already logged in, redirect them back to homepage
	if(!isset($_SESSION["username"])) {
		echo "Welcome to the blog example, you are not logged in. <br /><br >\n";
		echo 'You must login in order to modify your profile
		<a href="login.php">login</a> or <a href="register.php">register</a> if you don\'t have an account yet.';
		echo "\n";
	}
	else if($_SESSION["role"]=='0'){
		//Check whether fan a band
		$userflag=false;
		if ($stmt = $mysqli->prepare("select role from customer where uid=?")) {
			$stmt->bind_param("i", $_GET["uid"]);
			$stmt->execute();
			$stmt->bind_result($role);
			if($stmt->fetch()) {
				if($role=='0'){
					$userflag=true;
				}
			}
		}
		$stmt->close();
		if($userflag){
			//Check Whether follow before:
			if ($stmt = $mysqli->prepare("select followingid from follow where followedID = ? and followingid=?")) {
				$stmt->bind_param("ii", $_GET["uid"],$_SESSION["uid"]);
				$stmt->execute();
				$stmt->bind_result($uid);
				if($stmt->fetch()) {
					echo "You followed before!\n<br />";
					echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
					header("refresh: 3; index.php");
				}
				else {
					//To follow
					if ($stmt = $mysqli->prepare("insert into follow values (?,?,now())")) {
						$stmt->bind_param("ii",$_GET["uid"],$_SESSION["uid"]);
						$stmt->execute();
						echo "Successfully Fan!\n<br />";
						echo "You will be redirected to Home Page in 3 seconds or click <a href=\"index.php\">here</a>.\n";
						header("refresh: 3; index.php");
					}

				}
				$stmt->close();
			}
		}else{
			echo "Invalid User ID!\n<br />";
			echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
			header("refresh: 3; index.php");
		}
	}else{
		//if login as a band
		echo "You login as a band. \n";
		echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
		header("refresh: 3; index.php");
	}


	?>

</html>