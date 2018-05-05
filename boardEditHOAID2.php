<?php
	
	ini_set("session.save_path","/var/www/html/session/");

	session_start();

    $community_id = $_SESSION['hoa_community_id'];

    include 'includes/dbconn.php';

    if(@!$_SESSION['hoa_username'])
      	header("Location: logout.php");
      	
    $hoa_id = $_POST['hoa_id'];
	$firstname = $_POST['edit_firstname'];
	$lastname = $_POST['edit_lastname'];
	$email = $_POST['edit_email'];
	$cell_no = $_POST['edit_cell_no'];
	$valid_from = $_POST['edit_valid_from'];
	$valid_until = $_POST['edit_valid_until'];

	$valid_from = date('Y-m-d', strtotime($valid_from));

	$today = date('Y-m-d');
	$user_id = $_SESSION['hoa_user_id'];

	if($valid_until == "")		
		$result = pg_query("UPDATE hoaid SET firstname='".$firstname."', lastname='".$lastname."', email='".$email."', cell_no='".$cell_no."', valid_from='".$valid_from."', updated_by=".$user_id.", updated_on='".$today."' WHERE hoa_id=".$hoa_id);
	else
	{	
		$valid_until = date('Y-m-d', strtotime($valid_until));

		$result = pg_query("UPDATE hoaid SET firstname='".$firstname."', lastname='".$lastname."', email='".$email."', cell_no='".$cell_no."', valid_from='".$valid_from."', valid_until='".$valid_until."', updated_by=".$user_id.", updated_on='".$today."' WHERE hoa_id=".$hoa_id);
	}

	if($result)
		echo "<br><br><br><br><center><h3>Updated successfully.</h3></center>";
	else
		echo "<br><br><br><br><center><h3>Failed to update. Please try again.</h3></center>";

	echo "<br><br><br><center><a href='https://hoaboardtime.com/boardHOAHomeInfo.php'>Click here</a> if this doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardHOAHomeInfo.php'},2000);</script>";

?>