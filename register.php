<?php require_once("classes/init.php"); ?>

<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<link rel="stylesheet" href="css/css.css">
</head>
<body style="background-image: url('images/bg-img.jpg'); background-position: center; background-size: cover; background-repeat: no-repeat;">

	<div class="container">
		<div class="row" id="styled_row">
			<div class="col-md-12 text-center" style="background-color: rgba(255, 255, 255, 0.8); border: 1px solid white; box-shadow: 0px 0px 5px lightgrey; width: 400px; padding: 10px; border-radius: 10px">
				<div>Already registered? <a href="login.php" class="btn btn-success">Login</a> here!</div>
			</div><br>
			<div class="col-md-12 text-center" id="reg_field" style="background-color: rgba(255, 255, 255, 0.8); border: 1px solid white; box-shadow: 0px 0px 5px lightgrey; width: 400px; padding: 10px; border-radius: 10px">
				<form action="" onsubmit="return false;" id="reg_form">
					<div class="form-group">
						<label for="firstname">Firstname</label>
						<input type="text" name="firstname" class="form-control" id="firstname">
					</div>
					<div class="form-group">
						<label for="lastname">Lastname</label>
						<input type="text" name="lastname" class="form-control" id="lastname">
					</div>
					<div class="form-group">
						<label for="email">Email</label>
						<input onblur="checkEmail()" onkeyup="restrict('email')" type="text" name="email" class="form-control" id="email">
						<span id="email_status"></span>
					</div>
					<div class="form-group">
						<label for="username">Username</label>
						<input onblur="checkUsername()" onkeyup="restrict('username')" type="text" name="username" class="form-control" id="username">
						<span id="username_status" class="hidden"></span>
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input onblur="passwordMatch()" type="password" name="password" class="form-control" id="password">
					</div>
					<div class="form-group">
						<label for="password2">Confirm Password</label>
						<input type="password" onblur="passwordMatch()" name="password2" class="form-control" id="password2">
						<span id="password_match"></span>
					</div>
					<div class="form-group">
						<label for="gender">Gender</label>
						<select name="gender" id="gender" class="form-control">
							<option value="male">Male</option>
							<option value="female">Female</option>
						</select>
					</div>
					<input type="submit" id="register" value="Register" class="btn btn-primary">
					<div id="status"></div>
				</form>
			</div>
		</div>
	</div>

<script src="js/script.js"></script>

</body>
</html>