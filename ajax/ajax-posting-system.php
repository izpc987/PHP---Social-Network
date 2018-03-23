<?php 

require_once("../classes/init.php");

if (isset($_POST["post_text"])) {

	$text = $_POST["post_text"];
	global $db;
	$text = $db->escape_string($text);
	$text = preg_replace("/[^0-9a-z., ]/i", "", $text);

	$author = $_SESSION["user_id"];
	$user = User::get_id($author);
	$username = $user->username;

	$today = date("d.m.Y");

	$sql = "INSERT INTO posts (author, content, likes, date_posted, comments) VALUES ('{$author}', '{$text}', '0', now(), '0')";
	$query = $db->query($sql);
	$post_id = $db->connection->insert_id;

	$sql3 = "SELECT * FROM friends WHERE user1 = '{$author}' OR user2 = '{$author}'"; // notification on post
	$query3 = $db->query($sql3);
	while ($row3 = $query3->fetch_assoc()) {
		$u1 = $row3["user1"];
		$u2 = $row3["user2"];
		$friend;

		if ($u2 == $author) {
			$friend = $u1;
		} else if ($u1 == $author) {
			$friend = $u2;
		}

		// $friends_id = $row3["id"];
		$sql2 = "INSERT INTO post_notifications (friends_id, initiator_id, notification_date, post_id) VALUES ('{$friend}', '{$author}', now(), '{$post_id}')";
		$query2 = $db->query($sql2);
	}

	$sql_likes = "SELECT * FROM likes WHERE post_id = '$post_id' AND like_count = '1'";
	$query_likes = $db->query($sql_likes);
	$num_of_likes = $query_likes->num_rows;


	if ($query) {
		echo "<div class='col-md-12 styled-col post'>
				<p><strong>I ({$username})</strong> posted on {$today}:</p>
				<p>{$text}</p>

				<span>{$num_of_likes} <button data='{$post_id}' class='like_sys'><i class='fa fa-thumbs-up' aria-hidden='true'></i> Like</button></span>
				<a class='float-right' href='post.php?p_id={$post_id}'>Comment <i class='fa fa-comments' aria-hidden='true'></i></a>
			</div>";
		exit();
	}

}

if (isset($_POST["post_id"]) && isset($_POST["friend_id"])) {
	
	global $db;

	$post_id = $_POST["post_id"];
	$friend_id = $_POST["friend_id"];

	$sql = "UPDATE post_notifications SET did_read = '1' WHERE post_id = '{$post_id}' AND friends_id = '{$friend_id}'";
	$query = $db->query($sql);


}



?>