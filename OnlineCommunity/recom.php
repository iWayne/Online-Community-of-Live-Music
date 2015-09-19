<html>

	<?php

	include ("include.php");
	//Recommendation
	echo "Recommendation: ";
	echo "<br />\n";
	echo "<br />\n";
	if(!isset($_SESSION["username"])) {
		//Top 5 Concert
		echo "Top 5 Popular Concert: ";
		echo "<br />\n";
		if ($stmt = $mysqli->prepare("select c.cid,c.cname from concert as c join (select cid,count(uid)
		from plan group by cid order by count(uid) desc limit 5) as p where c.cid=p.cid")) {
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
		echo "<br />\n";
		echo "<br />\n";
		//Top 5 Band
		echo "Top 5 Popular Band: ";
		echo "<br />\n";
		if ($stmt = $mysqli->prepare("select b.uid,b.username from customer as b join (select bid,count(uid)
		from fan group by bid order by count(uid) desc limit 5) as f where b.uid=f.bid")) {
			$stmt->execute();
			$stmt->bind_result($bid,$username);
			while($stmt->fetch()) {
				echo '<a href="view.php?uid=';
				echo htmlspecialchars($bid);
				$cname = htmlspecialchars($username);
				echo "\">$username</a>, ";
			}
			$stmt->close();
		}
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
			
		}
		echo "<br />\n";
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
		}
		echo "<br />\n";
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
		}
		echo "<br />\n";
		echo "<br />\n";
		
	}

	?>

</html>