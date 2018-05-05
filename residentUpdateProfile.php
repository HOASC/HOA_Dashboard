<?php

	ini_set("session.save_path","/var/www/html/session/");

    session_start();

    if(@!$_SESSION['hoa_username'])
     	header("Location: logout.php");

    $community_id = $_SESSION['hoa_community_id'];
    $hoa_id = $_SESSION['hoa_hoa_id'];
    $user_id = $_SESSION['hoa_user_id'];
    $today = date('Y-m-d');

    include 'includes/dbconn.php';

    $email = $_POST['email'];
	$cell_no = $_POST['cell_no'];

    $cell_no = base64_encode($cell_no);

	$query = "UPDATE hoaid SET email='".$email."', cell_no='".$cell_no."', updated_on='".$today."', updated_by=".$user_id." WHERE hoa_id=".$hoa_id;
    $result = pg_query($query);

    if($result)
    	echo "<br><br><br><br><center><h3>Profile Updated Successfully.</h3></center>";
    else
    	echo "<br><br><br><br><center><h3>Some error occured. Please try again.</h3></center>";

    echo "<br><br><center><a href='https://hoaboardtime.com/residentProfile.php'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/residentProfile.php'},1000);</script>";
        
?>