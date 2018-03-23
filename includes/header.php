<?php 

  require_once("classes/init.php");

  if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
  }

  $id = $_SESSION["user_id"];
  preg_replace("/^[0-9]+/", "", $id);
  global $db;

  $get_noti_for_user = User::get_id($id);
  $username_notification = $get_noti_for_user->username;

  $post_noti_sql = "SELECT * FROM post_notifications WHERE did_read = '0' AND friends_id = '{$id}'";
  $query_post_noti = $db->query($post_noti_sql);
  $num_of_post_noti = $query_post_noti->num_rows;

  $comment_noti = "SELECT * FROM comment_notifications WHERE did_read = '0' AND post_owner = '{$username_notification}' AND initiator != '{$username_notification}'";
  $query_comment_noti = $db->query($comment_noti);
  $num_of_comment_noti = $query_comment_noti->num_rows;

  $notifications_sql = "SELECT * FROM notifications WHERE username = '{$username_notification}' AND did_read = '0'";
  $notification_sql_query = $db->query($notifications_sql);
  $noti_exists = $notification_sql_query->num_rows;
  $number_of_noti;
  if ($noti_exists > 0) {
    $number_of_noti = $noti_exists;
  } else {
    $number_of_noti = 0;
  }

?>

 <!DOCTYPE html>
 <html>
 <head>
 	<title>Social Network</title>

	<meta charset="utf-8">
	<link rel="stylesheet" href="fontAwesome/css/font-awesome.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

	<link rel="stylesheet" href="css/css.css">

	
	<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>	

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>	
	
 </head>
 <body style="background-image: url('images/body-bg.jpg'); background-repeat: no-repeat; background-position:center; background-size:cover; min-height: 800px">
 
<nav class="navbar navbar-toggleable-md navbar-light bg-faded">
  <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="index.php">SocialNetwork</a>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <?php 

        $sql = "SELECT * FROM message_notification WHERE user2 = '{$id}' AND is_read = '0'";
        $query = $db->query($sql);
        $number_of_msg = $query->num_rows;

        ?>
        <a class="nav-link" id="messages_count" data="<?php echo $id; ?>" href="messages.php">Messages (<?php echo $number_of_msg; ?>)</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" style="color: red;" href="profile.php?u=<?php $u = User::get_id($_SESSION['user_id']); echo $u->username; ?>">Logged in as: <?php $user = User::get_id($id); echo $user->username; ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="notification" data="<?php echo $username_notification; ?>" href="notifications.php?u=<?php echo $user->username; ?>">Notifications (<?php echo $number_of_noti + $num_of_post_noti + $num_of_comment_noti; ?>)</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <div class="search_box">
        <input id="search_text" class="form-control mr-sm-2" type="text" placeholder="Search for friends">
        <ul id="result">
        </ul>
      </div>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </form>
  </div>
</nav>


<script>
  

$(document).on("keyup", "#search_text", function() {

  var insert_text = $(this).val();

  if (insert_text != "") {
    $.ajax({
      url: "ajax/ajax.php",
      method: "POST",
      data: {insert_text: insert_text},
      success: function(data) {
        $("#result").html(data);
      }
    });
  } else {
    $("#result").html("");
  }

});


</script>