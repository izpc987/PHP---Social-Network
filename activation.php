<?php 

require_once("classes/init.php");

if (isset($_GET["i"]) && isset($_GET["u"]) && isset($_GET["p"])) {

	global $db;

	$id = $_GET["i"];

	$db->escape_string($id);

	$u = $_GET["u"];
	$p = $_GET["p"];

	if (User::activateUser($u, $p)) {
		echo "User has been activated";
	} else {
		header("Location: login.php");
	}
} else {
	header("Location: login.php");
}

?>