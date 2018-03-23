<?php require_once("includes/header.php"); ?>

<?php 

$user2;

if (isset($_GET["u"])) {

	$u = $_GET["u"]; // user that is confirming or denying

	//clean the get supervariavble...!!

	$logged_user_id = $_SESSION["user_id"];

	global $db;

	$sql = "SELECT * FROM notifications WHERE username = '{$u}' AND did_read = '0' AND friends_id <> '0'";
	$query = $db->query($sql);
	$num = $query->num_rows;

	$sql2 = "SELECT * FROM users WHERE username = '{$u}' LIMIT 1";
	$query2 = $db->query($sql2);
	$row2 = $query2->fetch_assoc();
	$user2 = $row2["id"];

	$sql3 = "SELECT * FROM notifications WHERE username = '{$u}' AND friends_id = '0' AND did_read = '0' AND confirm_friend_req = '1'";
	$query3 = $db->query($sql3);
	$num3 = $query3->num_rows;

	$number_of_results = $num + $num3;

}


 ?>

<div class="container-fluid">
	<div class="row index-row">
		<div class="col-md-4">
			<div class="col-md-12 styled-col">
				<h4 class="text-center">Friend Requests <i class="fa fa-user" aria-hidden="true"></i></h4><br>

				<?php 

				if ($number_of_results > 0) {


					while ($row = $query->fetch_assoc()) {
					$date = strtotime($row["date_time"]);
					$date = date("d.m.Y");

				 ?>
				<div class="friend_request">
					<div class="request_content">
						<div name="<?php echo $row["initiator"]; ?>" class="name hidden"></div>
						<p>Friend request from <strong><?php echo $row["initiator"]; ?></strong></p>
						<p>Date: <?php echo $date; ?></p>
					</div>
					<div class="request_buttons">
						<button add="<?php echo $row["friends_id"]; ?>" class="btn-confirm-friend btn btn-success">Accept</button>
						<button remove="<?php echo $row["friends_id"]; ?>" class="btn-decline-friend btn btn-danger">Decline</button>
					</div>
				</div>
				<?php } ?>
				
				<?php 

					while ($row3 = $query3->fetch_assoc()) {
				?>

				<div class="friend_request">
					<div><?php echo $row3["initiator"] . " accepted your friendship"; ?></div>
					<button data="<?php echo $row3["id"]; ?>" class="confirm_notification btn btn-primary">Ok</button>
				</div>
				
				<?php }

				} else {
					echo "<div class='text-center'>No new notifications</div>";
				}

				 ?>
			</div>
		</div>
		<div class="col-md-4">
			<div class="col-md-12 styled-col">
				<h4 class="text-center">Post's news feed <i class="fa fa-th-list" aria-hidden="true"></i></h4><br>
				
				<?php 

				$sql = "SELECT * FROM post_notifications WHERE friends_id = '{$logged_user_id}' AND did_read = '0'";
				$query = $db->query ($sql);
				$number_of_post_notifications = $query->num_rows;
				while ($row = $query->fetch_assoc()):
					$initiator_id = User::get_id($row["initiator_id"]);
					$initiator_id = $initiator_id->username;
				?>
						
					<div class="post_notifications">
						<div><?php echo $initiator_id; ?> posted something</div><br>
						<a class="check_post_noti" friend_id="<?php echo $row['friends_id']; ?>" href="post.php?p_id=<?php echo $row["post_id"]; ?>" username_attr="<?php echo $initiator_id->username ?>" data="<?php echo $row["post_id"]; ?>">Check the post</a>
					</div>
					

				<?php endwhile; ?>
				
				<div class="text-center"><?php if ($number_of_post_notifications == "0") {echo "No news";} ?></div>
			</div>
			
		</div>

		<div class="col-md-4">
			<div class="col-md-12 styled-col">
				<h4 class="text-center">Comment's notifications <i class="fa fa-commenting-o" aria-hidden="true"></i></h4><br>

				<?php 

				$sql_comment_noti = "SELECT * FROM comment_notifications WHERE post_owner = '{$u}' AND initiator != '{$u}' AND did_read = '0'";
				$query_comment_noti = $db->query($sql_comment_noti);
				$num_of_comment_noti = $query_comment_noti->num_rows;
				while ($row_comment_noti = $query_comment_noti->fetch_assoc()):


				?>
	
				<div class="comment_notifications">
					<p><strong><?php echo $row_comment_noti["initiator"]; ?></strong> commented on your post</p>
					<p>Quick! <a class="comment_notification_confirmed" comment_noti_id="<?php echo $row_comment_noti['id']; ?>" href="post.php?p_id=<?php echo $row_comment_noti['post_id']; ?>">Check the comment</a></p>
				</div>

			<?php endwhile;

				if ($num_of_comment_noti == 0) {
					echo "<div class='text-center'>No new comments</div>";
				}

			 ?>
				
			</div>
		</div>

	</div>
