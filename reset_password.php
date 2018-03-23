<?php 

require_once("classes/init.php");

if (isset($_GET["u"]) && isset($_GET["p"])) {

	global $db;

	$username = $db->escape_string(preg_replace("/[^0-9a-z]/i", "", $_GET["u"]));
	$password = $db->escape_string(preg_replace("/[^0-9a-z]/i", "", $_GET["p"]));

	$sql = "SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}' LIMIT 1";
	$query = $db->query($sql);
	$user_exists = $query->num_rows;
	$row = $query->fetch_assoc();

	$temp_pass = $row["temp_pass"];

	if ($user_exists == 1) {
		global $db;
		$sql = "UPDATE users SET password = '{$temp_pass}' WHERE username = '{$username}' LIMIT 1";
		$query = $db->query($sql);
		echo "Password has been successfully changed <a href='login.php'>Login here</a>";
	} else {
		echo "Something went wrong";
	}

}


?>