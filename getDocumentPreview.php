<?php
	
	session_start();

	require 'app/start.php';

	if($_SESSION['hoa_community_id'] == 2)
		$accessToken = 'QwUjEm5GAkAAAAAAAAAADocHK4CgCJoBl2A8-fe9Fs42E06qkDqJA2S9YPwGbZyF';

	$client = new Dropbox\Client($accessToken, $appName, 'UTF-8');

	$path = $_GET['path'];
	$description = $_GET['desc'];

	$client->getFile($path, fopen($description.".pdf", 'wb'));

	header("Location: https://hoaboardtime.com/".$description.".pdf");

?>