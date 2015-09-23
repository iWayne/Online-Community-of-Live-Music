<html>
	<title>Create Concert</title>
	<body>
	<center>
	<img src='images/concert.jpeg'>
	<?php
	echo "<br />\n";
	echo "<br />\n";
	include ("include.php");

	//if the user is already logged in, redirect them back to homepage
	if(!isset($_SESSION["username"])) {
		echo "Welcome to the project, you are not logged in. <br /><br >\n";
		echo 'You must login in order to modify your profile
		<a href="login.php">login</a> or <a href="register.php">register</a> if you don\'t have an account yet.';
		echo "\n";
	}
	else {
		if(isset($_POST["title"])){
			$flag=true;
			if(strlen($_POST["title"])>40||strlen($_POST["hyperlink"])>100){$flag=false;}
			if(strlen($_POST["year"])!=4||strlen($_POST["month"])!=2||strlen($_POST["day"])!=2
			||!is_numeric($_POST["year"])||!is_numeric($_POST["month"])||!is_numeric($_POST["day"])){
				$flag=false;
			}
			if(strlen($_POST["hh"])!=2||strlen($_POST["mm"])!=2
			||!is_numeric($_POST["hh"])||!is_numeric($_POST["mm"])){
				$flag=false;
			}
			if($_POST["hh"]< 0||$_POST["mm"]< 0||$_POST["hh"]>24||$_POST["mm"]>60||$_POST["year"]>2050||
			$_POST["year"]< 2000 ||$_POST["month"]< 1 ||$_POST["month"]>12 || $_POST["day"]< 1 ||$_POST["day"]>31){
				$flag=false;
			}
			if($flag){
				$getssid=explode('.',$_POST["genre"]);
				$getvid=explode('.',$_POST["venue"]);
				$getbid=explode('.',$_POST["band"]);
				$gettime=$_POST["year"]."-".$_POST["month"]."-".$_POST["day"]." ".$_POST["hh"].":".$_POST["mm"].":00";
				if($stmt = $mysqli->prepare("insert into concert values (null,?,?,?,?,?,?,?,now())")){
					$stmt->bind_param('siiissi',$_POST["title"],$getbid[0],$getvid[0],$getssid[0],$gettime,$_POST["hyperlink"],$_SESSION["uid"]);
					$stmt->execute();
				}
				$stmt->close();
				echo "Created Successfully!";
				echo "\n<br />";
				echo '<a href="createconcert.php">Add another conert</a>';
				echo "\n<br />";
				echo '<a href="index.php">Home Page</a>';
			}else{
				echo "Invalid Input! Try Again!";
				echo "\n<br />";
				header("refresh: 3; createconcert.php");
			}
		}else{
			$trust=0;
			if($_SESSION["role"]==0){
				if ($stmt = $mysqli->prepare("select trust from customer natural join users where customer.uid=?")) {
					$stmt->bind_param("i", $_SESSION["uid"]);
					$stmt->execute();
					$stmt->bind_result($trust);
					$stmt->fetch();
					$stmt->close();
				}
			}
			if($_SESSION["role"]==1||$trust>4){
				echo "To add a concert, please enter the following infomation: \n<br />*must enter\n<br />";
				echo "<form action = 'createconcert.php' method='POST'>\r\n";
				echo "Title: <input type='text' name='title' /> *";
				echo "\n<br />";
				echo "Band: ";
				echo "<select name='band'>";
				if ($stmt = $mysqli->prepare("select uid,username from customer where role='1'")) {
					$stmt->execute();
					$stmt->bind_result($uid,$bname);
					while($stmt->fetch()) {
						$band = $uid.".".$bname;
						$band = htmlspecialchars($band);
						echo "<option value='$band'>$band</option>\n";
					}
					$stmt->close();
				}
				echo "</select>*";
				echo "\n<br />";
				echo "Genre: ";
				echo "<select name='genre'>";
				if ($stmt = $mysqli->prepare("select ssid,ssname from subgenre order by sparentid")) {
					$stmt->execute();
					$stmt->bind_result($ssid,$ssname);
					while($stmt->fetch()) {
						$genre = $ssid.".".$ssname;
						$genre = htmlspecialchars($genre);
						echo "<option value='$genre'>$genre</option>\n";
					}
					$stmt->close();
				}
				echo "</select>*";
				echo "\n<br />";
				echo "Venue: ";
				echo "<select name='venue'>";
				if ($stmt = $mysqli->prepare("select vid,vname from venue")) {
					$stmt->execute();
					$stmt->bind_result($vid,$vname);
					while($stmt->fetch()) {
						$venue = $vid.".".$vname;
						$venue = htmlspecialchars($venue);
						echo "<option value='$venue'>$venue</option>\n";
					}
					$stmt->close();
				}
				echo "</select>*";
				echo "\n<br />";
				echo "Website: <input type='text' name='hyperlink' /> optional";
				echo "\n<br />";
				echo "Date: <input type='text' name='year'/> - <input type='text' name='month'/> -
				<input type='text' name='day'/> *YYYY-MM-DD";
				echo "\n<br />";
				echo "Time: <input type='text' name='hh'/> - <input type='text' name='mm'/> *hh-mm";
				echo "\n<br />";
				echo '<input type="submit" value="Save" />';
				echo "\n";
				echo '</form>';
				echo "\n";
				echo '<br /><a href="index.php">Go back</a>';
			}else{
				echo "Invlid to create conerts because of your trust score.";
				echo "\n<br />";
			}
		}
	}

	?>
</center>
</body>
</html>