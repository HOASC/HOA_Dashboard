<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

	$community_id = $_SESSION['hoa_community_id'];

	include 'includes/dbconn.php';

	header ( "Content-Type: application/vnd.ms-excel" );
    header ( "Content-disposition: attachment; filename=".$_SESSION['hoa_community_code']."-Mailing_List-_".date('m-d-Y H:i:s').".csv" );
    header ( "Content-Type: application/force-download" );
    header ( "Content-Transfer-Encoding: binary" );
    header ( "Pragma: no-cache" );
    header ( "Expires: 0" );

    echo ('"HOA ID","First Name","Last Name","Home ID","Mailing Address","Mailing City","Mailing State","Mailing Zip","Email","Phone"');
	
	echo "\n";

	$i = 0;

	$result = pg_query("SELECT * FROM homeid WHERE community_id=$community_id ORDER BY home_id");

	while($row = pg_fetch_assoc($result))
	{
		$home_id = $row['home_id'];
		$living_status = $row['living_status'];

		if($living_status == 't')
		{
			$address = $row['address1'];
			$city = $row['city_id'];
			$state = $row['state_id'];
			$zip = $row['zip_id'];
		}
		else
		{
			$row1 = pg_fetch_assoc(pg_query("SELECT * FROM home_mailing_address WHERE home_id=$home_id"));

			$address = $row1['address1'];
			$city = $row1['city_id'];
			$state = $row1['state_id'];
			$zip = $row1['zip_id'];
		}

		$row1 = pg_fetch_assoc(pg_query("SELECT * FROM city WHERE city_id=$city"));
		$city = $row1['city_name'];

		$row1 = pg_fetch_assoc(pg_query("SELECT * FROM state WHERE state_id=$state"));
		$state = $row1['state_code'];

		$row1 = pg_fetch_assoc(pg_query("SELECT * FROM zip WHERE zip_id=$zip"));
		$zip = $row1['zip_code'];

		$row1 = pg_fetch_assoc(pg_query("SELECT * FROM hoaid WHERE home_id=$home_id"));
		$firstname = $row1['firstname'];
		$lastname = $row1['lastname'];
		$hoa_id = $row1['hoa_id'];
		$email = $row1['email'];
		$cell_no = $row1['cell_no'];

		if($cell_no != '')
			$cell_no = base64_decode($cell_no);

		echo ('"'.$hoa_id.'","'.$firstname.'","'.$lastname.'","'.$home_id.'","'.$address.'","'.$city.'","'.$state.'","'.$zip.'","'.$email.'","'.$cell_no.'"');
		echo "\n";
	}

?>