<?php 

require_once("../classes/init.php");

if (isset($_POST["id_friends"])) {
	
	$id = $_POST["id_friends"];
	global $db;
	$id = $db->escape_string($id);
	$id = preg_replace("/[^0-9]/", "", $id);

	$sql = "UPDATE friends SET level = '1' WHERE id = '{$id}' LIMIT 1";
	$query = $db->query($sql);

	$sql3 = "SELECT * FROM friends WHERE id = '{$id}'"; // get id from the user that is accepting the request
	$query3 = $db->query($sql3);
	$row3 = $query3->fetch_assoc();
	$user2 = $row3["user2"]; // get username from that same user
	$user1 = $row3["user1"];

	$uname = User::get_id($user2);
	$u = $uname->username;

	$sql2 = "UPDATE notifications SET did_read = '1' WHERE username = '{$u}' LIMIT 1";
	$query2 = $db->query($sql2);

	$uname2 = User::get_id($user1); // person who first sent you the request - we must notify him ($user1) that you accepted the request so therefore he is the new username, and you ($user2) are the initiator
	$u2 = $uname2->username;

	$sql4 = "INSERT INTO notifications (username, initiator, friends_id, did_read, date_time, confirm_friend_req) VALUES ('{$u2}', '{$u}', '0', '0', now(), '1')";
	$query4 = $db->query($sql4);

	echo "now_friends";
	exit();

}


if (isset($_POST["remove_id"])) {

	$friends_id = $_POST["remove_id"];
	global $db;
	$friends_id = $db->escape_string($friends_id);
	$friends_id = preg_replace("/[^0-9]/", "", $friends_id);

	$sql = "DELETE FROM friends WHERE id = '{$friends_id}' LIMIT 1";
	$query = $db->query($sql);

	$sql2 = "DELETE FROM notifications WHERE friends_id = '{$friends_id}' LIMIT 1";
	$query2 = $db->query($sql2);

	echo "friend_removed";
	exit();

}

if (isset($_POST["adding_users_id"])) {
	//the user I WANT TO ADD

	$adding_users_id = $_POST["adding_users_id"];
	global $db;
	$adding_users_id = $db->escape_string($adding_users_id);
	$adding_users_id = preg_replace("/[^0-9]/", "", $adding_users_id);

	$user1 = $_SESSION["user_id"];
	$user1 = $db->escape_string($user1);
	$user1 = preg_replace("/[^0-9]/", "", $user1);

	$adding_user = User::get_id($adding_users_id);
	$add_user_username = $adding_user->username;

	$user1_user = User::get_id($user1);
	$user1_user_username = $user1_user->username;

	$sql = "INSERT INTO friends (user1, user2, level, date) VALUES ('{$user1}', '{$adding_users_id}', '0', now())";
	$query = $db->query($sql);

	$inserted_id = $db->connection->insert_id;

	$sql_notification = "INSERT INTO notifications (username, initiator, friends_id, did_read, date_time) VALUES ('{$add_user_username}', '{$user1_user_username}', '{$inserted_id}', '0', now())";
	$query_notification = $db->query($sql_notification);

	echo "request_sent";
	exit();

}

if (isset($_POST["notification_id"])) {

	$noti_id = $_POST["notification_id"];

	global $db;
	$id = $db->escape_string($noti_id);
	$id = preg_replace("/[^0-9]/", "", $id); // cleans the POST notification_id

	$sql = "UPDATE notifications SET did_read = '1' WHERE id = '{$id}' AND confirm_friend_req = '1' LIMIT 1";
	$query = $db->query($sql);

	if ($query) {
		echo "read_notification";
	}

}

?>