<?php

	include 'includes/dbconn.php';

	$result = pg_query("SELECT * FROM homeid WHERE community_id=2");

	while($row = pg_fetch_assoc($result))
	{

		$home_id = $row['home_id'];

		$r = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE home_id=$home_id"));
		$hoa_id = $r['hoa_id'];

		//$r = pg_query("INSERT INTO current_year_payments_processed(home_id, hoa_id, community_id, year) VALUES($home_id, $hoa_id, 2, 2018)");

		if($r)
			echo "<br><br>$hoa_id - - - $home_id - - - $r";

	}

?>