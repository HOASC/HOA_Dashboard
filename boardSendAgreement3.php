<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

	$name = $_POST['name'];
	$hoa_id = $_POST['hoa_id'];
	$living_in = $_POST['living_in'];
	$home_id = $_POST['home_id'];
	$email = $_POST['email'];
	$community_id = $_POST['community_id'];

	$agreement_type = $_POST['agreement_type'];

	if($community_id == 2) {

		switch ($agreement_type) {

			case 1:
				
				header("Location: https://hoaboardtime.com/qBoardEmailConsent.php?id=$hoa_id");
				
				break;

			case 2:

				header("Location: https://hoaboardtime.com/qBoardPool.php?id=$hoa_id");

				break;

			case 4:
				
				header("Location: https://hoaboardtime.com/qBoardCollectionPolicy.php?id=$hoa_id");
				
				break;
		}
		
	}
	else
		header("Location: https://hoaboardtime.com/boardCommunityAgreements.php");

?>