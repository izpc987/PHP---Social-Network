<?php 

require_once("../classes/init.php");

if (isset($_POST["user"])) {

	$u = $_POST["user"];

	if (strlen($u) <= 3 || strlen($u) > 15) {
		echo "<br><div class='alert alert-danger'>Username must be between 4 - 15 characters long</div>";
		exit();
	}

	if (User::checkUsername($_POST["user"])) {
		echo "<br><div class='alert alert-danger'>The user already exists</div>";
	} else {
		echo "<br><div class='alert alert-success'>" . $_POST["user"] . " is available</div>";
	}

}

if (isset($_POST["fname"])) {

	global $db;

	$username = $db->escape_string($_POST["uname"]);
	$password = $db->escape_string($_POST["pass"]);
	$p2 = $db->escape_string($_POST["p2"]);
	$firstname = $db->escape_string($_POST["fname"]);
	$lastname = $db->escape_string($_POST["lname"]);
	$gender = $db->escape_string($_POST["gender"]);
	$email = $db->escape_string($_POST["email"]);

	$mailRegex = "/[0-9a-z.]+@[a-z]+.[a-z]+/i";		

	$user_exists = User::checkUsername($username);
	$email_exists = User::checkEmail($email);

	if ($user_exists || $email_exists || $password != $p2 || !preg_match($mailRegex, $email)) {
		echo "<br><div class='alert alert-danger'>Fix all fields in the form to proceed</div>";
		exit();
	}

	if (strlen($username) <= 3) {
		echo "Username must be longer than 3 characters";
		exit();
	} else if (strlen($username) > 15) {
		echo "Username must be shorter than 15 characters";
	}

	$sql = "INSERT INTO users (username, password, firstname, lastname, gender, email, date_created) VALUES ('{$username}', '{$password}', '{$firstname}', '{$lastname}', '{$gender}', '{$email}', now())";	

	$db->query($sql);

	$id = $db->last_id();

	mkdir("../users/" . $username);

	$to = $email;
	$from = "t3sts0c1aln3tw0rk@gmail.com";
	$subject = "Activation email for Social Network";
	$content = "<br>Your username is: <strong>" . $username . "</strong><br>Your password is: <strong>" . $password . "</strong><br><h1>Activation Link</h1><p>Click on <a href='http://www.izpc.si/activation.php?i=" . $id . "&u=" . $username . "&p=" . $password . "'>this</a> activation link to activate your account and join us on Social Network!</p><p>Best regards, <br>Team Social Networks</p>";
	
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: " . $from . "\n";

	// mail($to, $subject, $content, $headers);

	echo "created";
	exit();

}

if (isset($_POST["e"])) {

	global $db;

	$email = $db->escape_string($_POST["e"]);

	$email_exists = User::checkEmail($email);

	if ($email_exists) {
		echo "This Email already exists";
	} else {
		echo "You can use this email";
	}

}

if (isset($_POST["pass_reset"])) {

	$email = $_POST["pass_reset"];

	global $db;

	$e = $db->escape_string($email);

	$sql = "SELECT * FROM users WHERE email = '{$e}' LIMIT 1";
	$query = $db->query($sql);
	$row = $query->fetch_assoc();

	$username = $row["username"];
	$password = $row["password"];

	$randPass = substr(md5(rand()), 0, 12);

	$update_sql = "UPDATE users SET temp_pass = '{$randPass}' WHERE email = '{$e}' AND activation = '1' LIMIT 1";
	$update_query = $db->query($update_sql);

	$to = $e;
	$subject = "Reset password - Social Networks";
	$content = "<strong>If you haven't requested to reset your password, IGNORE THIS EMAIL.</strong><br><br><p>Click on the below link to reset your password.<br> Your new password will be: <strong>{$randPass}</strong><br><a href='http://www.izpc.si/reset_password.php?u={$username}&p={$password}'>Click here to reset your password</a></p>";
	$from = "t3sts0c1aln3tw0rk@gmail.com";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: " . $from . "\n";

	if (mail($to, $subject, $content, $headers)) {
		echo "yes";
	} else {
		echo "no";
	}

}

if (isset($_POST["name"])) {

	$username_notification = $_POST["name"];

	$notifications_sql = "SELECT * FROM notifications WHERE username = '{$username_notification}' AND did_read = '0'";
	$notification_sql_query = $db->query($notifications_sql);
	$noti_exists = $notification_sql_query->num_rows;
	$number_of_noti;

	if ($noti_exists > 0) {
		$number_of_noti = $noti_exists;
	} else {
		$number_of_noti = 0;
	}

	echo $number_of_noti;
}


if (isset($_POST["insert_text"])) {

	$text = $_POST["insert_text"];

	$id = $_SESSION["user_id"];

	$sql = "SELECT * FROM users WHERE username LIKE '{$text}%' AND id != '{$id}'";
	$query = $db->query($sql);
	$if_exists = $query->num_rows;

	if ($if_exists != 0) {

		while ($row = $query->fetch_assoc()) {

			echo "<a href='profile.php?u={$row['username']}'><li>" . $row["username"] . "</li></a>";

		} 
	} else {
		echo "<li> No results found </li>";
	}

}

if (isset($_POST["post_id"]) && isset($_POST["user_id"])) {

	global $db;

	$post = $_POST["post_id"];
	$user = $_POST["user_id"];

	$sql3 = "SELECT * FROM likes WHERE post_id = '{$post}' AND user_id = '{$user}' AND like_count =  '1'";
	$query3 = $db->query($sql3);
	$num_of_likes_from_same_user = $query3->num_rows;
	if ($num_of_likes_from_same_user == 0) {
		$sql = "INSERT INTO likes (post_id, like_count, dislike_count, user_id) VALUES ('{$post}', '1', '0', '{$user}')";
		$query = $db->query($sql);

		$sql2 = "SELECT * FROM likes WHERE post_id = '{$post}' AND like_count = '1'";
		$query2 = $db->query($sql2);
		$num = $query2->num_rows;

		echo "{$num} <button data='{$post}' class='dislike_sys'><i class='fa fa-thumbs-down' aria-hidden='true'></i> Dislike</button>";
	} else {
		echo "already_liked";
	}

	

}

if (isset($_POST["post_id2"]) && isset($_POST["user_id2"])) {

	global $db;

	$post = $_POST["post_id2"];
	$user = $_POST["user_id2"];

	$sql = "DELETE FROM likes WHERE post_id = '{$post}' AND user_id = '{$user}' AND like_count = '1'";
	$query = $db->query($sql);

	$sql2 = "SELECT * FROM likes WHERE post_id = '{$post}' AND like_count = '1'";
	$query2 = $db->query($sql2);
	$num = $query2->num_rows;

	echo "{$num} <button data='{$post}' class='like_sys'><i class='fa fa-thumbs-up' aria-hidden='true'></i> Like</button>";


}

?>