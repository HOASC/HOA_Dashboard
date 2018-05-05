<?php

	ini_set("session.save_path","/var/www/html/session/");
	session_start();

	include "includes/api_keys.php";

    date_default_timezone_set('America/Los_Angeles');

	header("Content-type: application/pdf");
	
	$agreementID  = $_GET['id'];
	$url  = 'https://api.na1.echosign.com:443/api/rest/v5/agreements/';
	$url  = $url.$agreementID."/combinedDocument";
	$ch = curl_init($url);
	
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

	if($_SESSION['hoa_community_id'] == 2)
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Access-Token:'.$echo_sign_access_token));

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
	$result = curl_exec($ch);
	
	print_r($result);

	pg_close();

?>