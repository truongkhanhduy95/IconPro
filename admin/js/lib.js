// JavaScript Document
/*
function kiemtra() {
	var user = document.getElementById("txtUser");
	var pass = document.getElementById("txtPass");
	
	if(user.value=="") {
		alert("Yêu cầu nhập vào Ký danh!");
		user.focus();
		return false;
	} 
	else if(user.value.length<5 || user.value.length>16) {
		alert("Yêu cầu nhập vào trong khoảng 4-16 ký tự!");
		user.focus();
		return false;
	}
	
	if(pass.value=="") {
		alert("Yêu cầu nhập vào Mật khẩu!");
		pass.focus();
		return false;
	}
	
	return true;
}
*/

$(document).ready(function() {
	$("#btnSubmit").click(function() {
	
		if($("#txtUser").val()=="") {
			alert("Yêu cầu nhập vào Ký danh!");
			$("#txtUser").focus();
			return false;
		} 
		else if($("#txtUser").val().length<5 || $("#txtUser").val().length>16) {
			alert("Yêu cầu nhập vào trong khoảng 4-16 ký tự!");
			$("#txtUser").focus();
			return false;
		}
		
		if($("#txtPass").val()=="") {
			alert("Yêu cầu nhập vào Mật khẩu!");
			$("#txtPass").focus();
			return false;
		}
	});
	
	
	$("#txtPass").mouseover(function() {
		$("#txtPass").focus();
	});
});

/*
$.validator.setDefaults({
	submitHandler: function() {
		alert("submitted!");
	}
});


$(document).ready(function() {
						   
	// validate the comment form when it is submitted
	//$("#commentForm").validate();

	// validate signup form on keyup and submit
	$("#mUsers_Form").validate({
		
		rules: {
			Firstname: "required",
			Lastname: "required",
			Username: {
				required: true,
				minlength: 2
			},
			Password: {
				required: true,
				minlength: 5
			},
			PasswordVerify: {
				required: true,
				minlength: 5,
				equalTo: "#Password"
			},
			Email: {
				required: true,
				email: true
			}
		},
		messages: {
			Firstname: "Please enter your firstname",
			Lastname: "Please enter your lastname",
			Username: {
				required: "Please enter a username",
				minlength: "Your username must consist of at least 2 characters"
			},
			Password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			PasswordVerify: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long",
				equalTo: "Please enter the same password as above"
			},
			Email: "Please enter a valid email address"
		}
	});
});
*/