<html>
	<body>
	<center>
	<img src='images/concert.jpeg'>
	<?php
	echo "<br />\n";
	echo "<br />\n";
	include ("include.php");
	date_default_timezone_set('US/Eastern');
	//check if the user exists and prints out username, if not redirects back to homepage
	if ($stmt = $mysqli->prepare("select username,role from customer where uid = ?")) {
		$stmt->bind_param("s", $_GET["uid"]);
		$stmt->execute();
		$stmt->bind_result($username,$role);
		if($stmt->fetch()) {
			echo "<table>";
			echo "<tr bgColor=#CCFFFF><td>";
			$username = htmlspecialchars($username);
			echo "$username's Profile:";
			echo "</td></ tr>";
			echo "</table>";
		}
		else {
			echo "Users are not found. \n";
			echo "You will be redirected in 3 seconds or click <a href=\"index.php\">here</a>.\n";
			header("refresh: 3; index.php");
		}
		$stmt->close();
	}
	

	//If the user is also the one who is logged in, show modify option
	if(isset($_SESSION["uid"])) {
		echo "<table>";
		echo "<tr><td>";
		if($_SESSION["uid"] == $_GET["uid"]){
			if($role=='0'){
				echo '<a href="modifyuser.php">Modify</a>';
			}else{
				echo '<a href="modifyband.php">Modify</a>';
			}
		}
		else if($_SESSION["role"]=='0'){
			if($role=='0'){
				echo '<a href="follow.php?uid=';
				echo htmlspecialchars($_GET["uid"]);
				echo "\">Follow</a>";
			}else{
				echo '<a href="fan.php?bid=';
				echo htmlspecialchars($_GET["uid"]);
				echo "\">Be a Fan</a>";
			}
		}
		echo "</td></ tr>";
		echo "</table>";
	}
