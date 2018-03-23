<?php 

require_once("../classes/init.php");


if (isset($_POST["firstname"]) && isset($_POST["lastname"])) {

	$id = $_SESSION["user_id"];

	$fname = $_POST["firstname"];
	$lname = $_POST["lastname"];
	$gender = $_POST["gender"];

	if ($fname != "" || $lname != "" || $gender != "") {

		global $db;

		$sql = "UPDATE users SET firstname = '{$fname}', lastname = '{$lname}', gender = '{$gender}' WHERE id = '{$id}' ";
		$query = $db->query($sql);

	}

	
}

if (isset($_FILES["img"]["name"]) && isset($_FILES["img"]["tmp_name"])) {

	global $db;

	if (!isset($_SESSION["user_id"])) {
		die();
	}

	$user_id = $_SESSION["user_id"];
	$username = User::get_id($user_id);
	$username = $username->username;

	$newLoc = "../users/$username/";
	$tmp_file = $_FILES["img"]["tmp_name"];
	$file_name = $_FILES["img"]["name"];

	if (!empty($tmp_file) || !empty($file_name)) {
		$destination = $newLoc . $file_name;

		move_uploaded_file($tmp_file, $destination);

		$newDestination = "users/$username/" . $file_name;

		$sql = "UPDATE users SET image = '{$file_name}' WHERE id = '{$user_id}' LIMIT 1";
		$query = $db->query($sql);

		if($query) {
			echo $newDestination;
		}
	} else {
		echo "nope";
	}

}


?>