<?php
	
	ini_set("session.save_path","/var/www/html/session/");

	session_start();

	ini_set('max_execution_time', 300);

	$community_id = $_SESSION['hoa_community_id'];

	include 'includes/dbconn.php';

	include 'password.php';

	$query = "SELECT * FROM usr WHERE email='".$_SESSION['hoa_email']."'";
	$result = pg_query($query);

	$row = pg_fetch_assoc($result);

	$db_password = $row['password'];
	$old_password = $_POST['old_password'];

	if(password_verify($old_password, $db_password))
	{
		$new_password = $_POST['new_password'];
		$confirm_password = $_POST['confirm_password'];

		if($new_password == $confirm_password)
		{
			$pass = password_hash($confirm_password, PASSWORD_BCRYPT);

			$result = pg_query("UPDATE usr SET password='".$pass."', modified_date='".date('Y-m-d')."' WHERE email='".$_SESSION['hoa_email']."'");

			echo "<br><br><br><center><h3>Password successfully changed.</h3><center>";
		}
		else
		{
			echo "<br><br><br><center><h3>New password confirmation failed. Please try again.</h3><center>";
		}
	}
	else
	{
		echo "<br><br><br><center><h3>The old password you have entered is incorrect.</h3><center>";
	}

	echo "<br><br><br><center><a href='https://hoaboardtime.com/residentProfile.php'>Click here</a> if this page doesnot redirect in 5 seconds.<center>";
	echo "<script>setTimeout(function(){window.location.href='https://hoaboardtime.com/residentProfile.php'},1000);</script>";
	#$result = pg_query("UPDATE usr set password = ");
?>