echo "<table>";	
	//print out all the messages from this user in a pretty table
	if($role=='0'){
		$pickflag=false;
		//List general information
		if ($stmt = $mysqli->prepare("select regTime, lastAccessDT, trust, birthday, email,
		city, pickListName, updatedDT from customer natural join users where uid = ?")) {
			$stmt->bind_param("i", $_GET["uid"]);
			$stmt->execute();
			$stmt->bind_result($regTime,$lastAccessDT,$trust,$birthday,$email,$city,
			$pickListName,$updateDT);
			if($stmt->fetch()) {
				echo "<tr><td>";
				echo "Register Time: ";
				echo "</td><td>";
				echo htmlspecialchars($regTime);
				echo "</td></ tr>";
				echo "<tr><td>";
				echo "Last Login Time: ";
				echo "</td><td>";
				echo htmlspecialchars($regTime);
				echo "</td></ tr>";
				echo "<tr><td>";
				echo "Trust Score: ";
				echo "</td><td>";
				echo htmlspecialchars($trust);
				echo "</td></ tr>";
				echo "<tr><td>";
				echo "Year of Birth: ";
				echo "</td><td>";
				echo htmlspecialchars($birthday);
				echo "</td></ tr>";
				echo "<tr><td>";
				echo "Eamil: ";
				echo "</td><td>";
				echo htmlspecialchars($email);
				echo "</td></ tr>";
				echo "<tr><td>";
				echo "City: ";
				echo "</td><td>";
				echo htmlspecialchars($city);
				echo "</td></ tr>";
				$stmt->close();
			}
			//List Genre
			echo "<tr><td VALIGN='TOP'>";
			echo "Genre: ";
			echo '</td><td width="50">';
			if($stmt = $mysqli->prepare("select ssname from likes natural join subgenre where uid = ?")) {
				$stmt->bind_param("i", $_GET["uid"]);
				$stmt->execute();
				$stmt->bind_result($ssname);
				while($stmt->fetch()) {
					echo htmlspecialchars($ssname).", ";
				}
				$stmt->close();
			}
			echo "</td></ tr>";
			//List Plan
			echo "<tr><td>";
			echo "Plan: ";
			echo "</td><td>";
			if($stmt = $mysqli->prepare("select cid,cname,cdatetime from plan natural join concert where uid = ?")){
				$stmt->bind_param("i", $_GET["uid"]);
				$stmt->execute();
				$stmt->bind_result($cid,$cname,$cdatetime);
				$nowdatetime=date('Y-m-d H:i:s');
				while($stmt->fetch()) {
					if(strtotime($cdatetime)>=strtotime($nowdatetime)){
						echo '<a href="viewConcert.php?cid=';
						echo htmlspecialchars($cid);
						$cname = htmlspecialchars($cname);
						echo "\">$cname</a>, ";
					}
				}
				$stmt->close();
			}
			echo "</td></ tr>";
			//List Rated
			echo "<tr><td>";
			echo "Rated Concert: ";
			echo "</td><td>";
			if($stmt = $mysqli->prepare("select cid,cname,cdatetime from rate natural join concert where uid = ?")) {
				$stmt->bind_param("i", $_GET["uid"]);
				$stmt->execute();
				$stmt->bind_result($cid,$cname,$cdatetime);
				$nowdatetime=date('Y-m-d H:i:s');
				while($stmt->fetch()) {
						echo '<a href="viewConcert.php?cid=';
						echo htmlspecialchars($cid);
						$cname = htmlspecialchars($cname);
						echo "\">$cname</a>, ";
				}
				$stmt->close();
			}
			echo "</td></ tr>";
			//List Picklist
			if(empty($pickListName)){
				echo "<tr bgColor=#CC99FF><td>";
				echo "This user has no recommend list.";
				echo "</td></ tr>";
			}else{
				echo "<tr bgColor=#CC99FF><td>";
				echo htmlspecialchars($pickListName);
				echo "</td><td>";
				echo " updated time ".htmlspecialchars($updateDT);
				$pickflag=true;
				echo "</td></ tr>";
			}
			if($pickflag){
				if ($stmt = $mysqli->prepare("select Concert.cid, Concert.cname from PickList join Concert
				where PickList.cid=Concert.cid and PickList.uid = ?")) {
					$stmt->bind_param("i",$_GET["uid"]);
					$stmt->execute();
					$stmt->bind_result($cid,$cname);
					while($stmt->fetch()) {
						echo "<tr><td></td><td>";
						echo '<a href="viewConcert.php?cid=';
						echo htmlspecialchars($cid);
						$cname = htmlspecialchars($cname);
						echo "\">$cname</a>";
						echo "</td></ tr>";
					}
					$stmt->close();
				}
			}
		}
	}else{
		//Show gnereally information of band
		if ($stmt = $mysqli->prepare("select regTime, authStatus, link, bio
		from customer natural join band where uid = ?")) {
			$stmt->bind_param("i", $_GET["uid"]);
			$stmt->execute();
			$stmt->bind_result($regTime,$authStatus,$link,$bio);
			if($stmt->fetch()) {
				echo "<tr><td>";
				echo "Register Time: ";
				echo "</td><td>";
				echo htmlspecialchars($regTime);
				echo "</td></ tr>";
				echo "<tr><td>";
				echo "Status: ";
				echo "</td><td>";
				echo htmlspecialchars($authStatus);
				echo "</td></ tr>";
				echo "<tr><td>";
				echo "Website: ";
				echo "</td><td>";
				echo htmlspecialchars($link);
				echo "</td></ tr>";
				echo "<tr><td></td></tr>";
				echo '<tr><td VALIGN="TOP">';
				echo "Biography: ";
				echo '</td><td width="200">';
				echo htmlspecialchars($bio);
				echo "</td></ tr>";
				echo "<tr><td></td></tr>";
			}
			$stmt->close();
		}
		//List Genre
		echo '<tr><td VALIGN="TOP">';
		echo "Genre: ";
		echo '</td><td width="50">';
		if($stmt = $mysqli->prepare("select ssname from plays natural join subgenre where uid = ?")) {
			$stmt->bind_param("i", $_GET["uid"]);
			$stmt->execute();
			$stmt->bind_result($ssname);
			while($stmt->fetch()) {
				echo htmlspecialchars($ssname).", ";
			}
			$stmt->close();
		}
		echo "</td></ tr>";
		//List upcoming concert
		echo "<tr><td></td></tr>";
		echo "<tr><td>";
		echo "Upcoming Concert: ";
		echo "</td><td>";
		if($stmt = $mysqli->prepare("select cid,cname,cDatetime from concert where bid = ? order by cDatetime")) {
			$stmt->bind_param("i", $_GET["uid"]);
			$stmt->execute();
			$stmt->bind_result($cid,$cname,$cdatetime);
			$nowdatetime=date('Y-m-d H:i:s');
			while($stmt->fetch()) {
				if(strtotime($cdatetime)>=strtotime($nowdatetime)){
					echo '<a href="viewConcert.php?cid=';
					echo htmlspecialchars($cid);
					$cname = htmlspecialchars($cname);
					echo "\">$cname</a>,  ";
				}
			}
			$stmt->close();
		}
		echo "</td></ tr>";
		//List happened concert
		echo "<tr><td></td></tr>";
		echo "<tr><td>";
		echo "Happened Concert: ";
		echo "</td><td>";
		if($stmt = $mysqli->prepare("select cid,cname,cDatetime from concert where bid = ? order by cDatetime")) {
			$stmt->bind_param("i", $_GET["uid"]);
			$stmt->execute();
			$stmt->bind_result($cid,$cname,$cdatetime);
			$nowdatetime=date('Y-m-d H:i:s');
			while($stmt->fetch()) {
				if(strtotime($cdatetime)< strtotime($nowdatetime)){
					echo '<a href="viewConcert.php?cid=';
					echo htmlspecialchars($cid);
					$cname = htmlspecialchars($cname);
					echo "\">$cname</a>,  ";
				}
			}
			$stmt->close();
		}
		echo "</td></ tr>";
		
	}
	
	//If this user post any words, it will shows here.
		echo "<tr><td></td></tr>";
		echo "<tr><td>";
		echo "Words Post: ";
		echo "</td></ tr>";
			if($stmt = $mysqli->prepare("select msg,postDT from messages where uid = ?")) {
				$stmt->bind_param("i", $_GET["uid"]);
				$stmt->execute();
				$stmt->bind_result($msg,$posttime);
				while($stmt->fetch()) {
					echo "<tr><td>";
					echo $msg." on ".$posttime;
					echo "</td></ tr>";
				}
				$stmt->close();
			}
	
	
	echo "</table>";
	echo "<br />\n";
	echo "<table>";
	echo "<tr><td>";
	echo '<br /><a href="index.php">Go back</a>';
	echo "</td></tr>";
	echo "</table>";
	?>
</center>
</body>
</html>