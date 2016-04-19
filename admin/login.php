<?php 
include_once("../config.php");
include_once("includes/lib.php");

session_start();

$_SESSION['user'] = isset($_SESSION['user']) ? $_SESSION['user'] : '';
$_SESSION['pass'] = isset($_SESSION['pass']) ? $_SESSION['pass'] : '';

if(isset($_POST["btnSubmit"])) {
	$user = isset($_POST['txtUser']) ? $_POST['txtUser'] : '';
	$pass = isset($_POST['txtPass']) ? $_POST['txtPass'] : '';

	if(dangnhap($user, $pass)==true) {
		header("Location: index.php");
	}
}

if(xacthuc($_SESSION['user'], $_SESSION['pass'])==true) {
	header("Location: index.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Đăng nhập hệ thống</title>
<link href="css/csslogin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/lib.js"></script>
</head>

<body>


<div class="login-container">
<img  class="profile-img-card" src="images/386969_410374179010252_1826062238_n.jpg" alt="" />
		<form method="post" action="" id="loginForm">
				<input type="text"  class="text" name="txtUser" id="txtUser" value="" class="form-text" />

			<input type="password" class="text" name="txtPass" id="txtPass" class="form-text" />
					<input type="submit" name="btnSubmit" id="btnSubmit" value="Đăng nhập" class="form-submit" />
		</form>
</div>

</body>
</html>
