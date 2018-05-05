<?php

	ini_set("session.save_path","/var/www/html/session/");

    session_start();

    $community_id = $_SESSION['hoa_community_id'];

    include 'includes/dbconn.php';

    $disclosure_id = $_POST['disclosure_id'];
    $edit_disclosure_type = $_POST['edit_disclosure_type'];
    $edit_actual_date = $_POST['edit_actual_date'];
    $edit_notes = $_POST['edit_notes'];

    if($edit_disclosure_type != "")
    	$result = pg_query("UPDATE community_disclosures SET type_id=$edit_disclosure_type WHERE id=$disclosure_id");
    
    if($edit_actual_date != "")
    	$result = pg_query("UPDATE community_disclosures SET actual_date='$edit_actual_date' WHERE id=$disclosure_id");
    
    if($edit_notes != "")
    	$result = pg_query("UPDATE community_disclosures SET notes='$edit_notes' WHERE id=$disclosure_id");

    echo "<br><br><br><br><center><h3>Disclosure Updated.</h3></center>";

    echo "<br><br><center><a href='https://hoaboardtime.com/boardCommunityDisclosures.php'>Click here</a> if this page doenot redirect automatically in 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/boardCommunityDisclosures.php'},1000);</script>";

?>