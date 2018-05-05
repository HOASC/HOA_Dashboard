<?php

include 'includes/dbconn.php';

$data = file_get_contents('php://input');
// error_reporting(E_ERROR | E_PARSE);
try{
if (true){
// $personQuery = "SELECT DISTINCT relationship_id,EMAIL,role_type_id FROM PERSON WHERE HOA_ID=".$data."";
// if(  $personResult = pg_query($personQuery) ){
// 	$emailsData = array();
// 	while ($row = pg_fetch_assoc($personResult)) {
// 		$data2 = array();
// 		$data2['relation'] =  $row['relationship_id'];
// 		$data2['email'] =  $row['email'];
// 		$data2['role_id'] =  $row['role_type_id'];
// 		array_push($emailsData, $data2);
// 	}
// 	$emailsData  = json_encode($emailsData);
// 	print_r($emailsData);


// }
	$q  = "SELECT * FROM HOMEID";
	$qr = pg_query($q);
	$homeDetails = array();
	while ( $r  = pg_fetch_assoc($qr) ){
		$homeDetails[$r['home_id']] = $r['address1'];
	}
	$data2  = array();
	$query = "SELECT * FROM HOAID WHERE HOA_ID=".$data."";
	if ( $queryResult = pg_query($query) ){
		while($row = pg_fetch_assoc($queryResult)){
			$data2['home_id'] =  $homeDetails[$row['home_id']];
			$data2['community_id'] = $row['community_id'];
		}
		echo json_encode($data2);
	}
	else {
	echo "An error occured";
	}
}
else {
	throw new Exception("Error Processing Request", 1);
}
}
catch(Exception  $e){
	echo $e->getMessage();
}
?>