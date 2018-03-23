<?php require_once("includes/header.php"); 

if (isset($_GET["u"])) {

	$u = $_GET["u"];
	global $db;
	$u = $db->escape_string($u);
	$u = preg_replace("/[^0-9a-zA-Z]/i", "", $u);

	$logged_user = $_SESSION["user_id"];
	$logged_user = $db->escape_string($logged_user);
	$logged_user = preg_replace("/[^0-9]/i", "", $logged_user);

	$sql = "SELECT * FROM users WHERE username = '{$u}' LIMIT 1";
	$query = $db->query($sql);
	$user_exists = $query->num_rows;
	if ($user_exists == 1) {
		$row = $query->fetch_assoc();  /*USERNAME OF A PERSON'S PROFILE*/
		$img_link;
		$image = $row["image"];
		if ($image == "") {
			$img_link = "http://via.placeholder.com/150x150";
		} else {
			$img_name = $image;
			$img_link = "users/$u/" . $img_name;
		}

?>

	<div class="container">
		<div class="row index-row">
			<div class="col-md-5">
				<div class="col-md-12 styled-col">
					<div class="specific-user-info">
						<img id="profile_picture" class="img-thumbnail" src="<?php echo $img_link; ?>" alt="">
						<h4><?php echo $u; ?></h4>
						<?php 


						$sql = "SELECT * FROM friends WHERE (user1 = '{$logged_user}' AND user2 = '{$row["id"]}') OR (user1 = '{$row["id"]}' AND user2 = '{$logged_user}')";
						$query = $db->query($sql);
						$friendship_exists = $query->num_rows;
						$row2 = $query->fetch_assoc();
						$level = $row2["level"];

						$sql_get_user_name = "SELECT * FROM users WHERE id = '{$logged_user}' LIMIT 1"; //gets username from the logged in user the session user
						$query_username = $db->query($sql_get_user_name);
						$row_username = $query_username->fetch_assoc();
						$logged_user_username = $row_username["username"];

						$user1 = $row2["user1"]; //who sent the request FIRST
						$user2 = $row2["user2"]; //who recieved the request

						$button = "";

						if ($friendship_exists == 1 && $level == 1) {
							$button .= "<button remove='" . $row2["id"] . "' class='action-button btn btn-danger'>Remove from Friends</button>";
						} else if ($friendship_exists == 1 && $level == 0 && $user1 == $logged_user) {
							$button .= "<button class='btn btn-warning'>Request Sent</button>";
						} else if ($friendship_exists == 1 && $level == 0 && $user1 == $row["id"]) {
							$button .= "<button accept='" . $row2["id"] . "' class='action-button btn btn-warning'>Accept as Friend</button>";
						} else if ($friendship_exists == 0) {
							$button .= "<button add='" . $row["id"] . "' class='action-button btn btn-primary'>Add as Friend</button>";
							if ($u == $logged_user_username) {
								$button = "<button data-toggle='modal' data-target='#myModal' class='btn btn-info'>Manage my account</button>";
							}
						}

						echo $button;

						?>
					</div>
				</div>
				<br>
				<?php if($u == $logged_user_username) { ?>
				
				<?php require_once("includes/friends_list.php"); ?>

				<?php } else {
					require_once("includes/self_profile_friends.php");
				} ?>
			</div>
			<div class="col-md-7">
				<div class="col-md-12 styled-col">
					<h4 class="text-center">My Posts</h4>
					<?php 

						$user_id = $row["id"];

						$sql_posts = "SELECT * FROM posts WHERE author = '{$user_id}' ORDER BY id DESC";
						$query_posts = $db->query($sql_posts);
						$num_of_posts = $query_posts->num_rows;

						if ($num_of_posts != 0) {

						while ($posts_row = $query_posts->fetch_assoc()):

							$sql_likes = "SELECT * FROM likes WHERE post_id = '{$posts_row['id']}' AND like_count = '1'";
							$query_likes = $db->query($sql_likes);
							$num_of_likes = $query_likes->num_rows;

							$sql_my_likes = "SELECT * FROM likes WHERE post_id = '{$posts_row['id']}' AND like_count = '1' AND user_id = '{$logged_user}'";
							$query_my_likes = $db->query($sql_my_likes);
							$num_my_likes = $query_my_likes->num_rows;


					?>
					<div id="<?php echo $posts_row["id"]; ?>" class="col-md-12 styled-col post">
						<p><strong><?php echo $u; ?></strong> posted on <?php echo $posts_row["date_posted"]; ?>:</p>
						<p><?php echo $posts_row["content"]; ?></p>
						
						<?php if ($num_my_likes == 0) { ?>

							<span><?php echo $num_of_likes; ?> <button data="<?php echo $posts_row["id"]; ?>" class="like_sys"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Like</button></span>

						<?php } else { ?>

							<span><?php echo $num_of_likes; ?> <button data="<?php echo $posts_row["id"]; ?>" class="dislike_sys"><i class="fa fa-thumbs-down" aria-hidden="true"></i> Dislike</button></span>

						<?php } ?>

						<a class="float-right" href="post.php?p_id=<?php echo $posts_row["id"]; ?>">Comment <i class="fa fa-comments" aria-hidden="true"></i></a>
					</div>
					<?php endwhile; 

					} else {

						echo "<div class='text-center'>You have no posts yet</div>";

					} ?>
				</div>
			</div>
		</div>
	</div>


	<div id="status-container">
		<div id="status-msg" class="alert alert-success">
			<strong>Success!</strong>
		</div>
	</div>


	<!-- modal -->
	<div id="myModal" class="modal fade" role="dialog">

	  <div class="modal-dialog">

	    

	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">My Profile</h4>
	      </div>
	      <div class="modal-body">

			<?php 

				$sql_user = "SELECT * FROM users WHERE username = '{$u}' LIMIT 1";
				$query_user = $db->query($sql_user);
				$row_user = $query_user->fetch_assoc();

				$image_path = "users/" . $u . "/";
				$image_name = $row_user["image"];

				if($image_name == "") {
					$image = "http://via.placeholder.com/150x150";
				} else {
					$image = $image_path . $image_name;
				}

				$firstname = $row_user["firstname"];
				$lastname = $row_user["lastname"];
				$gender = $row_user["gender"];

			?>

	      	<div class="form-group text-center">
	        	<label for="profile_pic">Profile picture:</label><br>
	        	<img id="profile_pic" class="img-thumbnail" src="<?php echo $image; ?>" alt=""><br><br>
	        	<form id="form_change_img" action="" enctype="multipart/form-data" onsubmit="return false;">
	        		<label for="profile_img"><strong>I want to change the profile pic!</strong></label>
	        		<input class="form-control" type="file" name="img"><br>
	        		<input id="upload_img" type="submit" class="btn btn-primary" value="Change Picture">
	        	</form>
	        </div>

			<form id="change_personal" onsubmit="return false;">
		        <div class="form-group">
		        	<label for="firstname">Firstname:</label>
		        	<input class="form-control" type="text" name="firstname" id="fname" value="<?php echo $firstname; ?>">
		        </div>
		        <div class="form-group">
		        	<label for="lastname">Lastname:</label>
		        	<input class="form-control" type="text" name="lastname" id="lname" value="<?php echo $lastname; ?>">
		        </div>
		        <div class="form-group">
		        	<label for="gender">Gender:</label>
		        	<select class="form-control" name="gender" id="selected_gender">
		        		<?php 

		        			if ($gender == "male") {
		        				echo "<option value='male'>Male</option><option value='female'>Female</option>";
		        			} else {
		        				echo "<option value='female'>Female</option><option value='male'>Male</option>";
		        			}

		        		?>
		        	</select>
		        </div>
		        <div class="form-group" style="text-align: center;">
		        	<input id="update_personal" type="submit" class="btn btn-success" value="Update my personal information">
		        </div>
			</form>

	        <div style="text-align:center ">OR</div><br>
	        
	      
	      	<div style="text-align: center">
	      		<button id="change_email" type="button" class="btn btn-info">Change email</button>
	      	</div>
	      	<br>
	      	</div>
	    </div>

	  </div>
	</div>


	<script>

		$(document).on("click", ".like_sys", function() {
	
			var post_id = $(this).attr("data");
			var user_id = <?php echo $logged_user; ?>;
			var this_btn = $(this);

			$.ajax({
				url: "ajax/ajax.php",
				method: "POST",
				data: {post_id: post_id, user_id: user_id},
				success: function(data) {
					if (data != "already_liked") {
						this_btn.parent().html(data);
					}
				}
			})

		})

		$(document).on("click", ".dislike_sys", function() {
			
			var post_id = $(this).attr("data");
			var user_id = <?php echo $logged_user; ?>;
			var this_btn = $(this);

			$.ajax({
				url: "ajax/ajax.php",
				method: "POST",
				data: {post_id2: post_id, user_id2: user_id},
				success: function(data) {
					this_btn.parent().html(data);
				}
			})

		})

		$(document).on("click", "#change_email", function() {

			$("#status-msg").html("A request has been sent to your email").fadeIn().delay(2000).fadeOut();
			$(this).attr("disabled", "true");

		});

		$(document).on("click", "#update_personal", function() {

			var form = document.querySelector("#change_personal");
			var dataForm = new FormData(form);
			var req = new XMLHttpRequest();

			req.open("POST", "ajax/updating_user.php", true);
			req.send(dataForm);

			req.onreadystatechange = function() {
				if (req.readyState == 4 && req.status == 200) {
					var returnedData = req.responseText;
					$("#status-msg").html("Your <strong>personal information</strong> has been updated!").fadeIn().delay(2000).fadeOut();
				}
			}

		});

		$(document).on("click", "#upload_img", function() {

			var form = document.querySelector("#form_change_img");
			var req = new XMLHttpRequest();
			var formData = new FormData(form);

			req.open("POST", "ajax/updating_user.php", true);
			req.send(formData);

			req.onreadystatechange = function() {
				if (req.readyState == 4 && req.status == 200) {
					var returnedData = req.responseText;
					if (returnedData != "nope") {
						$("#profile_pic").attr("src", returnedData);
						$("#status-msg").html("Profile Image has been updated!").fadeIn().delay(2000).fadeOut();
						$("#profile_picture").attr("src", returnedData);
					}
										
				}
			}


		});
		

		$(document).on("click", ".action-button", function() {
			var id_friends;
			var remove_id;
			var adding_users_id;

			if ($(this).is("[accept]")) {
				id_friends = $(this).attr("accept");
				
				$.ajax({
					url: "ajax/ajax-friend-system.php",
					method: "POST",
					data: {id_friends: id_friends},
					success: function(data) {
						if (data == "now_friends") {
							$(".action-button").removeAttr("accept").removeClass("btn-warning").addClass("btn-danger").html("Remove from Friends").attr("remove", <?php echo $row2["id"]; ?>);
						}
					}
				});
			} else if ($(this).is("[remove]")) {
				remove_id = $(this).attr("remove");

				$.ajax({
					url: "ajax/ajax-friend-system.php",
					method: "POST",
					data: {remove_id: remove_id},
					success: function(data) {
						if (data == "friend_removed") {
							$(".action-button").removeAttr("remove").removeClass("btn-danger").addClass("btn-primary").html("Add as Friend").attr("add", <?php echo $row["id"]; ?>);
						}
					}
				});
			} else if ($(this).is("[add]")) {
				adding_users_id = $(this).attr("add");

				$.ajax({
					url: "ajax/ajax-friend-system.php",
					method: "POST",
					data: {adding_users_id: adding_users_id},
					success: function(data) {
						if (data == "request_sent") {
							$(".action-button").removeAttr("add").removeClass("btn-primary").addClass("btn-warning").html("Request Sent");
						}
					}
				});
			}
		});

	</script>


	<?php } else {
		echo "User doesn't exist";
		exit();
	}

}

?>

<?php require_once("includes/footer.php"); ?>

