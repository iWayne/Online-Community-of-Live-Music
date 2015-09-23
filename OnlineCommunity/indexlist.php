<html>
	<title>Home Page</title>

	<?php

	include ("include.php");

	if(!isset($_SESSION["username"])) {
		echo "Welcome to the blog example, you are not logged in. <br /><br >\n";
		echo 'You may view the blogs listed below, <a href="login.php">login</a> to post on your blog or <a href="register.php">register</a> if you don\'t have an account yet.';
		echo "\n";
	}
	else {
		$username = htmlspecialchars($_SESSION["username"]);
		echo "Welcome $username. You are logged in.<br /><br />\n";
		echo 'You may view the blogs listed below, <a href="view.php?uid=';
		echo htmlspecialchars($_SESSION["uid"]);
		echo '">go to your Page</a>, or <a href="post.php">post on your Page</a>, or ';
		if($_SESSION["role"]=='0'){
			echo '<a href="modifyuser.php">modify</a>';
		}else{
			echo '<a href="modifyband.php">modify</a> ';
		}
		echo ' or <a href="logout.php">logout</a>.';
		echo "\n";
	}
	echo "<br /><br />\n";
	if ($stmt = $mysqli->prepare("select username, uid from customer order by username")) {
		$stmt->execute();
		$stmt->bind_result($username, $uid);
		while ($stmt->fetch()) {
			echo '<a href="view.php?uid=';
			echo htmlspecialchars($uid);
			$username = htmlspecialchars($username);
			echo "\">$username's profile</a><br />\n";
		}
		$stmt->close();
		$mysqli->close();
	}

	?>

</html>