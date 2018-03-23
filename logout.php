<?php 

require_once("classes/init.php");

if (isset($_SESSION["user_id"])) {
	$session->logout();
	header("Location: login.php");
}




?>