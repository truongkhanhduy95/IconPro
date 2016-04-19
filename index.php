<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Icons Pro</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div class"main">
    	<!--header-->
    	<header>
        	<div class="logo">
                    <div class="lg">
                        <a href="index.php"><div class="logo_icon"></div></a>
                    </div>
                    <nav>
                        <div id="nav" class="login">
                            <ul>
                                <li><a href="index.php?pages=Icons">Icons</a></li>
                                <li><a href="index.php?pages=Icons">Top Product</a></li>
                                <li><a href="index.php?pages=Icons">Buy Icons</a></li>
                                <li><a href="index.php?pages=Icons   ">About Us</a></li>
                                
                            </ul>
                            
                        </div>
                        
                        <div class="login">
                            <ul>
                                <li><a href="index.php?pages=Login">Login</a></li>
                                <li><a href="index.php?pages=Register">Register</a></li>
                            </ul>
                        </div>
                	</nav>
                    
          	</div>
             <div class="clear"></div>
        </header>
         <div class="clear"></div>
    	<!--content-->
    	<div id="content">
        	<?php
				error_reporting(0);
				$pages=$_GET['pages'];
				if(!isset($pages))
				{
				
					include_once "pages/home.php";
					include_once "pages/footer.php";
					
				}
				else if($pages=='Icons')
				{	
					include "pages/left.php";
					include_once "pages/icons.php";
					
				}
				else if($pages=='Login')
				{	
					include_once "pages/login.php";
					include_once "pages/footer.php";
					
				}
				else if($pages=='Register')
				{	
					include_once "pages/register.php";
					include_once "pages/footer.php";
					
				}
				
			?>
        </div>
        <div class="clear"></div>
        
    </div>
</body>
</html>