<?php require_once("includes/header.php"); ?>

<?php 

if (isset($_GET["p_id"])) {

$p_id = $_GET["p_id"];
global $db;

$p_id = $db->escape_string($p_id);
$p_id = preg_replace("/[^0-9]/", "", $p_id);

$sql = "SELECT * FROM posts WHERE id = '{$p_id}'";
$query = $db->query($sql);
$exists_id = $query->num_rows;

$l_user = User::get_id($_SESSION["user_id"]);
$logged_user = $l_user->username;

$username;
$author;
$content;
$likes;
$date_posted;
$comments;
$id;

if ($exists_id != 0) {

	$row = $query->fetch_assoc();
	
	$author = $row["author"];
	$content = $row["content"];
	$likes = $row["likes"];
	$date = new DateTime($row["date_posted"]);
	$the_date = $date->format("d.m.Y");
	$comments = $row["comments"];
	$id = $row["id"];

	$user = User::get_id($author);
	$username = $user->username;
	

} else {
	echo "This post doesn't exist man...";
	exit();
}

}

?>

<div class="container">
	<div class="col-md-8 styled-col post">
		<p><strong><?php echo $username; ?></strong> posted on <?php echo $the_date; ?>:</p>
		<p><?php echo $content; ?></p>

		<?php 

		$sql_likes = "SELECT * FROM likes WHERE post_id = '{$id}' AND like_count = '1'";
		$query_likes = $db->query($sql_likes);
		$num_of_likes = $query_likes->num_rows;

		$sql_my_likes = "SELECT * FROM likes WHERE post_id = '{$id}' AND like_count = '1' AND user_id = '{$_SESSION["user_id"]}'";
		$query_my_likes = $db->query($sql_my_likes);
		$num_of_my_likes = $query_my_likes->num_rows;

		if ($num_of_my_likes == 0) { ?>
		
			<span><?php echo $num_of_likes; ?> <button data="<?php echo $id; ?>" class="like_sys"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Like</button></span>

		<?php } else { ?>
		
			<span><?php echo $num_of_likes; ?> <button data="<?php echo $id; ?>" class="dislike_sys"><i class="fa fa-thumbs-down" aria-hidden="true"></i> Dislike</button></span>

		<?php } ?>


	</div>
	<div class="col-md-8 styled-col comments">
		<div class="form-group">
			<textarea id="comment_textarea" cols="30" rows="2" class="form-control" placeholder="My comment..."></textarea>
		</div>
		<div style="text-align: right;" class="form-group">
			<button id="post_comment" class="btn btn-primary">Comment</button>
		</div>
		<div id="comments_container">

		<?php 

		$comment_sql = "SELECT * FROM comments WHERE post_id = '{$p_id}' ORDER BY id DESC";
		$comment_query = $db->query($comment_sql);
		$comment_count = $comment_query->num_rows;

		if ($comment_count != 0) {

			while ($comment_row = $comment_query->fetch_assoc()) {
				$author = $comment_row["author"];
				$content = $comment_row["content"];
				$date = $comment_row["date"]; 

				$newtime = new DateTime($date);
				$date = $newtime->format("d.m.Y");

				?>

				<div class="all_comments">
					<p><strong><?php echo $author; ?></strong> commented on <?php echo $date; ?>: </p>
					<p style='margin: 0;'><?php echo $content; ?></p>
				</div>

			<?php

			 }

		}	?>

		</div>
	</div>
</div>


<script>


		$(document).on("click", ".like_sys", function() {
	
			var post_id = $(this).attr("data");
			var user_id = <?php echo $_SESSION["user_id"]; ?>;
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
			var user_id = <?php echo $_SESSION["user_id"]; ?>;
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

	
	$(document).on("click", "#post_comment", function() {

		var comment_text = $("#comment_textarea").val();
		var post_id = <?php echo $p_id; ?>;
		var author = "<?php echo $logged_user; ?>";

		$.ajax({
			url: "ajax/ajax-comment-system.php",
			method: "POST",
			data: {comment_text: comment_text, post_id: post_id, author: author},
			success: function(data) {
				$("#comments_container").prepend(data);
			}
		});

	});

</script>


<?php require_once("includes/footer.php"); ?>