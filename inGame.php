<?php

	session_start();
	
	if (!isset($_SESSION['logged']))
	{
		header('Location: index.php');
		exit();
	}
	
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Management Game</title>
</head>
<!---//////////////////////////////////////////////////////////////////////////--->
<body>
	
<?php

	echo "<p>Hello ".$_SESSION['user'].'! [ <a href="logout.php">logout!</a> ]</p>';
	echo "<b>food</b>: ".$_SESSION['food']."</p>";
	echo "<p><b>wood</b>: ".$_SESSION['wood'];
	echo "<b>stone</b>: ".$_SESSION['stone'];
	echo "<p><b>E-mail</b>: ".$_SESSION['email'];
	echo "<br /><b>Day of premium</b>: ".$_SESSION['premium']."</p>";
	
?>

</body>
</html>