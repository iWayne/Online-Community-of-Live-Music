<html>
	<title>Home Page</title>
	<body>
	<center>
	<img src='images/homepage.jpg'>
	
	<?php
	echo "<br />\n";
	include ("include.php");
	if(!isset($_SESSION["username"])) {
		echo "Welcome to the our project, you are not logged in.";
		echo "<table>";
		echo "<tr bgColor=#CCFFFF><td>";
		echo '<a href="login.php">login</a> ';
		echo "</td><td>";
		echo ' <a href="register.php">register</a> ';
		echo "</td><td>";
		echo ' <a href="search.php">search</a>';
		echo "</td></ tr>";
		echo "</table>";
	}
	else {
		$username = htmlspecialchars($_SESSION["username"]);
		echo "Welcome $username. You are logged in.<br /><br />\n";
		echo "<table>";
		echo "<tr bgColor=#CCFFFF><td>";
		echo '<a href="view.php?uid=';
		echo htmlspecialchars($_SESSION["uid"]);
		echo '">your profile</a>';
		echo "</td><td>";
		if($_SESSION["role"]=='0'){
			echo ' <a href="modifyuser.php">modify profile</a>';
		}else{
			echo ' <a href="modifyband.php">modify profile</a> ';
		}
		echo "</td><td>";
		echo ' <a href="search.php">search</a>';
		echo "</td><td>";
		echo ' <a href="createConcert.php">create concerts</a>';
		echo "</td><td>";
		echo ' <a href="rate.php">rate concerts</a>';
		echo "</td><td>";
		echo ' <a href="post.php">post</a>';
		echo "</td><td>";
		if($_SESSION["role"]=='0'){
			echo '<a href="likegenre.php">choose genres</a>';
		}else{
			echo '<a href="playgenre.php">choose genres</a> ';
		}
		echo "</td><td>";
		echo ' <a href="logout.php">Logout</a>.';
		echo "</td></tr>";
		echo "</table>";
	}
	echo "<br /><br />\n";
		//Recommendation
	echo "Recommendation";
	echo "<br />\n";
	echo "<br />\n";
	if(!isset($_SESSION["username"])) {
		//Top 5 Concert
		echo "Top 5 Popular Concert: ";
		echo "<table>";
		echo "<tr>";
		if ($stmt = $mysqli->prepare("select c.cid,c.cname from concert as c join (select cid,count(uid)
		from plan group by cid order by count(uid) desc limit 5) as p where c.cid=p.cid")) {
			$stmt->execute();
			$stmt->bind_result($cid,$cname);
			while($stmt->fetch()) {
				echo "<td>";
				echo '<a href="viewConcert.php?cid=';
				echo htmlspecialchars($cid);
				$cname = htmlspecialchars($cname);
				echo "\">$cname</a>, ";
				echo "</td>";
			}
			$stmt->close();
			echo "<br />\n";
		}
		echo "</tr>";
		echo "</table>";
		echo "<br />\n";
		//Top 5 Band
		echo "Top 5 Popular Band: ";
		echo "<table>";
		echo "<br />\n";
		if ($stmt = $mysqli->prepare("select b.uid,b.username from customer as b join (select bid,count(uid)
		from fan group by bid order by count(uid) desc limit 5) as f where b.uid=f.bid")) {
			$stmt->execute();
			$stmt->bind_result($bid,$username);
			echo "<tr>";
			while($stmt->fetch()) {
				echo "<td>";
				echo '<a href="view.php?uid=';
				echo htmlspecialchars($bid);
				$cname = htmlspecialchars($username);
				echo "\">$username</a>, ";
				echo "</td>";
			}
			echo "</tr>";
			$stmt->close();
			echo "</table>";
			echo "<br />\n";
		}
		echo "</table>";
	}else if($_SESSION["role"]=='0'){
		//User login
		//The 5 earliest upcoming concerts picked by the users who you followed
		echo "The Earliest Coming Concerts From Your Followed Users:";
		echo "<br />\n";
		if ($stmt = $mysqli->prepare("select ct.cid,ct.cname from customer as c join follow as f join picklist as p join concert as ct
			where c.uid=f.followingID and f.followedID=p.uid and p.cid=ct.cid and c.uid=? order by ct.cDatetime desc limit 5")) {
			$stmt->bind_param("i", $_SESSION["uid"]);
			$stmt->execute();
			$stmt->bind_result($cid,$cname);
			while($stmt->fetch()) {
				echo '<a href="viewConcert.php?cid=';
				echo htmlspecialchars($cid);
				$cname = htmlspecialchars($cname);
				echo "\">$cname</a>, ";
			}
			$stmt->close();
			echo "<br />\n";
		}
		echo "<br />\n";
		//The 5 earliest upcoming concerts by the band fanned
		echo "The Earliest Coming Concerts From Your Liked Bands:";
		echo "<br />\n";
		if ($stmt = $mysqli->prepare("select ct.cid,ct.cname from customer as c join fan as f join concert as ct
			where c.uid=f.uid and f.bid=ct.bid and c.uid=? order by ct.cDatetime desc limit 5")) {
				$stmt->bind_param("i", $_SESSION["uid"]);
				$stmt->execute();
				$stmt->bind_result($cid,$cname);
				while($stmt->fetch()) {
					echo '<a href="viewConcert.php?cid=';
					echo htmlspecialchars($cid);
					$cname = htmlspecialchars($cname);
					echo "\">$cname</a>, ";
				}
				$stmt->close();
				echo "<br />\n";
		}
		echo "<br />\n";
		//The 5 earliest upcoming concerts in the genre you liked
		echo "The 5 Earliest Upcoming Concerts in the genre you liked:";
		echo "<br />\n";
		if ($stmt = $mysqli->prepare("select ct.cid,ct.cname from customer as c join likes as l join concert as ct
				where c.uid=l.uid and l.sid=ct.sid and c.uid=? order by ct.cDatetime desc;")) {
				$stmt->bind_param("i", $_SESSION["uid"]);
				$stmt->execute();
				$stmt->bind_result($cid,$cname);
				while($stmt->fetch()) {
					echo '<a href="viewConcert.php?cid=';
					echo htmlspecialchars($cid);
					$cname = htmlspecialchars($cname);
					echo "\">$cname</a>, ";
				}
				$stmt->close();
		}
	}else{
		//Band login
		//The newest comment in your concert
		echo "The Newest Comments in your concert: ";
		echo "<br />\n";
		if ($stmt = $mysqli->prepare("select * from rate as r join concert as ct
		where r.cid=ct.cid and ct.bid=? order by r.rDateTime desc limit 5;")) {
				$stmt->bind_param("i", $_SESSION["uid"]);
				$stmt->execute();
				$stmt->bind_result($cid,$cname);
				while($stmt->fetch()) {
					echo '<a href="viewConcert.php?cid=';
					echo htmlspecialchars($cid);
					$cname = htmlspecialchars($cname);
					echo "\">$cname</a>, ";
				}
				$stmt->close();
				echo "<br />\n";
		}
		echo "<br />\n";
	}
	?>
</center>
</body>
</html>