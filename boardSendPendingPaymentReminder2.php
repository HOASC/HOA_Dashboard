<?php

	ini_set("session.save_path","/var/www/html/session/");
	include 'includes/api_keys.php';

    session_start();

	$mes = $_POST['message'];

	$community_id = $_SESSION['hoa_community_id'];

	echo $_POST['no_of_recipients']."<br><br>";
	//echo $mes."<br><br><br><br>";

	$mes = nl2br($mes);

	for($i = 0; $i < $_POST['no_of_recipients']; $i++)
		echo $_POST['home_id_'.$i]."<br>";


	$to = "";

    switch ($community_id) {
        case 2:
                $community = $m_community;
                $cnote = $m_cnote;
                $api_key = $m_api_key;
                $from = $m_from;
                break;
    }

    $content = $mes;
    $subject = 'Password Reset for HOA account '.$hoa_id;
    $uri = 'https://mandrillapp.com/api/1.0/messages/send.json';
    $content_text = strip_tags($content);

    $params = array(
        "key" => $api_key,
        "message" => array(
            "html" => $content,
            "text" => $content_text,
            "subject" => $subject,
            "from_email" => $from,
            "from_name" => $from,
            "to" => array(
                array("email" => $to, "name" => $to)
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
   	$postString = json_encode($params);
                      
   	curl_setopt($ch, CURLOPT_URL, $uri);
   	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
   	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
   	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   	curl_setopt($ch, CURLOPT_POST, true);
   	curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
                      
                    $result = curl_exec($ch);

                    $result = json_decode($result,TRUE);

                    $status = $result[0]['status'];

                    echo $status;
?>