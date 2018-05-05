<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

    $community_id = $_SESSION['hoa_community_id'];
    $hoa_id = $_POST['select_hoa'];
    $vendor_id = $_POST['select_vendor'];
	$document_to = $_POST['document_to'];
	$is_board_document = $_POST['board_document'];
	$flag = $_POST['flag'];

	include 'includes/dbconn.php';

	if($is_board_document == "Yes")
		$is_board_document = 't';
	else
		$is_board_document = 'f';

	$id = $_POST['id'];

	if($vendor_id != "" && $hoa_id != "")
	{

		echo "<br><br><br><br><h3><center>Select either user or vendor only.</center></h3>";

	}
	else if($vendor_id == "" && $hoa_id != "")
	{
		
		$result = pg_query("UPDATE community_sign_agreements SET hoa_id=$hoa_id, is_board_document='$is_board_document' WHERE id=$id");

		if($result)
			echo "<br><br><br><br><center><h3>HOA ID added successfully.</h3></center>";
		else
			echo "<br><br><br><br><center><h3>Some error occured. Please try again.</h3></center>";

	}
	else if($vendor_id != "" && $hoa_id == "")
	{
		
		$result = pg_query("UPDATE community_sign_agreements SET vendor_id=$vendor_id, is_board_document='$is_board_document' WHERE id=$id");

		if($result)
			echo "<br><br><br><br><center><h3>Vendor ID added successfully.</h3></center>";
		else
			echo "<br><br><br><br><center><h3>Some error occured. Please try again.</h3></center>";

	}
	else if($vendor_id == "" && $hoa_id == "")
	{

		echo "<br><br><br><br><h3><center>No user or vendor selected.</center></h3>";

	}

	if($flag == 1)
		echo "<br><br><br><center><a href='https://hoaboardtime.com/boardCommunitySignedAgreements.php'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardCommunitySignedAgreements.php'},1000);</script>";
	else if($flag == 2)
		echo "<br><br><br><center><a href='https://hoaboardtime.com/boardCommunitySignedAgreements.php'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardCommunityPendingAgreements.php'},1000);</script>";
?>