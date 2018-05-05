<?php
	date_default_timezone_set('America/Los_Angeles');
	ini_set("session.save_path","/var/www/html/session/");
	session_start();
	include 'includes/dbconn.php';
	$hoa_id = $_SESSION['hoa_hoa_id'];
    $ip = $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']);
	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	$escapedAgent = pg_escape_string($userAgent);
	$insertResult = pg_query("INSERT INTO user_access_log (ip_address, user_agent, hoa_id, access_date,access_page) VALUES ('$ip', '{$escapedAgent}', $hoa_id, '".date('Y-m-d H:i:s')."','Logout')");
    session_unset();
    session_destroy();
    session_write_close();
    //setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);	
	header('Location: https://hoaboardtime.com/');
	
?>