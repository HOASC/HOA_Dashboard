<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

	$user_id = $_SESSION['hoa_user_id'];
	$community_id = $_SESSION['hoa_community_id'];

	include 'includes/dbconn.php';

	$tag_id = $_POST['tag_id'];

	$result = pg_query("UPDATE home_tags SET valid_from='".date('Y-m-d')."', valid_until='".date('Y')."-12-31', issued_on='".date('Y-m-d')."', issued_by=$user_id, status='APPROVED' WHERE id=$tag_id");

	if($result)
		echo "<br><br><br><center><h3>Parking tag approved.</h3></center>";
	else
		echo "<br><br><br><center><h3>Some error occured. Please try again later.</h3></center>";

	echo "<center><a href='https://hoaboardtime.com/boardParkingTags.php'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardParkingTags.php'},1000);</script>";

?>