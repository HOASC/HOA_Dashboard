<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

	$community_id = $_SESSION['hoa_community_id'];

    include 'includes/dbconn.php';

    $id = $_POST['current_payments_id'];
	$hoa_id = $_POST['select_hoa'];

	$result = pg_query("UPDATE community_funding_transactions SET hoa_id=$hoa_id WHERE id=$id");

	if($result)
		echo "<br><br><br><br><center><h3>HOA ID Added.</h3></center>";
	else
		echo "<br><br><br><br><center><h3>Some error occured. Please try again.</h3></center>";

	echo "<br><br><br><center><a href='https://hoaboardtime.com/boardCommunityDeposit.php'>Click here</a> if this page doesnot redirect in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardCommunityDeposit.php'},1000);</script>";

?>