<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

	$fname = $_POST['person_firstname'];
	$lname = $_POST['person_lastname'];
	$cell_no = $_POST['person_cell_no'];
	$email = $_POST['person_email'];
	$role_type = $_POST['role_type'];
	$relationship = $_POST['relationship'];
	$home_id = $_POST['home_id'];
	$hoa_id = $_POST['hoa_id'];
	$community_id = $_SESSION['hoa_community_id'];

	include 'includes/dbconn.php';

	$cell_no = base64_encode($cell_no);
	$ehoa_id = base64_encode($hoa_id);

	$date = date("Y-m-d");
	$year = date("Y");

	print_r($result);

	$result = pg_query("INSERT INTO person(fname, lname, cell_no, email, hoa_id, home_id, community_id, is_active, role_type_id, relationship_id, valid_from, valid_until) VALUES ('$fname', '$lname', '$cell_no', '$email', $hoa_id, $home_id, $community_id, 't', $role_type, $relationship, '$date', '12-31-$year')");

	if($result)
		echo "<br><br><br><br><center><h3>Person added successfully.</h3></center>";
	else
		echo "<br><br><br><br><center><h3>Some error occured. Please try again.</h3></center>";

	echo "<br><br><br><center><a href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$ehoa_id'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$ehoa_id'},1000);</script>";

?>