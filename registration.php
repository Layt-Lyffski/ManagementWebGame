<?php
	session_start();
	if (isset($_POST['email']))
	{
		$allOK=true;
		
		//check the nick
		$nick = $_POST['nick'];
		
		//test the length
		if ((strlen($nick)<3) || (strlen($nick)>20)){
			$allOK=false;
			$_SESSION['e_nick']="Nick min length 3 max 20 sings!";
		}
		
		if (ctype_alnum($nick)==false){
			$allOK=false;
			$_SESSION['e_nick']="Nick can contain only alphanumeric sings";
		}
		
		// regex of email
		$email = $_POST['email'];
		$email_regex = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($email_regex, FILTER_VALIDATE_EMAIL)==false) || ($email_regex!=$email)){
			$allOK=false;
			$_SESSION['e_email']="Entry correct email!";
		}
		
		//validated the password
		$pw1 = $_POST['pw1'];
		$pw2 = $_POST['pw2'];
		
		if ((strlen($pw1)<8) || (strlen($pw1)>20)){
			$allOK=false;
			$_SESSION['e_pw']="Password have to be min 8 char or max 20!";
		}
		
		if ($pw1!=$pw2){
			$allOK=false;
			$_SESSION['e_pw']="Passwords do not equal to each other!";
		}	

		$pw_hash = password_hash($pw1, PASSWORD_DEFAULT);
		
		//Agreement denied ?
		if (!isset($_POST['agreement'])){
			$allOK=false;
			$_SESSION['e_agreement']="Accept the agreement!";
		}				
		
		
		$sec = "Entry the sec";
		
		$testSec = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sec.'&response='.$_POST['g-recaptcha-response']);
		
		$re = json_decode($testSec);
		
		if ($re->success==false){
			$allOK=false;
			$_SESSION['e_bot']="Prove that you are not a Bot";
		}		
		
		//save the data
		$_SESSION['fr_nick'] = $nick;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_pw1'] = $pw1;
		$_SESSION['fr_pw2'] = $pw2;
		if (isset($_POST['agreement'])) $_SESSION['fr_agreement'] = true;
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try{
			$conn = new mysqli($host, $db_user, $db_password, $db_name);
			if ($conn->connect_errno!=0){
				throw new Exception(mysqli_connect_errno());
			}
			else{
				
				$result = $conn->query("SELECT id FROM users WHERE email='$email'");
				
				if (!$result) throw new Exception($conn->error);
				
				$amountE = $result->num_rows;
				if($amountE>0){
					$allOK=false;
					$_SESSION['e_email']="The email have been already assigned to an account";
				}		

				//Nick free=?
				$result = $conn->query("SELECT id FROM users WHERE user='$nick'");
				
				if (!$result) throw new Exception($conn->error);
				
				$amountN = $result->num_rows;
				if($amountN>0){
					$allOK=false;
					$_SESSION['e_nick']="The Nick is already occupied by someone!";
				}
				
				if ($allOK==true){
					//allOK passed
					if ($conn->query("INSERT INTO users VALUES (NULL, '$nick', '$pw_hash', '$email', 100, 100, 100, 14)")){
						$_SESSION['accomplishedRegister']=true;
						header('Location: Hello.php');
					}
					else{
						throw new Exception($conn->error);
					}
					
				}
			
				$conn->close();
			}
			
		}
		catch(Exception $e){
			echo '<span style="color:red;">server error!</span>';
			echo '<br />InfoDev: '.$e;
		}
	}
	
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Management Game: create an account</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	
	<style>
		.error
		{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
	</style>
</head>
<!---////////////////////////////////////////////////////////////////////////////////////////////////////--->
<body>
	<form method="post">
	
		Nick: <br /> <input type="text" value="<?php
			if (isset($_SESSION['fr_nick'])){
				echo $_SESSION['fr_nick'];
				unset($_SESSION['fr_nick']);
			}
		?>" name="nick" /><br />
		
		<?php
			if (isset($_SESSION['e_nick'])){
				echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
				unset($_SESSION['e_nick']);
			}
		?>
		
		E-mail: <br /> <input type="text" value="<?php
			if (isset($_SESSION['fr_email'])){
				echo $_SESSION['fr_email'];
				unset($_SESSION['fr_email']);
			}
		?>" name="email" /><br />
		
		<?php
			if (isset($_SESSION['e_email'])){
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
		?>
		
		Password: <br /> <input type="password"  value="<?php
			if (isset($_SESSION['fr_pw1'])){
				echo $_SESSION['fr_pw1'];
				unset($_SESSION['fr_pw1']);
			}
		?> "name="pw1" /><br />
		
		<?php
			if (isset($_SESSION['e_pw'])){
				echo '<div class="error">'.$_SESSION['e_pw'].'</div>';
				unset($_SESSION['e_pw']);
			}
		?>		
		
		Re Password: <br/> <input type="password" value="<?php
			if (isset($_SESSION['fr_pw2'])){
				echo $_SESSION['fr_pw2'];
				unset($_SESSION['fr_pw2']);
			}
		?> "name="pw2" /><br />
		
		<label>
			<input type="checkbox" name="agreement" <?php
			if (isset($_SESSION['fr_agreement'])){
				echo "checked";
				unset($_SESSION['fr_agreement']);
			}
				?>/> Accept the Agreement
		</label>
		
		<?php
			if (isset($_SESSION['e_agreement'])){
				echo '<div class="error">'.$_SESSION['e_agreement'].'</div>';
				unset($_SESSION['e_agreement']);
			}
		?>	
		
		<div class="g-recaptcha" data-sitekey="Entry you recap"></div>
		
		<?php
			if (isset($_SESSION['e_bot'])){
				echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
				unset($_SESSION['e_bot']);
			}
		?>
		<br />
		<input type="submit" value="Register now!" />
	</form>
</body>
</html>