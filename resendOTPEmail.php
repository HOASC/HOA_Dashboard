<?php

	ini_set("session.save_path","/var/www/html/session/");

    session_start();

    include 'includes/dbconn.php';
	include 'includes/api_keys.php';
    include 'includes/globalvar.php';
 
    $reset_email = $_REQUEST['forgot_password_email'];

   	$result = pg_query("SELECT * FROM usr WHERE email='$reset_email'");

    $row = pg_fetch_assoc($result);

    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $community_id = $row['community_id'];
    $id = $row['id'];

    $to = $reset_email;

    $otp = rand(100000,1000000);

    switch ($community_id) 
    {
    	case 1:
            
                $community = $m_community;
                $cnote = $m_cnote;
                $api_key = $m_api_key;
                $from = $m_from;
                break;
            
            

    	case 2:
            
                $community = $m_community;
                $cnote = $m_cnote;
                $api_key = $m_api_key;
                $from = $m_from;
                break;
            
            
    }                     
                    
    $content = 'Hello '.$first_name.' '.$last_name.',<br><br>Please use '.$otp.' as OTP for reseting your HOA account password.<br><br>Thank you<br>'.$cnote.'.';
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

    if($status == 'sent')
    {
                        
        $result = pg_query("UPDATE usr SET forgot_password_code='".$otp."' WHERE id=".$id);

        if($result)
        {
            
            echo "<br><br><br><center><h3>Email Sent.</h3></center><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/forgotPassword.php?forgot_password_email=".$reset_email."'},1000);</script>";

        }
        else
        	echo "<center><h3>Some error occured. Please try again.</h3><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/'},1000);</script></center>";
    }
    else
    	echo "<center><br><br><br><h3>Email cannot be sent. Please try again later.</h3><br><br><script>setTimeout(function(){window.location.href='https://hoaboardtime.com/'},1000);</script></center>";

?>