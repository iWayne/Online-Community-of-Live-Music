<html>

	<?php

	include ("include.php");
	date_default_timezone_set('US/Eastern');
	//check if the concert exists, if not redirects back to homepage
	if ($stmt = $mysqli->prepare("select ssname from subgenre where ssid = ?")) {
		$stmt->bind_param("i", $_GET["sid"]);
		$stmt->execute();
		$stmt->bind_result($ssname);
		if($stmt->fetch()){
			$ssname = htmlspecialchars($ssname);
			echo "<title>Genre: $ssname</title>\n";
			echo "$ssname: <br />\n";
		}
		else {
			echo "Genre are not found. \n";
			echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
			header("refresh: 3; index.php");
		}
		$stmt->close();
	}
	echo "<br />\n";
	//List Realted Bands
	if(isset($ssname)){
		echo "Related Band: ";
		echo "<br />\n";
		if ($stmt = $mysqli->prepare("select username,uid from customer natural join plays where sid=?")) {
			$stmt->bind_param("i", $_GET["sid"]);
			$stmt->execute();
			$stmt->bind_result($username, $uid);
			while ($stmt->fetch()) {
				echo '<a href="view.php?uid=';
				echo htmlspecialchars($uid);
				$username = htmlspecialchars($username);
				echo "\">$username</a><br />\n";
			}
			$stmt->close();
		}
	}
	echo "<br />\n";
	//List Related Concert
	if(isset($ssname)){
		echo "Related Concert: ";
		echo "<br />\n";
		if ($stmt = $mysqli->prepare("select cname,cid from concert where sid=?")) {
			$stmt->bind_param("i", $_GET["sid"]);
			$stmt->execute();
			$stmt->bind_result($cname, $cid);
			while ($stmt->fetch()) {
				echo '<a href="viewConcert.php?cid=';
					echo htmlspecialchars($cid);
					$cname = htmlspecialchars($cname);
					echo "\">$cname</a><br />\n";
			}
			$stmt->close();
		}
	}
	echo "<br />\n";
	//List related User
	if(isset($ssname)){
		echo "Users who also likes this genre: ";
		echo "<br />\n";
		if ($stmt = $mysqli->prepare("select username,uid from customer natural join likes where sid=?")) {
			$stmt->bind_param("i", $_GET["sid"]);
			$stmt->execute();
			$stmt->bind_result($username, $uid);
			while ($stmt->fetch()) {
				echo '<a href="view.php?uid=';
				echo htmlspecialchars($uid);
				$username = htmlspecialchars($username);
				echo "\">$username</a><br />\n";
			}
			$stmt->close();
		}
	}
	echo "<br />\n";
	echo '<br /><a href="index.php">Go back</a>';
	?>

</html>