</div>

<script>

	$(document).on("click", ".comment_notification_confirmed", function() {

		var comment_noti_id = $(this).attr("comment_noti_id");

		$.ajax({
			url: "ajax/ajax-comment-system.php",
			method: "POST",
			data: {confirm_comment_noti: comment_noti_id}
		})

	});

	$(document).on("click", ".check_post_noti", function() {
		var post_id = $(this).attr("data");
		var friend_id = $(this).attr("friend_id");

		$.ajax({
			url: "ajax/ajax-posting-system.php",
			method: "POST",
			data: {post_id: post_id, friend_id: friend_id}
		})
	});

	// $(document).on("click", ".check_post", function() {
	// 	var post_id = $(this).attr("data");
	// 	var username_attr = $(this).attr("username_attr");

	// 	var navigationFn = {
	// 		    goToSection: function(id) {
	// 			        $('html, body').animate({
	// 			            scrollTop: $(id).offset().top
	// 			        }, 1000);
	// 			    }
	// 			}
		
	// 	$.ajax({
	// 		url: "ajax/ajax-posting-system.php",
	// 		method: "POST",
	// 		data: {post_id: post_id},
	// 		success: function(data) {
	// 			window.location = "profile.php?u=" + username_attr;
	// 			$("#" + post_id).css("background-color", "yellow");
	// 		}
	// 	})
	// });



	$(document).on("click", ".confirm_notification", function() {
		var notification_id = $(this).attr("data");
		var this_btn = $(this);
		if (notification_id != "") {
			$.ajax({
				url: "ajax/ajax-friend-system.php",
				method: "POST",
				data: {notification_id: notification_id},
				success: function(data) {
					if (data == "read_notification") {
						this_btn.parent().html("Notification checked");
					}
				}
			});	
		}
	});
	
	$(".btn-confirm-friend").on("click", function() {

		var user_name = $(this).parent().siblings().children("div").attr("name");
		var this_button = $(this);
		var add = $(this).attr("add");
		if (add != "") {

			$.ajax({
				url: "ajax/ajax-friend-system.php",
				method: "POST",
				data: {id_friends: add},
				success: function(data) {
					if (data == "now_friends") {
						this_button.parent().parent().html("You are now friends with " + user_name);
					}
				}
			});

		}

	});

	$(".btn-decline-friend").on("click", function() {

		var user_name = $(this).parent().siblings().children("div").attr("name");
		var this_button = $(this);
		var remove = $(this).attr("remove");
		if (remove != "") {

			$.ajax({
				url: "ajax/ajax-friend-system.php",
				method: "POST",
				data: {remove_id: remove},
				success: function(data) {
					if (data == "friend_removed") {
						this_button.parent().parent().html("You have declined friendship with " + user_name);
					}
				}
			});

		}

	});

</script>


<?php require_once("includes/footer.php"); ?>