<?php require_once("../classes/init.php");



if (isset($_POST["user_id"]) && isset($_POST["friends_id"]) && isset($_POST["text"])) {

	global $db;

	$user_id = $_POST["user_id"];
	$friends_id = $_POST["friends_id"];
	$text = $_POST["text"];

	$sql = "INSERT INTO messages (sender, reciever, msg) VALUES ('{$user_id}', '{$friends_id}', '{$text}')";
	$query = $db->query($sql);

	$last_id = $db->connection->insert_id;

	$sql2 = "INSERT INTO message_notification (initiator, user2, msg_id) VALUES ('{$user_id}', '{$friends_id}', '{$last_id}')";
	$query2 = $db->query($sql2);

	$sender = User::get_id($user_id);
	$sender = $sender->username;

	echo "<div style='display:flex; justify-content: flex-end'><div class='me'>{$sender} said: {$text}</div></div><br>";


}

if (isset($_POST["my_id"])) {

	$id = $_POST["my_id"];

	global $db;

	$sql = "SELECT * FROM message_notification WHERE user2 = '{$id}' AND is_read = '0'";
	$query = $db->query($sql);
	$num = $query->num_rows;

	echo $num;

}

if (isset($_POST["user"]) && isset($_POST["friend"])) {

	$id1 = $_POST["user"];
	$id2 = $_POST["friend"];

	$sql = "SELECT * FROM messages WHERE sender = '{$id1}' AND reciever = '{$id2}' OR sender = '{$id2}' AND reciever = '{$id1}' ORDER BY id ASC";
	$query = $db->query($sql);

	while ($row = $query->fetch_assoc()) {
		if ($row["sender"] == $id1) {
			$me = User::get_id($id1);
			$me = $me->username;
			echo "<div style='display:flex; justify-content: flex-end'><div class='me'>{$me} said: {$row['msg']}</div></div><br>";
		} else {
			$other_person = User::get_id($id2);
			$other_person = $other_person->username;
			echo "<div style='display:flex; justify-content: flex-start'><div class='you'>{$other_person} said: {$row['msg']}</div></div><br>";
		}
	}

}
if (isset($_POST["usr_id"]) && isset($_POST["frnds_id"])) {

	$me = $_POST["usr_id"];
	$friend = $_POST["frnds_id"];

	$sql = "UPDATE message_notification SET is_read = '1' WHERE initiator = '{$friend}' AND user2 = '{$me}' AND is_read = '0'";
	$query = $db->query($sql);

}









?>