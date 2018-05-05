<?php
$data = file_get_contents('php://input');
$parseJSON = json_decode($data);
$hoaID = $parseJSON[0]->member_id;
include 'includes/dbconn.php';
$query = "SELECT HOME_ID FROM HOAID WHERE HOA_ID = ".$hoaID;
$queryResult = pg_query($query);
$row = pg_fetch_assoc($queryResult);
$homeID = $row['home_id'];

$query = "SELECT * FROM HOMEID WHERE HOME_ID=".$homeID;
$queryResult = pg_query($query);
$row  = pg_fetch_assoc($queryResult);

if ( $row['living_status'] == 'f') {
	$query = "SELECT * FROM HOME_MAILING_ADDRESS WHERE HOME_ID=".$row['home_id'];
	$queryResult = pg_query($query);
	$row2 = pg_fetch_assoc($queryResult);

	$returnArray = array();

	array_push($returnArray, $row['address1']);
	array_push($returnArray, $row2['address1']);
	echo json_encode($returnArray);
}

else {
	$returnArray = array();
	array_push($returnArray, $row['address1']);
	echo json_encode($returnArray);
}

?>