<?php

	ini_set("session.save_path","/var/www/html/session/");

    session_start();

    $community_id = $_SESSION['hoa_community_id'];

    include 'includes/dbconn.php';

    $home_id = $_POST['home_id'];
    $hoa_id = $_POST['hoa_id'];
    $home_pay_method = $_POST['edit_home_pay_method'];

    $result = pg_query("UPDATE home_pay_method SET payment_type_id=$home_pay_method WHERE home_id=$home_id AND hoa_id=$hoa_id");

    if($result)
    	echo "<br><br><br><center><h3>Home Pay Method Updated.</h3></center>";
    else
    	echo "<br><br><br><center><h3>Some error occured. Please try again.</h3></center>";


    echo "<br><br><br><br><center><a href='boardHOAHomeInfo.php'>Click here</a> if this page doesnot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='boardHOAHomeInfo.php'},1000);</script>";

?>