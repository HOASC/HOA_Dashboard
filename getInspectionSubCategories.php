<?php

include 'includes/dbconn.php';

$data = file_get_contents('php://input');
error_reporting(E_ERROR | E_PARSE);
try{
if (true){
		$query  = "SELECT * FROM INSPECTION_SUB_CATEGORY WHERE INSPECTION_CATEGORY_ID = (SELECT ID FROM INSPECTION_CATEGORY WHERE NAME='".$data."')";
		$queryResult = pg_query($query);
		$returnData = array();
		while ($row = pg_fetch_assoc($queryResult)) {
				$data2 = array();
				array_push($data2, $row['id']);
				array_push($data2,$row['name']);
				array_push($returnData, $data2);
		}
		echo json_encode($returnData);
	}
else {
	throw new Exception("Error Processing Request", 1);
}
}
catch(Exception  $e){
	echo $e->getMessage();
}
?>