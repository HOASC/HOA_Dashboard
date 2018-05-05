<?php

	include 'includes/dbconn.php';
	
	$jsonData = file_get_contents("HOAView.json");
	$json = json_decode($jsonData, true);

	#echo $json['Inspection_Data_Input_Form'][0]['Viewed_From'];

	$count = sizeof($json['Inspection_Data_Input_Form']);

	for($i = 0; $i < $count; $i++)
	{
		
		$home = $json['Inspection_Data_Input_Form'][$i]['Address1'];
		$item = $json['Inspection_Data_Input_Form'][$i]['Lookup_1'];
		$category = $json['Inspection_Data_Input_Form'][$i]['Category'];
		$inspection_date = $json['Inspection_Data_Input_Form'][$i]['Filed_Date'];
		$description = $json['Inspection_Data_Input_Form'][$i]['Item_Notes'];
		$location = $json['Inspection_Data_Input_Form'][$i]['Viewed_From'];
		$status = $json['Inspection_Data_Input_Form'][$i]['Status'];
		$compliance_date = $json['Inspection_Data_Input_Form'][$i]['Compliance_Date'];

		$row = pg_fetch_assoc(pg_query("SELECT * FROM inspection_status WHERE inspection_status='$status'"));
		$inspection_status_id = $row['id'];

		$row = pg_fetch_assoc(pg_query("SELECT * FROM locations_in_community WHERE location='$location'"));
		$location_id = $row['location_id'];

		$row = pg_fetch_assoc(pg_query("SELECT * FROM inspection_category WHERE name='$category'"));
		$category_id = $row['id'];

		if($compliance_date != "")
			$compliance_date = date('Y-m-d', strtotime($compliance_date));

		if($inspection_date != "")
			$inspection_date = date('Y-m-d', strtotime($inspection_date));

		$row = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE address1='$home'"));
		$home_id = $row['home_id'];

		$row = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE home_id=$home_id"));
		$hoa_id = $row['hoa_id'];

		$date = date('Y-m-d');

		#echo $inspection_date." - - - ".$description." - - - ".$home_id." - - - ".$date." - - - ".$location_id." - - - ".$category_id." - - - ".$inspection_status_id." - - - ".$compliance_date." - - - ".$item."<br>";

		$result = pg_query("INSERT INTO inspection_notices (inspection_date, description, community_id, home_id, date_of_upload, location_id, inspection_category_id, hoa_id, inspection_status_id, compliance_date, updated_date, updated_by, item) VALUES ('$inspection_date', '$description', 2, $home_id, '$date', $location_id, $category_id, $hoa_id, $inspection_status_id, '$compliance_date', '$date', 258, '$item')");

		if($result)
			echo ($i+1)." - - - Success<br><br><br><br><br>";
		else
			echo ($i+1)." - - - Failed<br><br><br><br><br>";

	}

?>