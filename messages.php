<?php require_once("includes/header.php"); ?>

<?php 

$id = $_SESSION["user_id"];

?>

<div class="container"><br>
	<h3>Click on a friend you want to chat with</h3><br>
	<div class="row">
		
			<?php 

			global $db;

			$sql = "SELECT * FROM friends WHERE level = '1' AND (user1 = '{$id}' OR user2 = '{$id}') ";
			$query = $db->query($sql);
			$has_friends = $query->num_rows;

			if ($has_friends == 0) { ?>

				<div class="col-md-4">
					<div class="msg-container">
						<p>You have no friends yet :/</p>
					</div>
				</div>

			<?php }

			while ($row = $query->fetch_assoc()):
				$friend_id;

				$user1 = $row["user1"];
				$user2 = $row["user2"];

				if ($user1 != $id) {
					$friend_id = $user1;
				} else if($user2 != $id) {
					$friend_id = $user2;
				}

				$sql_friend = "SELECT * FROM users WHERE id = '{$friend_id}'";
				$friend_query = $db->query($sql_friend);
				$friend_row = $friend_query->fetch_assoc();
				$friend_img = $friend_row["image"];
				$friend_uname = $friend_row["username"];

				if ($friend_img == "") {
					$img = "http://via.placeholder.com/75x75";
				} else {
					$img = "users/{$friend_uname}/{$friend_img}";
				}

				$friend_username = $friend_row["username"];
				//can check profile image
				$sql_message_noti = "SELECT * FROM message_notification WHERE user2 = '{$id}' AND initiator = '{$friend_id}' AND is_read = '0'";
				$query_msg_noti = $db->query($sql_message_noti);
				$num_of_msg_noti = $query_msg_noti->num_rows;

			?>
			<div class="col-md-4">
				<div class="msg-container">
					<div class="msg-image"><img style="height: 75px; width: auto;" src="<?php echo $img; ?>" alt=""></div>
					<a href="chat.php?first_id=<?php echo $friend_id; ?>&second_id=<?php echo $id; ?>" class="btn btn-primary">Chat with <?php echo $friend_username; ?></a> <?php  if ($num_of_msg_noti > 0) {echo "<span class='msg_notification_circle'>{$num_of_msg_noti}</span>";} ?>
				</div>
			</div>

			<?php endwhile; ?>

		
	</div>
</div>



<?php require_once("includes/footer.php"); ?>