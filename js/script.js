var username;
var firstname;
var lastname;
var password;
var password2;
var email;
var emailCheck;

var regex;
var mailRegex;

function restrict(id_name) {
	regex = new RegExp;
	if (id_name == "username") {
		regex = /[^a-zA-Z0-9]/gi;
		document.getElementById(id_name).value = document.getElementById(id_name).value.replace(regex, "");
	} else if (id_name == "email") {
		regex = /[^.@a-z0-9]/gi;
		document.getElementById(id_name).value = document.getElementById(id_name).value.replace(regex, "");
	}
}

function checkUsername() {
	username = $("#username").val();

	if (username != "") {
		$.ajax({
			url: "ajax/ajax.php",
			data: {user: username},
			method: "POST",
			success: function(data) {
				$("#username_status").removeClass("hidden");
				$("#username_status").html(data);
			}
		});
	 } else {
		$("#username_status").addClass("hidden");
	}

	
}

function passwordMatch() {

	password = $("#password").val();
	password2 = $("#password2").val();

	if (!password == "" && !password2 == "") {

		if (password == password2) {
			$("#password_match").html("<br><div class='alert alert-success'>Passwords match</div>");
		} else {
			$("#password_match").html("<br><div class='alert alert-danger'>Passwords do not match</div>");
		}

	} else {
		$("#password_match").html("");
	}

	

}

$(document).on("focus", "#reg_form", function() {
	$("#status").html("");
});

$(document).on("click", "#register", function() {

	firstname = $("#firstname").val();
	lastname = $("#lastname").val();
	username = $("#username").val();
	password = $("#password").val();
	password2 = $("#password2").val();
	gender = $("#gender").val();
	email = $("#email").val();

	if (firstname == "" || lastname == "" || username == "" || password == "" || password2 == "" || gender == "" || email == "") {
		$("#status").html("<br><div class='alert alert-danger'>All fields are required!</div>");
	} else {
		$.ajax({
			url: "ajax/ajax.php",
			data: {fname: firstname, lname: lastname, uname: username, pass: password, p2: password2, gender: gender, email: email},
			method: "POST",
			success: function(data) {
				if (data == "created") {
					$("#reg_form").html("Congratulations!<br> <strong>" + username + "</strong> has been created! <br><br>You will recieve an email to: <strong>" + email + "</strong> where you can activate your account, so you can login");
				} else {
					$("#status").html(data);
				}
			}
		});
	}

});

function checkEmail() {

	mailRegex = new RegExp;

	emailCheck = $("#email").val();
	mailRegex = /[0-9a-z.]+@[a-z]+.[a-z]+/gi;


	if (!mailRegex.test(emailCheck)) {
		$("#email_status").html("Invalid email");
		if (emailCheck == "") {
			$("#email_status").html("");
		}
		return;
	}

	if (email != "") {
		$.ajax({
			url: "ajax/ajax.php",
			method: "POST",
			data: {e: emailCheck},
			success: function(data) {
				$("#email_status").html(data);
			}
		});
	}

}

//NOT FUNCTIONING PROPERLY ker ne zajema vseh notifications hkrati

// function check_notifications() {

// 	var name = $("#notification").attr("data");
// 	if (name != "") {
// 		$.ajax({
// 			url: "ajax/ajax.php",
// 			method: "POST",
// 			data: {name: name},
// 			success: function(data) {
// 				$("#notification").html("Notifications (" + data + ")");
// 			}
// 		});
// 	}
	
// }


// setInterval(check_notifications, 5000);
$(document).on("click", "#submit_post", function() {
	$(".no_posts").remove();
});


function check_messages() {

	var my_id = $("#messages_count").attr("data");

	$.ajax({
		url: "ajax/ajax-chat.php",
		method: "POST",
		data: {my_id: my_id},
		success: function(data) {
			$("#messages_count").html("Messages (" + data + ")")
		}
	})

}



setInterval(check_messages, 1000);


$(document).ready(function() {

	// $("#status-msg").hide();

	$("#reg_field").hide();

	$("#reg_field").slideDown("slow");


});