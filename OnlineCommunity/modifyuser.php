<html>
	<title>Blog Example</title>
	<body>
	<center>
	<img src='images/concert.jpeg'>
	<?php
	echo "<br />\n";
	echo "<br />\n";

	include ("include.php");

	//if the user is already logged in, redirect them back to homepage
	if(!isset($_SESSION["username"])) {
		echo "Welcome to the blog example, you are not logged in. <br /><br >\n";
		echo 'You must login in order to modify your profile
		<a href="login.php">login</a> or <a href="register.php">register</a> if you don\'t have an account yet.';
		echo "\n";
	}
	else {
		//Read from DB
		if($_SESSION["role"]=='0'){
			$stmt = $mysqli->prepare("select birthday, email, city, pickListName from users where uid = ?");
			$stmt->bind_param("i", $_SESSION["uid"]);
			$stmt->execute();
			$stmt->bind_result($birthday, $email, $city, $picklist);
			$stmt->fetch();
			$stmt->close();
			//Check if new input entered
			if(!empty($_POST["birthday"])||!empty($_POST["email"])||!empty($_POST["city"])||!empty($_POST["picklist"])){
				echo "Saved!\n<br />";
			}
			if(!empty($_POST["birthday"])){
				if(strlen($_POST["birthday"])==4 && is_numeric($_POST["birthday"])
				&& $_POST["birthday"]<2010 && $_POST["birthday"]>1900){
					$birthday=$_POST["birthday"];
				}else{
					echo "Birthday is invalid!<br />\n";
				}
			}
			if(!empty($_POST["email"])){
				if(preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$_POST["email"])){
					$email=$_POST["email"];
				}else{
					echo "Eamil is invalid!<br />\n";
				}
			}
			if(!empty($_POST["city"])){
				if(strlen($_POST["city"])<20){
					$city=$_POST["city"];
				}else{
					echo "City is invalid!<br />\n";
				}
			}
			if(!empty($_POST["picklist"])){
				if(strlen($_POST["picklist"])<20){
					$picklist=$_POST["picklist"];
				}else{
					echo "Picklist Name is invalid!<br />\n";
				}
			}
			//Update DB
			$stmt = $mysqli->prepare("update users set birthday=?, email=?, city=?, pickListName=?, updatedDT=now() where uid=?");
			$stmt->bind_param("isssi",$birthday,$email,$city,$picklist,$_SESSION["uid"]);
			$stmt->execute();
			$stmt->close();
			//Text for entering values
			echo "<table >";
			echo "<tr bgColor=#CCFF00><td align=left>";
			echo "Modify your information below: <br />";
			echo "</td></tr>";
			echo "</table >";
			echo "<br />\n";
			echo "<table >";
			echo '<form action="modifyuser.php" method="POST">';
			echo "<tr><td align=left>";
			echo 'Year of Birth:';
			echo "</td><td>";
			echo '<input type="text" name="birthday" value="';
			if(!empty($birthday)){$birthday=htmlspecialchars($birthday);echo $birthday;}
			echo '"/>';
			echo "</td><td>";
			echo 'format: yyyy<br />';
			echo "</td></tr>";
			echo "<tr><td align=left>";
			echo 'Email:';
			echo "</td><td>";
			echo '<input type="text" name="email" value="';
			if(!empty($email)){$email=htmlspecialchars($email);echo $email;}
			echo '"/><br />';
			echo "</td></tr>";
			echo "<tr><td align=left>";
			echo 'City:';
			echo "</td><td>";
			echo '<input type="text" name="city" value="';
			if(!empty($city)){$city=htmlspecialchars($city);echo $city;}
			echo '"/>';
			echo "</td><td>";
			echo ' less than 20 letters<br />';
			echo "</td></tr>";
			echo "<tr><td align=left>";
			echo 'PickList Name:';
			echo "</td><td>";
			echo '<input type="text" name="picklist" value="';
			if(!empty($picklist)){$picklist=htmlspecialchars($picklist);echo $picklist;}
			echo '"/>';
			echo "</td><td>";
			echo 'less than 20 letters<br />';
			echo "</td></tr>";
			echo "<tr><td></td><td align=middle>";
			echo "<br />\n";
			echo '<input type="submit" value="Save" />';
			echo "</td></tr>";
			echo '</form>';
			echo "<tr><td></td><td align=middle>";
			echo "<br />\n";
			echo '<a href="index.php">Go back</a>';
			echo "</td></tr>";
			echo "</table>";
		}
		else{
			echo "You login as a band, redirect to Home Page or click <a href=\"index.php\">here</a>";
			header("refresh: 3; index.php");
		}
	}

	?>
</center>
</body>
</html>