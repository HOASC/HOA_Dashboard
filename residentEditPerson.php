<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

	$user_id = $_SESSION['hoa_user_id'];
	$community_id = $_SESSION['hoa_community_id'];

	include 'includes/dbconn.php';

	$fname = $_POST['person_firstname'];
	$lname = $_POST['person_lastname'];
	$cell_no = $_POST['person_cell_no'];
	$email = $_POST['person_email'];
	$role_type = $_POST['role_type'];
	$relationship = $_POST['relationship'];
	$person_id = $_POST['person_id'];
	$hoa_id = $_POST['hoa_id'];
	$today = date('Y-m-d');

	$result = pg_query("UPDATE person SET fname='$fname', lname='$lname', email='$email', cell_no=$cell_no, role_type_id=$role_type, relationship_id=$relationship, updated_by=$user_id, updated_on='$today' WHERE id=$person_id");

	if($result)
		echo "<br><br><br><br><center><h3>Person details updated successfully.</h3></center>";
	else
		echo "<br><br><br><br><center><h3>Some error occured. Please try again.</h3></center>";

	echo "<br><br><br><center><a href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$hoa_id'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/residentProfile.php'},1000);</script>";

?>