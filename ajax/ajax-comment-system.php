<?php require_once("../classes/init.php");


if (isset($_POST["comment_text"]) && isset($_POST["post_id"]) && isset($_POST["author"])) {

	global $db;

	$comment_text = $_POST["comment_text"];
	$post_id = $_POST["post_id"];
	$author = $_POST["author"];
	$today = date("d.m.Y");

	$sql = "INSERT INTO comments (post_id, author, content, date) VALUES ('{$post_id}', '{$author}', '{$comment_text}', now())";
	$query = $db->query($sql);
	
	$sql3 = "SELECT * FROM posts WHERE id = '{$post_id}'";
	$query3 = $db->query($sql3);
	$row = $query3->fetch_assoc();
	$user = User::get_id($row["author"]);
	$username = $user->username;

	if ($username != $author) {
		$sql2 = "INSERT INTO comment_notifications (initiator, post_owner, post_id, comment_date) VALUES ('{$author}', '{$username}','{$post_id}', now() )";
		$query2 = $db->query($sql2);
	}

	echo "<div class='all_comments'><p><strong>{$author}</strong> commented on {$today}: </p><p style='margin: 0;'>{$comment_text}</p></div>";

}

if (isset($_POST["confirm_comment_noti"])) {

	global $db;

	$comment_noti_id = $_POST["confirm_comment_noti"];

	$sql = "UPDATE comment_notifications SET did_read = '1' WHERE id = {$comment_noti_id}";
	$query = $db->query($sql);

}








 ?>