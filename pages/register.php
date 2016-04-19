<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	$connect=mysql_connect('localhost','root','');
	mysql_select_db('dang nhap');
?>
<?php
	session_start();
	if(isset($_SESSION['username']))
	{
		echo "Xin chao ".$_SESSION['username'];
	}
	else
	{
		if(isset($_POST['submit'])){
			$sql=mysql_query('SELECT * FROM user WHERE username="'.$_POST['user'].'" and password="'.$_POST['pass'].'"');
			if(mysql_num_rows($sql)>0){
				echo "Ban da dang nhap thanh cong, xin chao ".$_POST['user'];
			$_SESSION['username']=$_POST['user'];
			}
			else{
				echo "User hoac password khong dung";
				}
		}
		?>
	
	<form class="form_login" action="index.php" method="post">
    	<h1> Register to IconPro </h1>
    	<div id="lb"><label>Username:</label></div> <input type="text" name="user" /><br />
        <div id="lb"><label>Password:</label></div> <input type="password" name="pass" /><br />
        <input type="submit" name="submit" value="Register"/>
    </form><?php
	
	}?>
</body>
</html>