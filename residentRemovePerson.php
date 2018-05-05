<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

	$person_id = $_POST['person_id'];
	$hoa_id = $_POST['hoa_id'];
	$user_id = $_SESSION['hoa_user_id'];
	$today = date('Y-m-d');

	$community_id = $_SESSION['hoa_community_id'];

	include 'includes/dbconn.php';

	$result = pg_query("UPDATE person SET is_active='f', updated_by=$user_id, updated_on='$today' WHERE id=$person_id");

	if($result)
		echo "<br><br><br><br><center><h3>Person removed.</h3></center>";
	else
		echo "<br><br><br><br><center><h3>Some error occured. Please try again.</h3></center>";

	echo "<br><br><br><center><a href='https://hoaboardtime.com/boardUserDashboard2.php?hoa_id=$hoa_id'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/residentProfile.php'},1000);</script>";

?>