<?php require "shrc.php" ?>
<?php 
    if($_POST[password] == $shellPW
        && $_POST[username] == $shellACT){
        	setcookie("login",'USER', time()+360);
            header("Location:./sh.php");
    }
?>
<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=PT+Mono' rel='stylesheet' type='text/css'>
	<style>
	* {
    margin: 0px auto;
	}

	</style>
	</head>

	<body background="/~s10410/login-bg.jpg" >
	<br><br><br><br><br><br>
	<center>
		<h1 style="padding:0;text-align:center; color:yellow;">	
			<span title="Login System" style="font-size:2.9rem;font-family: 'Gloria Hallelujah', cursive ,Arial, Helvetica, sans-serif;">Shell Login</span>
		</h1>
		<br>
        <form method="post" action="login.php" style="width:100%;margin:0 auto;">
            <div><label for="user_login">
                <input id="username" type="text" size="28" name="username" value="" style="font-family:'PT Mono', sans-serif;border: 1px solid rgb(120, 59, 168);margin-bottom: 15px;font-size: 107% !important;padding: 5px; height: 35 px;width: 10rem;border-radius: 3px;" onblur="if(this.value=='') this.value=this.defaultValue" onfocus="if(this.value==this.defaultValue) this.value=''" placeholder="Username" />
			</label></div>
            <div><label for="user_password">
                <input id="password" type="password" size="28" name="password" value=""  style="font-family:'PT Mono', sans-serif;border: 1px solid rgb(120, 59, 168);margin-bottom: 15px;font-size: 107% !important;padding: 5px;height: 35px;width: 10rem;border-radius: 3px;" onblur="if(this.value=='') this.value=this.defaultValue" onfocus="if(this.value==this.defaultValue) this.value=''" placeholder="Password" />
			</label></div>
           <div style="margin:0 auto;text-align:center;"><button type="submit" style="font-size:1.2rem;font-family: 'PT Mono', sans-serif;border-radius: 2px;background-color: rgb(110, 50, 157);padding: 5px;background-image: -moz-linear-gradient(left center , rgb(110, 50, 157) 0%, rgb(110, 50, 157) 100%);border: 1px solid rgb(82, 38, 117);color: #FFF;line-height: 1.88rem;width: 10rem;" title="Pass" name=".login" value="Pass" >Pass</button></div>
        </form>
	</center>
	</body>
</html>
