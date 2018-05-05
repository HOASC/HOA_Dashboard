<?php

  	ini_set("session.save_path","/var/www/html/session/");

  	session_start();

	$community_id = $_SESSION['hoa_community_id'];

	include 'includes/dbconn.php';

	function encrypt_string($input)
	{
									 
		$inputlen = strlen($input);// Counts number characters in string $input
		$randkey = rand(1, 9); // Gets a random number between 1 and 9
									 
		$i = 0;
		while ($i < $inputlen)
		{
									 
			$inputchr[$i] = (ord($input[$i]) - $randkey);//encrpytion 
									 
			$i++; // For the loop to function

		}
									 
		//Puts the $inputchr array togtheir in a string with the $randkey add to the end of the string
		$encrypted = implode('.', $inputchr) . '.' . (ord($randkey)+50);
		return $encrypted;
	}

	$car_id = $_POST['car_id'];
	$make = $_POST['edit_make'];
	$model = $_POST['edit_model'];
	$color = $_POST['edit_color'];
	$year = $_POST['edit_year'];
	$plate = $_POST['edit_plate'];

	$plate = encrypt_string($plate);
	$plate = base64_encode($plate);

	$result = pg_query("UPDATE car_detail SET car_make_id=$make, car_model_id=$model, car_color_id=$color, year=$year, notes='$plate' WHERE id=$car_id");

	if($result)
		echo "<br><br><br><center><h3>Car details updated.</h3></center>";
	else
		echo "<br><br><br><center><h3>Some error occured. Please try again later.</h3></center>";

	echo "<br><br><center><a href='https://hoaboardtime.com/residentParkingTags.php'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/residentParkingTags.php'},2000);</script>";

?>