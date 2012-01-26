<?php
require_once("../include/connect.php");

$data["username"] = "admin";
$data["password"] = "admin";

$error_message = '';

if(isset($_POST["username"]))
{
	if($_POST["username"] == $data["username"]) 
	{
		if($_POST["password"] == $data["password"]) 
		{
			session_start();
			//session_register("login");
			//session_register("login_name");
			
			$_SESSION["login"]		= 1;
			$_SESSION["login_name"] = $data["username"];
			
			header("Location: index.php");
			exit();
		} 
		else 
		{
			$error_message =  "Incorrect Username or Password";
		}
	} 
	else
	{
		$error_message =  "Incorrect Username or Password";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<?php include('inc.css.php'); ?>
<style type="text/css">
.error_message
{
	color: #F00;
	font-size: 14px;
	line-height: 36px;
	text-align: center;
	text-shadow: none;
}
#submit { margin-top: 15px; }
</style>
</head>

<body class="center_box">
	<div class="gadget">
		<div class="error_message"><?php echo $error_message; ?></div>
		<form action="login.php" method="post">
			<label for="user">Username : </label>
			<input name="username" type="text" id="username" value="<?php if(isset($_POST['username'])) echo $_POST['username']; ?>" size="36" />
			<label for="pass">Password : </label>
			<input name="password" type="password" id="password" size="36" />
			<label class="center">
				<input id="submit" name="submit" type="submit" value="Login" />
			</label>
		</form>
	</div>
</body>
</html>