<?php

	include 'password.php';

	include 'includes/dbconn.php';

	$reset_email = $_POST['forgot_password_email'];
	$new_password = $_POST['new_password'];
	$confirm_password = $_POST['confirm_password'];
	$otp_entered = $_POST['otp_entered'];

	if($new_password != $confirm_password)
	{

		echo "<br><br><br><br><br><center><h3>New password and Re-type password are not same. Please enter same password and try again.</h3></center>";

		echo "<script>setTimeout(function(){window.location.href='forgotPassword2.php?forgot_password_email=".$reset_email."&otp_entered=".$otp_entered."'},2000);</script>";

	}
	else
	{

		$pass = password_hash($confirm_password, PASSWORD_BCRYPT);

		$row = pg_fetch_assoc(pg_query("SELECT * FROM usr WHERE email='$reset_email'"));

		$id = $row['id'];
		$otp = "";

		$result = pg_query("UPDATE usr SET password='".$pass."', forgot_password_code='".$otp."', modified_date='".date('Y-m-d')."' WHERE id=".$id);

		if($result)
			echo "<br><br><br><center><h3>Password changed successfully.<br>You can now use your new password to access your HOA account.</h3></center>";
		else
			echo "<br><br><br><center><h3>Some error occured. Please try again.</h3></center>";

		echo "<script>setTimeout(function(){window.location.href='index.php'},2000);</script>";
	}

?>