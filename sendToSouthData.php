<?php
header("Content-Type: text/event-stream\n\n");
include 'includes/dbconn.php';

ini_set("session.save_path","/var/www/html/session/");
session_start();
if ( $_SESSION['hoa_user_id'] ){
    $dropboxInsertUserID = $_SESSION['hoa_user_id'];
}
else {
    $dropboxInsertUserID = 401;
}



if ($_GET['data']){	


	 $dropboxQuery = "SELECT oauth2_key FROM dropbox_api WHERE community_id=2";
  $dropboxQueryResult = pg_fetch_assoc(pg_query($dropboxQuery));
  $accessToken = base64_decode($dropboxQueryResult['oauth2_key']);

	$url = 'https://content.dropboxapi.com/2/files/download';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken,'Dropbox-API-Arg: {"path": "'.$_GET['data'].'"}'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = curl_exec($ch);
	if ( strpos($response, "Error in call to API function") !== false ) {
		$message =  "An error occured. Please try again.";
		echo 'data: '.$message."\n\n";  
		ob_end_flush();
		flush();
		exit(0);
	}
	$dropboxInsertQuery = "INSERT INTO dropbox_stats(user_id,action,dropbox_path,requested_on) VALUES(".$dropboxInsertUserID.",'".$_GET['data']."','".date('Y-m-d H:i:s')."')";
	pg_query($dropboxInsertQuery);
	else {
		$fileContent = base64_encode($response);
		$req = curl_init();
		curl_setopt($req, CURLOPT_URL,"http://southdata.us-west-2.elasticbeanstalk.com/TestOrderMailing.aspx?id=".$fileContent."&hoaid=".$_GET['data1']);
		curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
		if(curl_exec($req) === false)
		{
    			$message =  "An error occured. Please try again.";
    			echo 'data: '.$message."\n\n";  
				ob_end_flush();
				flush();
				exit(0);
		}
		else 
		{	
				$message = "File uploaded to South Data.";
				echo 'data: '.$message."\n\n";  
				ob_end_flush();
				flush();
		}
		
	}
}
else {
	$message =  "An error occured. Please try again.";
	echo 'data: '.$message."\n\n";  
	ob_end_flush();
	flush();
	exit(0);
}

?>