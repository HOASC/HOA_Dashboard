<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

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

	$cell_no = base64_encode($cell_no);
	$ehoa_id = base64_encode($hoa_id);

	$result = pg_query("UPDATE person SET fname='$fname', lname='$lname', email='$email', cell_no='$cell_no', role_type_id=$role_type, relationship_id=$relationship WHERE id=$person_id");

	if($result)
		echo "<br><br><br><br><center><h3>Person details updated successfully.</h3></center>";
	else
		echo "<br><br><br><br><center><h3>Some error occured. Please try again.</h3></center>";

	echo "<br><br><br><center><a href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$ehoa_id'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$ehoa_id'},1000);</script>";

?>