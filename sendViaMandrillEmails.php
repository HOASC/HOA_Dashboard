<?php
date_default_timezone_set('America/Los_Angeles');
$someJSON = file_get_contents('php://input');
$parsedJSON = json_decode($someJSON);
$hoaID = $parsedJSON[0]->hoaid;
$email = $parsedJSON[0]->email;
$emails = explode(' ', $email);
foreach ($emails as $em) {
	if ( $em != "" ){
	$url = "https://hoaboardtime.com/sendViaMandrill.php?hoaid=".$hoaID."&email=".$em;
	$req = curl_init();
	curl_setopt($req, CURLOPT_URL,$url);
	curl_exec($req);
	}
}
?>