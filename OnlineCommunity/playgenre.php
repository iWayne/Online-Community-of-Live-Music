<html>
	<title>Plays Genre</title>

	<?php

	include ("include.php");

	//if the user is already logged in, redirect them back to homepage
	if(!isset($_SESSION["username"])) {
		echo "Welcome to the blog example, you are not logged in. <br /><br >\n";
		echo 'You must login in order to modify your profile
		<a href="login.php">login</a> or <a href="register.php">register</a> if you don\'t have an account yet.';
		echo "\n";
	}
	else {
		if($_SESSION["role"]=='1'){
			//Update the genres the band plays
			if(isset($_POST["allgenre"])||empty($_POST["allgenre"])){
				if($stmt = $mysqli->prepare("delete from plays where uid=?")){
					$stmt->bind_param("i",$_SESSION["uid"]);
					$stmt->execute();
				}
				if(isset($_POST["allgenre"])){
					foreach($_POST["allgenre"] as $checked){
						if($stmt = $mysqli->prepare("insert into plays values (?,?)")){
							$stmt->bind_param("ii",$_SESSION["uid"],$checked);
							$stmt->execute();
							$stmt->close();
						}
					}
					
				}
				$stmt->close();
				echo "Saved!\n<br />";
			}
			//List all genres with checked genres
			echo '<form action="playgenre.php" method="POST"><br />';
			echo "<table border='1'>";
			$col=0;
			$prt=0;
			if($stmt = $mysqli->prepare("select ssid, ssname, uid, sparentid from subgenre left join (select uid,sid from plays where uid=?) 
																	as P on subgenre.ssid=P.sid order by sparentid")){
				$stmt->execute();
				$stmt->bind_result($ssid, $ssname, $uid, $parent);
				echo "<tr>";
				while($stmt->fetch()){
					if($prt!=$parent){
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

</html>