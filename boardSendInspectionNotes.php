<?php

	ini_set("session.save_path","/var/www/html/session/");

	session_start();

	$community_id = $_SESSION['hoa_community_id'];

	include 'includes/dbconn.php';
	include 'includes/api_keys.php';

	$id = $_POST['id'];
	$date = $_POST['date'];
	$inspection_notice = $_POST['inspection_notice'];
	$initial_notice = $_POST['initial_notice'];
	$compliance_date = $_POST['compliance_date'];
	$viewed_from = $_POST['viewed_from'];
	$item = $_POST['item'];
	$observation = $_POST['observation'];
	$home = $_POST['home'];
	$owner = $_POST['owner'];
	$notice_summary = $_POST['notice_summary'];
	$submit = $_POST['submit'];

	$row = pg_fetch_assoc(pg_query("SELECT * FROM inspection_status WHERE id=".$status_requested_id));
	$status_requested = $row['inspection_status'];

	$row = pg_fetch_assoc(pg_query("SELECT * FROM homeid WHERE address1='".$home."'"));
	$home_id = $row['home_id'];

	$result = pg_query("INSERT INTO inspection_notes (inspection_notices_id, notes_date, detail) VALUES ($id, '$date', '$notice_summary')");

	if($result)
	{

		$vendor_to = "project-13225888-c32e66904296f8f2b2f5c19f@basecamp.com";

		switch ($community_id) {
	        case 2:
	            $community = $m_community;
	            $cnote = $m_cnote;
	            $api_key = $m_api_key;
	            $from = $m_from;
	            break;
	    }
   
	    $content = 'Date : '.$date.'<br>Inspection Notice : '.$inspection_notice.'<br>Compliance Date : '.$compliance_date.'<br>Viewed From : '.$viewed_from.'<br>Item : '.$item.'<br>Observation : '.$observation.'<br>Home : '.$home.'<br>Owner : '.$owner.'<br>Inspection Notes Summary : '.$notice_summary;

	    $count = pg_num_rows(pg_query("SELECT * FROM home_mailing_address WHERE home_id=$home_id"));

	    if($count)
	    	$content .= "<br>Occupied By : Tenant";

	    $content .= '.';

	    $subject = $home.' posted a question';
	    $uri = 'https://mandrillapp.com/api/1.0/messages/send.json';
	    $content_text = strip_tags($content);

	    $params2 = array(
	        "key" => $api_key,
	        "message" => array(
	            "html" => $content,
	            "text" => $content_text,
	            "subject" => $subject,
	            "from_email" => $from,
	            "from_name" => $from,
	            "to" => array(
	                array("email" => $vendor_to, "name" => $vendor_to)
	            ),
	            "track_opens" => true,
	            "track_clicks" => true,
	            "auto_text" => true,
	            "url_strip_qs" => true,
	            "preserve_recipients" => true
	        ),
	        "async" => false
	   	);

	   	$ch = curl_init();

	   	$postString = json_encode($params2);

		curl_setopt($ch, CURLOPT_URL, $uri);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

		$result = curl_exec($ch);

		$result = json_decode($result, true);

	    echo "<br><br><center><h3>Email Sent.</h3></center>";

	}
	else
		echo "<br><br><center><h3>Some error occured. Please try again1.</h3></center>";

	echo "<center><a href='https://hoaboardtime.com/residentViolationCitation.php'>Click here</a> if this page doesnot redirect automatically within 5 seconds.</center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/residentViolationCitation.php'},1000);</script>";
?>