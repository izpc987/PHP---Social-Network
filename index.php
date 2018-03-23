
<?php require_once("includes/header.php"); ?>


<div class="container-fluid">
	<div class="row index-row">
		<div class="col-md-2">
			<!-- <div class="col-md-12 styled-col">
				<h4 class="text-center">Profile <i class="fa fa-user" aria-hidden="true"></i></h4>
			</div> -->
		</div>
		<div class="col-md-7" id="posting_section">
			<div class="col-md-12 styled-col">
				<h4 class="text-center">Share your thoughts <i class="fa fa-paper-plane-o" aria-hidden="true"></i></h4>
				<br>
				<div class="publish_post">
					<form class="posting_form" onsubmit="return false;">
						<div class="form-group">
							<textarea placeholder="I am thinking about..." id="post_content" cols="30" rows="4" class="form-control" style="border: 1px solid grey; box-shadow: 0px 0px 10px lightgrey"></textarea>
						</div>
						<div class="form-group" style="text-align: right;">
							<button class="btn btn-primary" id="submit_post">Post</button>
						</div>
					</form>
				</div>

			</div>
			<div id="all_posts">
				<?php 

				$logged_user = $_SESSION["user_id"];

				$sql1 = "SELECT * FROM friends";
				$query1 = $db->query($sql1);
				$friends_exist = $query1->num_rows;

				$sql;

				if ($friends_exist == 0) {
					$sql = "SELECT * FROM posts WHERE author = '{$logged_user}' ORDER BY id DESC";

				} else {
					$sql = "SELECT posts.id AS posts_id, posts.author, posts.content, posts.date_posted, posts.comments, friends.id, friends.user1, friends.user2, friends.level, friends.date FROM posts,friends WHERE (posts.author = '{$logged_user}') OR (friends.user1 = posts.author AND friends.user2 = '{$logged_user}' AND friends.level = '1') OR (friends.user2 = posts.author AND friends.user1 = '{$logged_user}' AND friends.level = '1') GROUP BY posts.id ORDER BY posts_id DESC";
				}

				
				$query = $db->query($sql);
				$num_of_posts = $query->num_rows;
				if ($num_of_posts != 0) {
				while ($row = $query->fetch_assoc()):

					$class;
					$iclass;
					$content;

					$posts_id; // if friends exist or not, this changes

					$user = User::get_id($row["author"]);
					$the_logged_username = User::get_id($logged_user);

					$date = new DateTime($row["date_posted"]);
					$result = $date->format("d.m.Y");

					if ($friends_exist == 0) { 

						$posts_id = $row["id"];

					} else {
						$posts_id = $row["posts_id"];
					}

					$sql2 = "SELECT * FROM posts WHERE author = '{$row["author"]}' AND id = '{$posts_id}'";
					$query3 = $db->query($sql2);
					$row2 = $query3->fetch_assoc();
					$post_id = $row2["id"];

					$sql_all_likes = "SELECT * FROM likes WHERE post_id = '{$posts_id}'";
					$query_all_likes = $db->query($sql_all_likes);
					$num_of_acctual_likes = $query_all_likes->num_rows;

					$sql_likes = "SELECT * FROM likes WHERE post_id = '{$posts_id}' AND like_count = '1' AND user_id = '{$logged_user}'";
					$query_likes = $db->query($sql_likes);
					$num_of_likes = $query_likes->num_rows;

				?>

				<div class="col-md-12 styled-col post">
					<p><strong><?php echo $user->username == $the_logged_username->username ? "I ({$the_logged_username->username})" : $user->username; ?></strong> posted on <?php echo $result; ?>:</p>
					<p><?php echo $row["content"]; ?></p>
					
						<?php 

							if ($num_of_likes == 0) {
								$class = "like_sys";
								$iclass = "fa fa-thumbs-up";
								$content = "Like";
							} else {
								$class = "dislike_sys";
								$iclass = "fa fa-thumbs-down";
								$content = "Dislike";
							}

						?>

						<span class="like_system_span"><?php echo $num_of_acctual_likes; ?> <button data="<?php echo $posts_id; ?>" class="<?php echo $class; ?>"><i class="<?php echo $iclass; ?>" aria-hidden="true"></i> <?php echo $content; ?></button></span>

					<a class="float-right" href="post.php?p_id=<?php echo $posts_id; ?>">Comment <i class="fa fa-comments" aria-hidden="true"></i></a>
				</div>

				<?php endwhile;} else {echo "<div class='no_posts styled-col post'>Noone posted anything yet</div>";} ?>

			</div>
		</div>
		<div class="col-md-3">
			<div class="col-md-12 styled-col" id="friends">
				<h4 class="text-center">Add Others <i class="fa fa-users" aria-hidden="true"></i></h4>
				<br>

				<div class="all-people">
					<?php 

					$sql = "SELECT * FROM users WHERE NOT id = '{$id}' ORDER BY RAND() LIMIT 4";
					$users = User::query($sql);

					foreach ($users as $user): 

						$img;

					if (empty($user->image)) {
						$img = "http://via.placeholder.com/80x80";
					} else {
						$img = "users/$user->username/$user->image";
					}

					?>

						<div class="person">
							<a href="profile.php?u=<?php echo $user->username; ?>" class="person-flex">
								<div style='height: 75px; width: 75px; overflow:hidden; border-radius: 100px'>
									<img style="height: 75px; width: auto;" src=<?php echo $img; ?> alt="">
								</div>
								<p class="user_name"><?php echo $user->username; ?></p>
							</a>
						</div>

					<?php endforeach; ?>

				</div>
			</div>
		</div>
	</div>
</div>

<script>

$(document).on("click", "#submit_post", function() {

	var post_text = $("#post_content").val();

	if (post_text != "") {

		$.ajax({
			url: "ajax/ajax-posting-system.php",
			method: "POST",
			data: {post_text: post_text},
			success: function(data) {
				if (data) {
					$("#all_posts").prepend(data);
					$("#post_content").val("");
				}
			}
		});
		
	}
	


});
	

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

</script>

<?php require_once("includes/footer.php"); ?>