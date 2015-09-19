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
		//Check Whether plan before:
		if ($stmt = $mysqli->prepare("select uid from plan where cid = ? and uid=?")) {
			$stmt->bind_param("ii", $_GET["cid"],$_SESSION["uid"]);
			$stmt->execute();
			$stmt->bind_result($uid);
		if($stmt->fetch()) {
			echo "You have planed before!\n<br />";
			echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
			header("refresh: 3; index.php");
		}
		else {
			//add to plan
			if ($stmt = $mysqli->prepare("insert into plan values (?,?,now())")) {
			$stmt->bind_param("ii", $_SESSION["uid"],$_GET["cid"]);
			$stmt->execute();
			echo "Successfully plan!\n<br />";
			echo "You will be redirected to Home Page in 3 seconds or click <a href=\"index.php\">here</a>.\n";
			header("refresh: 3; index.php");
		}
		
		}
		$stmt->close();
	}}else{
		//if login as a band
		echo "You login as a band. \n";
		echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
		header("refresh: 3; index.php");
	}

	?>

</html>