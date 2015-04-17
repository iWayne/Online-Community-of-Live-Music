<html>
	<body>
	<center>
	<img src='images/concert.jpeg'>
	<?php
	echo "<br />\n";
	echo "<br />\n";
	include ("include.php");
	date_default_timezone_set('US/Eastern');
	echo "<table>";
	//check if the concert exists, if not redirects back to homepage
	if ($stmt = $mysqli->prepare("select * from concert where cid = ?")) {
		$stmt->bind_param("s", $_GET["cid"]);
		$stmt->execute();
		$stmt->bind_result($cid,$cname,$bid,$vid,$sid,$ctime,$link,$postid,$posttime);
		if($stmt->fetch()) {
			$cname = htmlspecialchars($cname);
			echo "<tr bgColor=#CCFFFF><td>";
			echo "$cname: ";
			echo "</td></tr>";
			echo "</table>";
			echo "<table >";
			echo "<tr bgColor=#CCFF00><td align=left>";
			echo "Information: ";
			echo "</td></tr>";
			echo "<tr><td align=left>";
			echo "Time: ".htmlspecialchars($ctime);
			echo "</td></tr>";
			echo "<tr><td align=left>";
			echo "Link: ".htmlspecialchars($link);
			echo "</td></tr>";
			echo "<br />\n";
		}
		else {
			echo "Concert are not found. \n";
			echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
			header("refresh: 3; index.php");
		}
		$stmt->close();
	}
	//If login as a user, show plan and pickList option
	if(isset($_SESSION["uid"])&&$_SESSION["role"]=='0') {
		echo "<tr><td align=left>";
		echo '<a href="plan.php?cid=';
		echo htmlspecialchars($cid);
		echo "\">Add to your plan</a>";
		echo "</td></tr>";
		echo "<tr><td align=left>";
		echo '<a href="pick.php?cid=';
		echo htmlspecialchars($cid);
		echo "\">Add to your PickList</a>";
		echo "</td></tr>";
		
	}
	//List Band
	if(!empty($bid)){
		if ($stmt = $mysqli->prepare("select username from customer where uid = ?")) {
			$stmt->bind_param("s", $bid);
			$stmt->execute();
			$stmt->bind_result($bname);
			if($stmt->fetch()) {
				echo "<tr><td align=left>";
				$bname = htmlspecialchars($bname);
				echo 'Band: <a href="view.php?uid=';
				echo htmlspecialchars($bid);
				$bname = htmlspecialchars($bname);
				echo "\">$bname</a><br />\n";
				echo "</td></tr>";
			}
			$stmt->close();
		}
	}
	//List Genre

	if($stmt = $mysqli->prepare("select ssname from concert join subgenre
	where concert.sid=subgenre.ssid and cid = ?")) {
		echo "<tr><td align=left>";
		echo "Genre: ";
		$stmt->bind_param("i", $_GET["cid"]);
		$stmt->execute();
		$stmt->bind_result($ssname);
		if($stmt->fetch()) {
			echo htmlspecialchars($ssname);
		}
		$stmt->close();
		echo "</td></tr>";
	}
	echo "\n<br />";

	//List Venue

	if(!empty($vid)){
		if ($stmt = $mysqli->prepare("select vname,street,city,country from venue where vid = ?")) {
			$stmt->bind_param("s", $vid);
			$stmt->execute();
			$stmt->bind_result($vname,$street,$city,$country);
			if($stmt->fetch()) {
				echo "<tr><td align=left>";
				$vname = htmlspecialchars($vname);
				echo "Venue: ". $vname;
				echo "</td></tr>";
				echo "<tr><td align=left>";
				echo "Address: ".htmlspecialchars($street).", ".htmlspecialchars($city).", ".htmlspecialchars($country);
				echo "</td></tr>";
			}
			$stmt->close();
		}
	}

	//List Post Man
	if(!empty($postid)){
		if ($stmt = $mysqli->prepare("select username from customer where uid = ?")) {
			$stmt->bind_param("s", $postid);
			$stmt->execute();
			$stmt->bind_result($pname);
			if($stmt->fetch()) {
				echo "<tr><td align=left>";
				$pname = htmlspecialchars($pname);
				echo 'Post ID: <a href="view.php?uid=';
				echo htmlspecialchars($postid);
				echo "</td></tr>";
				echo "<tr><td align=left>";
				$pname = htmlspecialchars($pname);
				echo "\">$pname</a><br />\n";
				echo "Post time: ".htmlspecialchars($posttime);
				echo "</td></tr>";
			}
			$stmt->close();
		}
	}

	echo "<br />\n";
	//List comment if happened
	$nowdatetime=date('Y-m-d H:i:s');
	if(!empty($ctime)&&strtotime($ctime)< strtotime($nowdatetime)){
		echo "<tr bgColor=#CCFF00><td align=left>";
		echo "Comment: ";
		echo "</td></tr>";
		if($stmt = $mysqli->prepare("select rate.uid,rating,rDateTime,comments,username
		from rate join customer where rate.uid=customer.uid and cid = ? order by rDateTime")) {
			$stmt->bind_param("i", $_GET["cid"]);
			$stmt->execute();
			$stmt->bind_result($uid,$rating,$rTimestamp,$comments,$uname);
			while($stmt->fetch()) {
				echo "<tr><td align=left>";
				echo '<a href="view.php?uid=';
				echo htmlspecialchars($uid);
				$uname = htmlspecialchars($uname);
				echo "\">$uname</a>";
				echo " Rate: ".htmlspecialchars($rating)." At ".htmlspecialchars($rTimestamp);
				echo "</td></tr>";
				echo "<tr><td align=left>";
				echo htmlspecialchars($comments);
				echo "</td></tr>";
			}
		}
	}
	echo "</table>";
	echo "<br />\n";
	echo "<table>";
	echo "<tr><td>";
	echo '<br /><a href="index.php">Go back</a>';
	echo "</td></tr>";
	echo "</table>";
	echo "<br />\n";
	?>
</center>
</body>
</html>