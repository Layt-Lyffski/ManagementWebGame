<?php

	session_start();
	
	if (!isset($_SESSION['accomplishedRegister'])){
		header('Location: index.php');
		exit();
	}
	else{
		unset($_SESSION['accomplishedRegister']);
	}
	
	//delete the values of var
	if (isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
	if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
	if (isset($_SESSION['fr_pw1'])) unset($_SESSION['fr_pw1']);
	if (isset($_SESSION['fr_pw2'])) unset($_SESSION['fr_pw2']);
	if (isset($_SESSION['fr_agreement'])) unset($_SESSION['fr_agreement']);
	
	//delete the errors
	if (isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
	if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
	if (isset($_SESSION['e_pw'])) unset($_SESSION['e_pw']);
	if (isset($_SESSION['e_agreement'])) unset($_SESSION['e_agreement']);
	if (isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
	
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Management game</title>
</head>

<body>
	
	<a href="index.php">Login into your account!</a>
	<br /><br />

</body>
</html>