<?php

  	ini_set("session.save_path","/var/www/html/session/");
  	error_reporting(E_ALL);
	ini_set('display_errors', 1);
  	session_start();
  	date_default_timezone_set('America/Los_Angeles');
	$login_email = $_POST['login_email'];
	$login_password = $_POST['login_password'];

	$ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
	$userAgent = $_SERVER['HTTP_USER_AGENT'];


	$now = date('Y-m-d H:i:s');

	$escapedAgent = pg_escape_string($userAgent);	


	ini_set('max_execution_time', 180);

	include 'password.php';

	include 'includes/dbconn.php';





	$query = "SELECT * FROM usr WHERE email='".$login_email."'";
	$result = pg_query($query);
	$num_row = pg_num_rows($result);

	if($num_row == 0)
	{
		echo '<script type="text/javascript">'; 
		echo 'alert("User doesnot exist.");'; 
		echo 'window.location.href = "index.php";';
		echo '</script>';
	}
	else
	{
		$row =pg_fetch_assoc($result);
		$password = $row['password'];

		if(password_verify($login_password, $password))
		{
			$name = $row['first_name'];
			$name .= " ";
			$name .= $row['last_name'];
			$id = $row['id'];
			$otp = "";

			$_SESSION['hoa_username'] = $name;
			$_SESSION['hoa_email'] = $login_email;
			$_SESSION['hoa_user_id'] = $row['id'];
			$_SESSION['hoa_community_id'] = $row['community_id'];
				
			$result123 = pg_query("UPDATE usr SET forgot_password_code='".$otp."' WHERE id=".$id);
				
			$query = "SELECT * FROM hoaid WHERE email='".$login_email."'";
			$result = pg_query($query);
			$row = pg_fetch_assoc($result);

			$_SESSION['hoa_hoa_id'] = $row['hoa_id'];
			$_SESSION['hoa_home_id'] = $row['home_id'];

			$query = "SELECT * FROM homeid WHERE home_id=".$_SESSION['hoa_home_id'];
			$result = pg_query($query);
			$row =pg_fetch_assoc($result);

			$_SESSION['hoa_address'] = $row['address1'];

			$query = "SELECT * FROM community_info WHERE community_id=".$_SESSION['hoa_community_id'];
			$result = pg_query($query);
			$row =pg_fetch_assoc($result);

			$_SESSION['hoa_community_name'] = $row['legal_name'];
			$_SESSION['hoa_community_code'] = $row['community_code'];
			$_SESSION['hoa_community_website_url'] = $row['community_website_url'];

			$query = "SELECT * FROM board_committee_details WHERE user_id=".$_SESSION['hoa_user_id'];
			$result = pg_query($query);
			$num_row = pg_num_rows($result);

			$escapedAgent = pg_escape_string($userAgent);	

			if ( !isset($ip) ) {
				$ip = 'UNKNOW';
			}

			if ( !isset($escapedAgent) ) {
				$escapedAgent  = 'UNKNOW';
			}


			$hoa_id = $_SESSION['hoa_hoa_id'];
			$insertResult = pg_query("INSERT INTO user_access_log (ip_address, user_agent, hoa_id, access_date,access_page) VALUES ('$ip', '{$escapedAgent}', $hoa_id, '".date('Y-m-d H:i:s')."','Login')");


			if($num_row == 0)
			{
					
				$result123 = pg_query("UPDATE usr SET last_login='$now' WHERE id=$id");

				$_SESSION['hoa_mode'] = 2;

				header("Location: residentDashboard.php");

			}
			else
			{

				$result123 = pg_query("UPDATE usr SET last_login='$now' WHERE id=$id");

				$_SESSION['hoa_mode'] = 1;

				header("Location: boardDashboard.php");

			}

		}
		else
		{
			
			echo '<script type="text/javascript">'; 
			echo 'alert("Invalid password entered.Please check the password and try again.");'; 
			echo 'window.location.href = "index.php";';
			echo '</script>';

		}
	}
?>