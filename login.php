<?php 

require_once("classes/init.php");

$e;
$p;

if (isset($_SESSION["user_id"])) {
	header("Location: index.php");
}

if (isset($_POST["login"])) {
	global $db;

	$email = $db->escape_string($_POST["email"]);
	$password = $db->escape_string($_POST["password"]);

	$user = User::verify_user($email, $password);

	if ($session->login($user)) {
		header("Location: index.php");
	} else {
		$_SESSION["login_problem"] = "Email or password is incorrect";
	}

	if (isset($_POST["rememberMe"])) {

		global $db;

		$email = $db->escape_string($_POST["email"]);
		$password = $db->escape_string($_POST["password"]);

		setcookie("login_cookie", $email . "," . $password, time() + (86400 * 365), "/");
	}

}

if (isset($_COOKIE["login_cookie"])) {
	$pieces = explode(",", $_COOKIE["login_cookie"]);
	$e = $pieces[0];
	$p = $pieces[1];
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<link rel="stylesheet" href="css/css.css">
</head>
<body style="background-image: url('images/bg-img.jpg'); background-position: center; background-size: cover; background-repeat: no-repeat;">

	<div class="container">
		<div class="row" id="styled_row2">
			<div class="col-md-12 text-center" id="login_field" style="background-color: rgba(255, 255, 255, 0.8); border: 1px solid white; box-shadow: 0px 0px 5px lightgrey; width: 400px; padding: 10px; border-radius: 10px">
				<form action="" method="post">
					<p style="font-size: 22px;">Login</p>
					<div class="form-group">
						<label for="email">Email</label>
						<input type="text" name="email" class="form-control" value="<?php if(isset($_COOKIE['login_cookie'])) {echo $e;} ?>">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" name="password" class="form-control" value="<?php if(isset($_COOKIE['login_cookie'])) {echo $p;} ?>">
					</div>
					<input type="checkbox" name="rememberMe" <?php if(isset($_COOKIE["login_cookie"])) {echo "checked";}; ?>> Remember me<br><br>
					<input type="submit" class="btn btn-primary" value="Log in" name="login"> or <a href="register.php" type="btn" class="btn btn-success" value="Register">Register</a>
					<div id="login_status"><?php if(isset($_SESSION["login_problem"])) { echo $_SESSION["login_problem"]; unset($_SESSION["login_problem"]); }?></div>
					<br>
					<a href="forgot_pass.php">Forgot password?</a>
				</form>
			</div>
		</div>
	</div>

<script>
	
	$(document).ready(function() {

		$("#login_field").hide();

		$("#login_field").slideDown("slow");

	});


</script>

</body>
</html>