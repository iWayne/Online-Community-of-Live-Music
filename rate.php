<html>
	<title>Rate Concert</title>
	<body>
	<center>
	<img src='images/concert.jpeg'>
	<?php
	echo "<br />\n";
	echo "<br />\n";

	include ("include.php");
	date_default_timezone_set('US/Eastern');
	//if the user is already logged in, redirect them back to homepage
	if(!isset($_SESSION["username"])) {
		echo "Welcome to the project, you are not logged in. <br /><br >\n";
		echo 'You must login in order to modify your profile
		<a href="login.php">login</a> or <a href="register.php">register</a> if you don\'t have an account yet.';
		echo "\n";
	}
	//Only Users can rate concerts
	else if($_SESSION["role"]=='1'){
		echo "This page is only for fans to rate the conert.";
		echo "You login as a band, redirect to Home Page or click <a href=\"index.php\">here</a>";
		header("refresh: 3; index.php");
	}
	else{
		//Check and save the comment
		if(isset($_POST["concert"])&&isset($_POST["rate"])){
			$flag=true;
			$getcid=explode('.',$_POST["concert"]);
			if ($stmt = $mysqli->prepare("select cid,uid from rate where cid=? and uid=?")) {
				$stmt->bind_param("ii", $getcid[0],$_SESSION["uid"]);
				$stmt->execute();
				$stmt->bind_result($cidDB,$uidDB);
				if($stmt->fetch()){
					$flag=false;
					echo "You have rated before.\n<br />";
				}
				$stmt->close();
			}
			if ($stmt = $mysqli->prepare("select cdatetime from concert where cid=?")) {
				$stmt->bind_param("i", $getcid[0]);
				$stmt->execute();
				$stmt->bind_result($cdatetime);
				$stmt->close();
			}

			$nowdatetime=date('Y-m-d H:i:s');
			if(strtotime($cdatetime)>=strtotime($nowdatetime)){
				$flag=false;
				echo "Invalid Time.\n<br />";
				echo $cdatetime . "\n<br />";
				echo $nowdatetime . "\n<br />";
			}
			if(strlen($_POST["rate"])>2||!is_numeric($_POST["rate"])||$_POST["rate"]< 0||$_POST["rate"]> 10){
				$flage=false; echo "Invalid Rate Value.\n<br />";
			}
			if(strlen($_POST["comment"])>200){
				$flage=false; echo "Invalid Comment Value.\n<br />";
			}
			if($flag){
				if($stmt = $mysqli->prepare("insert into rate values (?,?,?,now(),?)")){
					$stmt->bind_param('iiis',$_SESSION["uid"],$getcid[0],$_POST["rate"],$_POST["comment"]);
					$stmt->execute();
					echo "Created Successfully!";
					echo "\n<br />";
				}
				$stmt->close();
				echo '<a href="rate.php">Rate another conert</a>';
				echo "\n<br />";
				echo '<a href="index.php">Home Page</a>';
			}
			else{
				echo "Invalid Input! Try Again!";
				echo "\n<br />";
				echo "Refresh: 3 seconds.";
				header("refresh: 3; rate.php");
			}


		}else{
			//Enter the information
			echo "<table>";
			echo "<tr bgColor=#CCFFFF><td>";
			echo "Rate a Concert:";
			echo "</td></ tr>";
			echo "</table>";
			echo "<table>";
			echo "<form action = 'rate.php' method='POST'>\r\n";
			echo "<tr><td>";
			echo "Concert: ";
			echo "</td><td>";
			echo "<select name='concert'>";
			$nowdatetime=date('Y-m-d H:i:s');
			if ($stmt = $mysqli->prepare("select cid,cname,cdatetime from concert")) {
				$stmt->execute();
				$stmt->bind_result($cid,$cname,$cdatetime);
				while($stmt->fetch()) {
					if(strtotime($cdatetime)< strtotime($nowdatetime)){
						$concert = $cid.".".$cname;
						$concert = htmlspecialchars($concert);
						echo "<option value='$concert'>$concert</option>\n";
					}
				}
				$stmt->close();
			}
			echo "</select>*";
			echo "</td></ tr>";
			echo "<tr><td>";
			echo "Rate";
			echo "</td><td>";
			echo "<input type='text' name='rate'/> /10 *";
			echo "</td></ tr>";
			echo "<tr><td>";
			echo 'Comment';
			echo "</td><td>";
			echo '<textarea name="comment" rows="5" cols="40"/>';
			echo '</textarea>';
			echo "</td></ tr>";
			echo "</table>";
			echo "<table>";
			echo "<tr><td align=middle>";
			echo '<input type="submit" value="Save" />';
			echo "</td></ tr>";
			echo '</form>';
			echo "<tr><td align=middle>";
			echo '<br /><a href="index.php">Go back</a>';
			echo "</td></ tr>";
			echo "</table>";
		}
	}

	?>
</center>
</body>
</html>