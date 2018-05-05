<?php
date_default_timezone_set('America/Los_Angeles');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/dbconn.php';

$hoaID = $_GET['hoa_id'];
$query = "SELECT EMAIL FROM HOAID WHERE HOA_ID=".$hoaID;
$queryResult = pg_query($query);
$row = pg_fetch_assoc($queryResult);

if ( isset($row['email']) ){
	$email  = $row['email'];
	$url = "https://hoaboardtime.com/sendViaMandrill.php?hoaid=".$hoaID."&email=".$email;
	$req = curl_init();
	curl_setopt($req, CURLOPT_URL,$url);
	curl_setopt($req, CURLOPT_RETURNTRANSFER, TRUE);
	curl_exec($req);

	$response = array();
	$response["response"] = "success";

	echo json_encode($response);
}
else {
	$response = array();
	$response["response"] = "error";
	echo json_encode($response);
}

?>