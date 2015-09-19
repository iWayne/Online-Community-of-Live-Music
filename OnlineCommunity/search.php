<html>
	<body>
	<center>
	<img src='images/concert.jpeg'>
	<?php
	echo "<br />\n";
	echo "<br />\n";
	include ("include.php");
	//Search by Genre
	echo "<table >";
	echo "<tr><td>";
	echo "<form action = 'search.php' method='GET'>\r\n";
	echo 'Search by Genre: ';
	echo ' <input type="text" name="keywd" value="';
	if(isset($_GET["keywd"])){echo $_GET["keywd"];}
	echo '"/> ';
	echo ' <input type="submit" value="Search" />';
	echo "\n";
	echo '</form>';
	echo "\n<br />";
	echo "</td></tr>";
	if(isset($_GET["keywd"])){
		if($stmt = $mysqli->prepare('select ssname,ssid from subgenre where ssname like ? order by sparentid')){
			//echo "<table >";
			$keywd="%".$_GET["keywd"]."%";
			$stmt->bind_param('s',$keywd);
			$stmt->execute();
			$stmt->bind_result($ssname, $ssid);
			while($stmt->fetch()){
				echo "<tr><td align=middle>";
				$ssid=htmlspecialchars($ssid);
				$ssname=htmlspecialchars($ssname);
				echo "<a href='viewGenre.php?sid=";
				echo $ssid."'\>".$ssname."</a>";
				echo "</td></tr>";
			}
			
		}
	}
	echo "</ table>";
	echo "<table>";
	echo "<tr><td>";
	echo '<br /><a href="index.php">Go back</a>';
	echo "</td></tr>";
	echo "</table>";
	?>
</center>
</body>
</html>