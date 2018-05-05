<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

    $hoa_id = $_SESSION['hoa_hoa_id'];
    $community_id = $_SESSION['hoa_community_id'];

    include 'includes/dbconn.php';

    $change_mailing_address_visibility = $_POST['change_mailing_address_visibility'];
	$change_cell_visibility = $_POST['change_cell_visibility'];
	$change_email_visibility = $_POST['change_email_visibility'];
	$account_id = $_POST['account_id'];

	$result = pg_query("UPDATE account_info SET cell_visibility='$change_cell_visibility', email_visibility='$change_email_visibility', mailing_address_visibility='$change_mailing_address_visibility' WHERE account_id=$account_id");

	if($result)
		echo "<br><br><br><br><center><h3>Visibility Updated.</h3></center>";
	else
		echo "<br><br><br><br><center><h3>Some error occured. Please try again.</h3></center>";

	echo "<br><br><br><center><a href='https://hoaboardtime.com/residentDashboard.php'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/residentDashboard.php'},1000);</script>"

?>