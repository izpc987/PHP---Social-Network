<?php require_once("includes/header.php"); ?>

<?php 

if (isset($_GET["first_id"]) && isset($_GET["second_id"])) {

	global $db;

	$id1 = $_GET["first_id"];
	$id2 = $_GET["second_id"];

	$user_id = $_SESSION["user_id"];
	$friends_id;

	if ($id2 != $user_id) {
		header("Location: messages.php");
	}

	if ($user_id == $id1) {
		$friends_id = $id2;
	} else if ($user_id == $id2) {
		$friends_id = $id1;
	}

	$update_noti = "UPDATE message_notification SET is_read = '1' WHERE user2 = '{$user_id}' AND initiator = '{$friends_id}' AND is_read = '0'";
	$query_update = $db->query($update_noti);

} else {
	header("Location: messages.php");
}




?>

<div class="container">
	<div class="row">
		<div class="col-md-6 offset-md-3 chat" style="margin-top: 15px;">

			<div class="chat_content">
			<?php 

			global $db;

			$sql = "SELECT * FROM messages WHERE sender = '{$id1}' AND reciever = '{$id2}' OR sender = '{$id2}' AND reciever = '{$id1}' ORDER BY id ASC";
			$query = $db->query($sql);
			while ($row = $query->fetch_assoc()):
				if ($row["sender"] == $user_id) {
					$me = User::get_id($user_id);
					$me = $me->username;
					echo "<div style='display:flex; justify-content: flex-end'><div class='me'>{$me} said: {$row['msg']}</div></div><br>";
				} else {
					$other_person = User::get_id($friends_id);
					$other_person = $other_person->username;
					echo "<div style='display:flex; justify-content: flex-start'><div class='you'>{$other_person} said: {$row['msg']}</div></div><br>";
				}
			?>

			<?php endwhile; ?>
			</div>

			<div class="form-group">
				<textarea placeholder="Send a message ..." id="chat_text" cols="30" rows="3" class="form-control"></textarea>
			</div>
			<div class="form-group" style="text-align: center;">
				<button id="send" class="btn btn-primary">Send</button>
			</div>
		</div>
	</div>
</div>



<script>

	$(document).on("focus", "#chat_text", function() {

		var user_id = <?php echo $user_id; ?>;
		var friends_id = <?php echo $friends_id; ?>;

			$.ajax({
				url: "ajax/ajax-chat.php",
				method: "POST",
				data: {usr_id: user_id, frnds_id: friends_id},
				success: function(data) {
				}
			})
		

	});
	
	$("#send").on("click", function() {

		var user_id = <?php echo $user_id; ?>;
		var friends_id = <?php echo $friends_id; ?>;

		var text = $("#chat_text").val();

		if (text != "") {
			$.ajax({
				url: "ajax/ajax-chat.php",
				method: "POST",
				data: {user_id: user_id, friends_id: friends_id, text: text},
				success: function(data) {
					$(".chat_content").append(data);
					$("#chat_text").val("");
				}
			})
		}
		

	});


	function update_msg() {

		var user = <?php echo $user_id; ?>;
		var friend = <?php echo $friends_id; ?>;

		$.ajax({
			url: "ajax/ajax-chat.php",
			method: "POST",
			data: {user: user, friend: friend},
			success: function(data) {
				$(".chat_content").html(data);
			}
		})

	}

	setInterval(update_msg, 1000);

</script>

<?php require_once("includes/footer.php"); ?>