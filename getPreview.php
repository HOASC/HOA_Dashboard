<?php
	
	require 'app/start.php';

	$path = '/logo.JPG';
	$description = 'logo.JPG';

	$accessToken = 'QwUjEm5GAkAAAAAAAAAADocHK4CgCJoBl2A8-fe9Fs42E06qkDqJA2S9YPwGbZyF';

	$client = new Dropbox\Client($accessToken, $appName, 'UTF-8');

	$client->getFile($path, fopen($description, 'w+'));

	header("Location: https://hoaboardtime.com/".$description);

?>