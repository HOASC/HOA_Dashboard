<?php

include 'includes/dbconn.php';
	$make = $_POST['make_id'];
	$output = "<option disabled>Select Model</option>";

	$result = pg_query("SELECT * FROM car_model WHERE car_make_id=$make");

	while($row = pg_fetch_assoc($result))
	{
		$id = $row['id'];
		$name = $row['name'];

		$output .= "<option value='$id'>$name - $id</option>";
	}

	echo $output;

?>