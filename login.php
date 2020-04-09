<?php
	session_start();
	if ((!isset($_POST['login'])) || (!isset($_POST['password']))){
		header('Location: index.php');
		exit();
	}
	require_once "connect.php";
	$conn = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($conn->connect_errno!=0){
		echo "Error: ".$conn->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$password = $_POST['password'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		if ($result = @$conn->query(
		sprintf("SELECT * FROM users WHERE user='%s'",
		mysqli_real_escape_string($conn,$login)))){
			$amountU = $result->num_rows;
			if($amountU>0){
				$vers = $result->fetch_assoc();
				
				if (password_verify($password, $vers['pass'])){
					$_SESSION['logged'] = true;
					$_SESSION['id'] = $vers['id'];
					$_SESSION['user'] = $vers['user'];
					$_SESSION['wood'] = $vers['wood'];
					$_SESSION['stone'] = $vers['stone'];
					$_SESSION['food'] = $vers['food'];
					$_SESSION['email'] = $vers['email'];
					$_SESSION['premium'] = $vers['premium'];
					
					unset($_SESSION['error']);
					$result->free_result();
					header('Location: inGame.php');
				}
				else {
					$_SESSION['error'] = '<span style="color:red">Wrong login or password</span>';
					header('Location: index.php');
				}
				
			} else {
				$_SESSION['error'] = '<span style="color:red">Wrong login or password</span>';
				header('Location: index.php');
			}
		}
		$conn->close();
	}
?>