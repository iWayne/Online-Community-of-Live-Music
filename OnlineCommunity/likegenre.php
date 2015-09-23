<html>
	<title>Likes Genre</title>
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
		if($_SESSION["role"]=='0'){
			//Update the genres the user likes
			if(isset($_POST["allgenre"])||empty($_POST["allgenre"])){
				if($stmt = $mysqli->prepare("delete from likes where uid=?")){
					$stmt->bind_param("i",$_SESSION["uid"]);
					$stmt->execute();
				}
				if(isset($_POST["allgenre"])){
					foreach($_POST["allgenre"] as $checked){
						if($stmt = $mysqli->prepare("insert into likes values (?,?)")){
							$stmt->bind_param("ii",$_SESSION["uid"],$checked);
							$stmt->execute();
							echo "Saved!\n<br />";
						}
					}
				}
				$stmt->close();
			}
			//List all genres with checked genres
			echo '<form action="likegenre.php" method="POST"><br />';
			echo "<table border='1'>";
			$col=0;
			$prt=0;
			if($stmt = $mysqli->prepare("select ssid, ssname, uid, sparentid from subgenre left join 
																	(select uid,sid from likes where uid=?) as L on subgenre.ssid=L.sid order by sparentid")){
				$stmt->bind_param("i",$_SESSION["uid"]);				
				$stmt->execute();
				$stmt->bind_result($ssid, $ssname, $uid, $parent);
				echo "<tr>";
				while($stmt->fetch()){
					if($prt!=$parent){include 'likegenre.php';
					
						echo "</tr><tr>";
						$prt=$parent;
					}
					$ssid=htmlspecialchars($ssid);
					$ssname=htmlspecialchars($ssname);
					echo "<td><input type='checkbox' name='allgenre[]' value='$ssid'";
					if($uid==$_SESSION["uid"]){echo "checked='true'";}
					echo "/>".$ssname."</td>";
					$col++;
					if($col>8){echo "</tr><tr>";$col=0;}
				}
				echo "</tr>";
			}

			echo "</table>\n<br />";
			echo '<input type="submit" value="Save" />';
			echo "\n";
			echo '</form>';
			echo "\n";
			echo '<br /><a href="index.php">Go back</a><br />';
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