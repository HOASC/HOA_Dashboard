<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

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

	$hoa_id = $_SESSION['hoa_hoa_id'];
	$user_id = $_SESSION['hoa_user_id'];
	$community_id = $_SESSION['hoa_community_id'];

	include 'includes/dbconn.php';

	$row = pg_fetch_assoc(pg_query("SELECT * FROM car_detail ORDER BY id DESC"));
	$id = $row['id'];
	$id++;

	$make = $_POST['add_make'];
	$model = $_POST['add_model'];
	$color = $_POST['add_color'];
	$year = $_POST['add_year'];
	$plate = $_POST['add_plate'];

	$plate = encrypt_string($plate);
	$plate = base64_encode($plate);

	$result = pg_query("INSERT INTO car_detail (id, car_make_id, car_model_id, car_color_id, year, notes) VALUES ($id, $make, $model, $color, $year, '$plate')");

	if($result)
	{	
		
		$result = pg_query("INSERT INTO home_tags (detail, type, issued_to, hoa_id, community_id, status) VALUES ($id, 1, $user_id, $hoa_id, $community_id, 'PENDING')");

		if($result)
		{

			echo "<br><br><br><center><h3>Car tag added successfully.</h3></center>";

		}
		else
		{	

			$result = pg_query("DELETE FROM car_detail WHERE id=$id");

			echo "<br><br><br><center><h3>Some error occured. Please try again later 123.</h3></center>";

		}

	}
	else
		echo "<br><br><br><center><h3>Some error occured. Please try again later.</h3></center>";

	echo "<br><br><center><a href='https://hoaboardtime.com/residentParkingTags.php'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/residentParkingTags.php'},2000);</script>";

?>