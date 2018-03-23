<?php 

require_once("classes/init.php");


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
				<form action="" onsubmit="return false;" id="restore_password_form">
					<p style="font-size: 22px;">Restore your account</p>
					<div class="form-group">
						<label for="email">Email</label>
						<input type="text" name="email" class="form-control" placeholder="Enter your email" id="email">
					</div>
					<input type="submit" class="btn btn-success" value="Send me the email" id="restore_password">
					<br>
					<div id="status"></div>
				</form>
			</div>
		</div>
	</div>

<script>

var regex;

	$("#email").on("keyup", function() {
		var email;
		regex = new RegExp;
		regex = /[^@.a-z0-9]/gi;

		email = document.getElementById("email");
		email.value = email.value.replace(regex, "");

	});

	$("#email").on("blur", function() {

		regex = new RegExp;
		regex = /[^@.a-z0-9]/gi;

		document.getElementById("email").value = document.getElementById("email").value.replace(regex, "");

	});



	$("#restore_password").on("click", function() {

		var e;
		e = $("#email").val();

		if (e != "") {

			$.ajax({
				url: "ajax/ajax.php",
				data: {pass_reset: e},
				method: "POST",
				success: function(data) {
					if (data == "yes") {
						$("#restore_password_form").html("Temporary password has been successfully sent. Check your email in a couple of moments.");
					} else {
						$("#restore_password_form").html("The email could not be sent. Something went wrong :/<br><a href='login.php'>Click here to go back to login page</a>");
					}
				}
			});

		}

	});

	
	$(document).ready(function() {

		$("#login_field").hide();

		$("#login_field").slideDown("slow");

	});


</script>

</body>
</html>