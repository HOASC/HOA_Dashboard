<?php

	include 'includes/dbconn.php';

	$tag_id = $_POST['tag_id'];

	$result = pg_query("UPDATE home_tags SET status='REMOVED' WHERE id=$tag_id");

	if($result)
	{
		
		echo "<br><br><br><center><h3>Parking tag removed.</h3></center>";

	}
	else
	{
		
		echo "<br><br><br><center><h3>Some error occured.<br><br>Please try again later.</h3></center>";

	}

	echo "<center><a href='https://hoaboardtime.com/residentParkingTags.php'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/residentParkingTags.php'},2000);</script>";

?>